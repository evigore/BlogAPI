<?php

include_once('Db.php');
include_once('codes.php');

function route($method, $url_data, $form_data) {
	echo 'Hello from routes/logout.php<br>';
	$db = new Db();
	Db::UpMigrations();
	#Db::DownMigrations();

	switch ($method) {
	case 'POST':
		if (count($url_data) == 0) { # /logout
			User::logout($db);
			success();
		} else
			not_found_error();

		break;
	}
}
?>
