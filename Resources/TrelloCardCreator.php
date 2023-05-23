<?php

/**
*  █████╗ ██╗     ███████╗██████╗ ███████╗██╗     ██╗      ██████╗    ██████████╗ 
* ██╔══██╗██║     ██╔════╝██╔══██╗██╔════╝██║     ██║     ██╔═══██╗   ██╔═██╔═██║ 
* ███████║██║     █████╗  ██████╔╝█████╗  ██║     ██║     ██║   ██║   ██║ ██║ ██║ 
* ██╔══██║██║     ██╔══╝  ██╔══██╗██╔══╝  ██║     ██║     ██║   ██║   ██║ ██████║
* ██║  ██║███████╗██║     ██║  ██║███████╗███████╗███████╗╚██████╔╝   ██████████║
* ╚═╝  ╚═╝╚══════╝╚═╝     ╚═╝  ╚═╝╚══════╝╚══════╝╚══════╝ ╚═════╝    ╚═════════╝
*/

/**
* Description: Alfred Workflow for creating cards on a Trello board
* Version: 2.0
* Updated: 23/05/2023
* Source: https://github.com/MikoMagni/Alfrello
*/

/**
 * Trello Card Creator for creating cards on a Trello board.
 */
class TrelloCardCreator
{
    // Private properties to store API, board details and field order
    private $trelloApi;
    private $trelloBoardId;
    private $trelloListName;
    private $trelloFieldOrder;
    
    /**
     * Constructs a new instance of the TrelloCardCreator class.
     *
     * @param string $trelloKey The Trello API key.
     * @param string $trelloToken The Trello API token.
     * @param string $trelloBoardId The ID of the Trello board.
     * @param string $trelloListName The name of the Trello list.
     * @param array $trelloFieldOrder The order of the Trello fields.
     */
    public function __construct($trelloKey, $trelloToken, $trelloBoardId, $trelloListName, $trelloFieldOrder) {
        $this->trelloApi = new TrelloApi($trelloKey, $trelloToken);
        $this->trelloBoardId = $trelloBoardId;
        $this->trelloListName = $trelloListName;
        $this->trelloFieldOrder = $trelloFieldOrder;
    }

    /**
     * Safely trims a string value.
     *
     * @param mixed $item The value to trim.
     * @return mixed The trimmed value.
     */
    private function safeTrim($item) {
        return is_null($item) ? $item : trim($item);
    }

    /**
     * Retrieves the ID of the Trello list.
     *
     * @return mixed The ID of the Trello list, or false if not found.
     * @throws Exception When failed to retrieve Trello lists.
     */
    private function getListId() {
        $lists = $this->trelloApi->getLists($this->trelloBoardId);
        if ($lists === null) {
            throw new Exception("Failed to retrieve Trello lists. Check your internet connection.");
        }
        if (count($lists) > 0) {
            $trelloListId = $lists[0]->id;
            foreach($lists as $list) {
                if ($list->name == $this->trelloListName) {
                    $trelloListId = $list->id;
                }
            }
            return $trelloListId;
        }
        return false;
    }

    /**
     * Retrieves the IDs of the labels based on the input labels.
     *
     * @param mixed $labels The available labels.
     * @param string $inputLabels The input labels.
     * @return array The IDs of the matching labels.
     */
    private function getLabelIds($labels, $inputLabels) {
        if (empty($inputLabels)) {
            return [];
        }
        $labelIds = [];
        $inputLabelNames = array_map([$this, 'safeTrim'], explode(",", $inputLabels));

        $allLabels = !empty($inputLabelNames) ? in_array('all', array_map(function($item) {
            return is_null($item) ? '' : strtolower($item);
        }, $inputLabelNames)) : false;

        foreach ($labels as $label) {
            if ($allLabels) {
                $labelIds[] = $label->id;
            } else {
                foreach ($inputLabelNames as $inputLabelName) {
                    $isNameMatch = strtolower($label->name ?? '') == strtolower($inputLabelName ?? '');
                    $isColorMatch = strtolower(str_replace(' ', '', $label->color ?? '')) == strtolower(str_replace(' ', '', $inputLabelName ?? ''));
                    if ($isNameMatch || $isColorMatch) {
                        $labelIds[] = $label->id;
                    }
                }
            }
        }
        return $labelIds;
    }

    /**
     * Retrieves the IDs of the members based on the member names.
     *
     * @param string $memberNames The member names.
     * @return array The IDs of the matching members.
     */
    private function getMemberIds($memberNames) {
        $memberNames = array_map('trim', explode(",", $memberNames));
        $memberIds = [];
        $members = $this->trelloApi->getMembers($this->trelloBoardId);
    
        foreach ($memberNames as $memberName) {
            $memberName = ltrim(strtolower($memberName), '@');
    
            if (strtolower($memberName) === 'all') {
                foreach ($members as $member) {
                    $memberIds[] = $member->id;
                }
            } elseif ($memberName === 'me') {
                $url = "{$this->trelloApi->apiEndpoint}/members/me?key={$this->trelloApi->key}&token={$this->trelloApi->token}";
                $member = json_decode($this->trelloApi->httpRequest($url));
                $memberIds[] = $member->id;
            } else {
                foreach ($members as $member) {
                    if (strtolower($member->username) == $memberName) {
                        $memberIds[] = $member->id;
                        break;
                    }
                }
            }
        }
    
        return $memberIds;
    }
    
