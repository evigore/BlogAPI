<?php

include_once('Db.php');
include_once('codes.php');
include_once('Models/LoginModel.php');

function route($method, $url_data, $form_data) {
	echo 'Hello from routes/users.php<br>';
	$db = new Db();
	Db::UpMigrations();
	#Db::DownMigrations();

	switch ($method) {
	case 'POST':
		if (count($url_data) == 0) { # /login
			$user = new User((array) new LoginModel($form_data));
			$token = $user->login($db);
			header("Authorization: Bearer {$token}");
			echo $token;
		} else
			not_found_error();

		break;
	}
}
?>
