<?php

/* ----------------------------------------------------------------------------------------------
Script: 	  Trello for Alfred
Author: 	  Tom Longo
Contributors:     Miko Magni, cokeby190
Usage:		  trello <Card name>;<Card description>;<Label>;<Due Date>;<List Name>
Desc:		  Adds card to trello
Updated:	  20/11/14
------------------------------------------------------------------------------------------------- */

// API KEY: 1433c6977ccb78cd82e29a5455a24815
// https://trello.com/1/connect?key=[API_KEY]&name=[APP_NAME]&response_type=token&scope=read,write&expiration=never
// https://trello.com/1/connect?key=1433c6977ccb78cd82e29a5455a24815&name=Trello%20for%20Alfred&response_type=token&scope=read,write&expiration=never


$trello_key          = '1433c6977ccb78cd82e29a5455a24815';
$trello_api_endpoint = 'https://api.trello.com/1';
$trello_list_id      = false;
$data				 = explode( ";", $argv[1] );
$trello_member_token = $data[0];
$trello_board_id     = $data[1];
$name 				 = (isset($data[2])) ? stripslashes(trim($data[2])) : 'Untitled card';
$desc 				 = (isset($data[3])) ? stripslashes(trim($data[3])) : '';
$labels				 = (isset($data[4])) ? stripslashes(trim($data[4])) : '';
$due 				 = (isset($data[5])) ? stripslashes(trim($data[5])) : '';
$list_name		         = (isset($data[6])) ? stripslashes(trim($data[6])) : '';
$position			 = (isset($data[7])) ? stripslashes(trim($data[7])) : 'bottom';
$url				 = "{$trello_api_endpoint}/boards/{$trello_board_id}?lists=open&list_fields=name&fields=name,desc&key={$trello_key}&token={$trello_member_token}";

$ch = curl_init();

// Set query data here with the URL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, '25');
$content = trim(curl_exec($ch));
curl_close($ch);
$board = json_decode($content);
$lists = $board->lists;
$trello_list_id = $lists[0]->id;

if(@fsockopen('www.google.com',80,$errorNum,$errorMessage)) {
	foreach($lists as $list) {
		if($list->name == $list_name) {
			$trello_list_id = $list->id;
		}
	}

	if($trello_list_id) {

		$ch = curl_init("$trello_api_endpoint/cards");
		curl_setopt_array($ch, array(
			CURLOPT_SSL_VERIFYPEER => false, // Probably won't work otherwise
			CURLOPT_RETURNTRANSFER => true, // So we can get the URL of the newly-created card
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS => http_build_query(array( // if you use an array without being wrapped in http_build_query, the Trello API server won't recognize your POST variables
				'key'    => $trello_key,
				'token'  => $trello_member_token,
				'name'   => $name,
				'desc'   => $desc,
				'labels' => $labels,
				'due'	 => $due,
				'idList' => $trello_list_id,
				'pos'	 => $position
			)),
		));

		$result = curl_exec($ch);
		$trello_card = json_decode($result);
		echo ($trello_card->url) ? '"'.$name.'" added.' : 'ERROR ADDING CARD. your message was: '.$name.$desc.$labels;

	} else {
		echo 'List not found';
	}

} else {
	echo 'ERROR: NO NETWORK CONNECTION, your message was: '.$name.$desc.$labels.' ERROR: '.$errorNum,$errorMessage;
}