    /**
     * Creates a Trello card based on the card data.
     *
     * @param array $cardData The card data.
     * @return void
     */
    public function createTrelloCard($cardData) {
        try {
            $trelloListId = $this->getListId();
            if ($trelloListId === false) {
                echo 'Error: Trello list not found.';
                return;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return;
        }

        $labels = $this->trelloApi->getLabels($this->trelloBoardId);
        $labelIds = $this->getLabelIds($labels, $cardData['input_labels']);
        $labelIds = array_filter($labelIds); // Filter out any empty values

        $memberIds = $this->getMemberIds($cardData['assigned_member']);

        $data = [
            'key' => $this->trelloApi->key,
            'token' => $this->trelloApi->token,
            'name' => $cardData['name'],
            'desc' => $cardData['desc'],
            'idLabels' => implode(',', $labelIds),
            'due' => $cardData['due'],
            'idList' => $trelloListId,
            'pos' => $cardData['position'],
            'urlSource' => $cardData['url_attachment']
        ];

        if (!empty($memberIds)) {
            $data['idMembers'] = implode(',', $memberIds);
        }

        $result = $this->trelloApi->createCard($data);

        if (isset($result->url)) {
            echo 'Card "' . $data['name'] . '" added.';
        } else {
            $errorMessage = 'Error adding card. Please ensure all input data is correct.';
        
            // Add more specific error information if available
            if (isset($result->message)) {
                $errorMessage .= ' Trello API error: ' . $result->message;
            } else {
                // Log the entire $result object for further investigation
                $debugMessage = 'Unexpected error response: ' . print_r($result, true);
                if (getenv('trello_debug')) {
                    $timestampFormat = getenv('trello_date_format') . ' H:i:s' ?: 'Y-m-d H:i:s';
                    $timestamp = date($timestampFormat);
                    $this->trelloApi->logError('');
                    $this->trelloApi->logError(str_repeat('#', 48));
                    $this->trelloApi->logError('## ' . $timestamp . ' Debugging information  ##');
                    $this->trelloApi->logError(str_repeat('#', 48));
                    $this->trelloApi->logError('');
                    $this->trelloApi->logError('Data: ' . print_r($data, true));
                    $this->trelloApi->logError('Result: ' . print_r($result, true));
                    $this->trelloApi->logError($debugMessage);
                }
            }
        
            echo $errorMessage;
            $this->trelloApi->logError($errorMessage);
        }
    }
}

// Extracting command-line arguments and trimming them
$data = array_map('trim', explode(";", $argv[1]));

// The first three arguments are taken as Trello Key, Token and BoardID respectively
list($trelloKey, $trelloToken, $trelloBoardId) = array_map('stripslashes', array_slice($data, 0, 3));

// Assign the rest of the data to the fields according to the trelloFieldOrder
$trelloFieldOrder = getenv('trello_field_order') ?: 'name,desc,input_labels,due,list_name,position,url_attachment,assigned_member';
$trelloFieldOrder = array_map('trim', explode(",", $trelloFieldOrder));
$trelloFields = array_map('stripslashes', array_slice($data, 3));

// Create a map of all possible fields
$fieldMap = [
    'name' => '',
    'desc' => '',
    'input_labels' => '',
    'due' => '',
    'list_name' => '',
    'position' => '',
    'url_attachment' => '',
    'assigned_member' => '',
];

// Assign values to the fields according to the trelloFieldOrder
foreach($trelloFieldOrder as $index => $fieldName) {
    if(isset($fieldMap[$fieldName])) {
        $fieldMap[$fieldName] = $trelloFields[$index] ?? '';
    }
}

// Retrieve environment variables
$envLabels = getenv('trello_labels');
$envDue = getenv('trello_due');
$envListName = getenv('trello_list_name');
$envPosition = getenv('trello_position');
$envDate = getenv('trello_date_format');
$envMember = getenv('trello_member');

// Assign optional settings based on environment variables or default values
$inputLabels = $fieldMap['input_labels'] ?: $envLabels;
$due = $fieldMap['due'] ?: $envDue;
$listName = $fieldMap['list_name'] ?: $envListName;
$position = $fieldMap['position'] ?: $envPosition;
$assignedMembers = $fieldMap['assigned_member'] ?: $envMember;

// Convert the date to the ISO 8601 format
if (!empty($due)) {
    $relativeDate = strtotime($due);
    if ($relativeDate !== false) {
        $due = date('Y-m-d', $relativeDate);
    } else {
        $dueDate = DateTime::createFromFormat($envDate, $due);
        if ($dueDate) {
            $due = $dueDate->format('Y-m-d');
        }
    }
}

// Assigning values to the fields of the card
$cardData = [
    'name' => $fieldMap['name'],
    'desc' => $fieldMap['desc'],
    'input_labels' => $inputLabels,
    'due' => $due,
    'list_name' => $listName,
    'position' => $position,
    'url_attachment' => $fieldMap['url_attachment'],
    'assigned_member' => $assignedMembers
];

// Creating the Trello card using the TrelloCardCreator class
$trelloCardCreator = new TrelloCardCreator($trelloKey, $trelloToken, $trelloBoardId, $listName, $trelloFieldOrder);
$trelloCardCreator->createTrelloCard($cardData);

?>