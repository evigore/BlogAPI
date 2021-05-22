<?php

include_once('Db.php');
include_once('codes.php');
include_once('Models/CreateMessageModel.php');

function route($method, $url_data, $form_data) {
	echo 'Hello from routes/messages.php<br>';
	$db = new Db();
	Db::UpMigrations();
	#Db::DownMigrations();
	
	$userId = 1;

	switch ($method) {
	case 'GET':
		if (count($url_data) == 0) # /messages
			echo Message::get_all_by_user($db, $userId);
		else if (count($url_data) == 1 && is_numeric($url_data[0])) # /messages/{id}
			echo Message::get($db, intval($url_data[0]));
		else
			not_found_error();

		break;
	case 'POST':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /messages/{UserId}
			$messaage = new Message((array) new CreateMessageModel($form_data));
			echo $messaage->create($db, $userId, intval($url_data[0]));
		} else
			not_found_error();

		break;
	case 'DELETE':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /messages/{id}
			Message::delete($db, intval($url_data[0]), $userId);
			success();
		} else
			not_found_error();

		break;
	}
}

?>
