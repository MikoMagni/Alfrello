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
 * Trello API Client for interacting with the Trello API.
 */
class TrelloApi {
    // Declaring public properties for API endpoint, key, and token
    public $apiEndpoint;
    public $key;
    public $token;
    
    /**
     * Constructs a new instance of the TrelloApi class.
     *
     * @param string $key The Trello API key.
     * @param string $token The Trello API token.
     * @param string $apiEndpoint The Trello API endpoint URL.
     */
    public function __construct($key, $token, $apiEndpoint = 'https://api.trello.com/1') {
        // Assigning values to the properties
        $this->key = $key;
        $this->token = $token;
        $this->apiEndpoint = $apiEndpoint;
    }

    /**
    * Logs an error message if the Trello debug environment variable is true.
    *
    * @param string $message The error message.
    * @return void
    */
    public function logError($message) {
        // If trello_debug environment variable is set, write message to log.txt
        if (getenv('trello_debug')) {
            file_put_contents('log.txt', $message . PHP_EOL, FILE_APPEND);
        }
    }   

    /**
    * Sends an HTTP request to the Trello API.
    *
    * @param string $url The URL for the HTTP request.
    * @param string $method The HTTP method (GET, POST, etc.).
    * @param mixed $body The request body.
    * @return mixed The response from the API.
    */
    public function httpRequest($url, $method = 'GET', $body = []) {
        // Setting up cURL options
        $options = [
            CURLOPT_SSL_VERIFYPEER => true,  // Verifies SSL certificate
            CURLOPT_RETURNTRANSFER => true,  // Returns output as a string instead of outputting it
            CURLOPT_CUSTOMREQUEST => $method, // Sets HTTP request type
        ];

        // If body is not empty, add it to cURL options
        if (!empty($body)) {
            $options[CURLOPT_POSTFIELDS] = http_build_query($body);
        }

        // Initialize cURL session and set options
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        // Execute cURL session
        $result = curl_exec($ch);

        // If an error occurred, log it
        if (curl_errno($ch)) {
            $timestampFormat = getenv('trello_date_format') . ' H:i:s' ?: 'Y-m-d H:i:s';
            $errorMessage = '[' . date($timestampFormat) . '] Curl error: ' . curl_error($ch);
            $this->logError($errorMessage);
        }                   
        
        // Close cURL session and return the result
        curl_close($ch);
        return $result;
    }

    /**
     * Retrieves the lists for a given Trello board.
     *
     * @param string $boardId The ID of the Trello board.
     * @return mixed The lists for the board.
     */
    public function getLists($boardId) {
        // Create the URL and make a GET request to it, then return the decoded JSON response
        $url = "{$this->apiEndpoint}/boards/{$boardId}/lists?key={$this->key}&token={$this->token}";
        return json_decode($this->httpRequest($url));
    }

    /**
     * Retrieves the labels for a given Trello board.
     *
     * @param string $boardId The ID of the Trello board.
     * @return mixed The labels for the board.
     */
    public function getLabels($boardId) {
        // Create the URL and make a GET request to it, then return the decoded JSON response
        $url = "{$this->apiEndpoint}/boards/{$boardId}/labels?key={$this->key}&token={$this->token}";
        return json_decode($this->httpRequest($url));
    }

    /**
     * Retrieves the members for a given Trello board.
     *
     * @param string $boardId The ID of the Trello board.
     * @return mixed The members for the board.
     */
    public function getMembers($boardId) {
        // Create the URL and make a GET request to it, then return the decoded JSON response
        $url = "{$this->apiEndpoint}/boards/{$boardId}/members?key={$this->key}&token={$this->token}";
        return json_decode($this->httpRequest($url));
    }

    /**
     * Creates a new card on the Trello board.
     *
     * @param mixed $data The card data.
     * @return mixed The response from the API.
     */
    public function createCard($data) {
        // Create the URL and make a POST request to it, then return the decoded JSON response
        $url = "{$this->apiEndpoint}/cards";
        $options = [
            CURLOPT_SSL_VERIFYPEER => true,  // Verifies SSL certificate
            CURLOPT_RETURNTRANSFER => true,  // Returns output as a string instead of outputting it
            CURLOPT_POST => true,  // Sets HTTP request type to POST
            CURLOPT_POSTFIELDS => http_build_query($data),  // Adds POST data
        ];
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result);
    }
}

// Include the TrelloCardCreator file to the current script
include_once 'TrelloCardCreator.php';

?>