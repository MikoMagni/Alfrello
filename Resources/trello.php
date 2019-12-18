<?php

/*
  _______       _ _        __          __        _     __ _               
 |__   __|     | | |       \ \        / /       | |   / _| |              
    | |_ __ ___| | | ___    \ \  /\  / /__  _ __| | _| |_| | _____      __
    | | '__/ _ \ | |/ _ \    \ \/  \/ / _ \| '__| |/ /  _| |/ _ \ \ /\ / /
    | | | |  __/ | | (_) |    \  /\  / (_) | |  |   <| | | | (_) \ V  V / 
    |_|_|  \___|_|_|\___/      \/  \/ \___/|_|  |_|\_\_| |_|\___/ \_/\_/  
                                                                          
                                                                          
Script: 	   		Trello Workflow for Alfred
Version:        		1.6.2
Author: 	    		@mikomagni @tomlongo 
Contributors:   		@cokeby190 @cheryl @deanishe @gamell @geojunkie
Desc:		    		Adds card to Trello
Updated:	    		18/12/19
Source: 			https://github.com/MikoMagni/Trello-Workflow-for-Alfred

*/

$trello_api_endpoint 		= 'https://api.trello.com/1';
$trello_list_id 		= false;
$data 				= explode(";", $argv[1]);
$trello_key 			= $data[0];
$trello_token 			= $data[1];
$trello_board_id 		= $data[2];
$name 				= (isset($data[3])) ? stripslashes(trim($data[3])) : '';
$desc 				= (isset($data[4])) ? stripslashes(trim($data[4])) : '';
$labels 			= (isset($data[5])) ? stripslashes(trim($data[5])) : (getenv('trello.label') ?: '');
$due 				= (isset($data[6])) ? stripslashes(trim($data[6])) : (getenv('trello.due') ?: '');
$list_name 			= (isset($data[7])) ? stripslashes(trim($data[7])) : (getenv('trello.list_name') ?: '') ;
$position 			= (isset($data[8])) ? stripslashes(trim($data[8])) : (getenv('trello.position') ?: 'bottom');
$url_attachment		= (isset($data[9])) ? stripslashes(trim($data[9])) : '';
$url 				= "{$trello_api_endpoint}/boards/{$trello_board_id}?lists=open&list_fields=name&fields=name,desc&key={$trello_key}&token={$trello_token}";

$ch 				= curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, '25');
$content 			= trim(curl_exec($ch));
curl_close($ch);
$board 				= json_decode($content);
$lists 				= $board->lists;
$trello_list_id 		= $lists[0]->id;

if (@fsockopen('duckduckgo.com', 80)) {
	foreach($lists as $list) {
		if ($list->name == $list_name) {
			$trello_list_id = $list->id;
		}
	}

	if ($trello_list_id) {
		$ch = curl_init("$trello_api_endpoint/cards");
		curl_setopt_array($ch, array(
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query(array(
				'key' => $trello_key,
				'token' => $trello_token,
				'name' => $name,
				'desc' => $desc,
				'labels' => $labels,
				'due' => $due,
				'idList' => $trello_list_id,
				'pos' => $position,
				'urlSource' => $url_attachment
			)) ,
		));
		$result = curl_exec($ch);
		$trello_card = json_decode($result);
		echo ($trello_card->url) ? '"' . $name . '" added.' : 'Error adding card.';
	}
	else {
		echo 'List not found';
	}
}
else {
	echo 'Your computer seems to be offline, adding cards to Trello won\'t work at the moment';
}
