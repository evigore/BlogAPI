<?php

include_once('Db.php');
include_once('codes.php');
include_once('Models/CreateUserModel.php');
include_once('Models/UpdateUserModel.php');
include_once('Models/UpdateUserCityModel.php');
include_once('Models/UpdateStatusModel.php');
include_once('Models/UpdateUserRoleModel.php');

function route($method, $url_data, $form_data) {
	echo 'Hello from routes/users.php<br>';
	$db = new Db();
	Db::UpMigrations();
	#Db::DownMigrations();

	switch ($method) {
	case 'GET':
		if (count($url_data) == 0) # /users
			echo User::get_all($db);
		else if (count($url_data) == 1 && is_numeric($url_data[0])) # /users/{id}
			echo User::get_user($db, intval($url_data[0]));
		else if (count($url_data) == 2 && is_numeric($url_data[0]) && $url_data[1] == 'posts') { # /users/{id}/posts
			echo Post::get_all_by_user($db, intval($url_data[0]));
		} else if (count($url_data) == 3 && is_numeric($url_data[0]) && $url_data[1] == 'messages' && true) { # users/{id}/messages/?offset={}&limit={}
			echo json_encode(array('method' => 'GET', 'data' => '4 messages of user with id=' . $url_data[0] . ' starts with 1 message'));
		} else
			not_found_error();

		break;
	case 'POST':
		if (count($url_data) == 0) { # /users
			$user = new User((array) new CreateUserModel($form_data));
			$user->create($db);
			success();
		} else if (count($url_data) == 2 && is_numeric($url_data[0])) {
			if ($url_data[1] == 'avatar') # /users/{id}/avatar
				echo json_encode(array('method' => 'POST', 'data' => 'Upload avatar for user with id=' . $url_data[0]));
			else if ($url_data[1] == 'messages') # /users/{id}/messages
				echo json_encode(array('method' => 'POST', 'data' => 'Send message to user with id=' . $url_data[0]));
			else
				not_found_error();
		} else
			not_found_error();

		break;
	case 'PATCH':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /users/{id}
			$user = new User((array) new UpdateUserModel($form_data));
			$user->update($db, intval($url_data[0]));
			echo User::get_user($db, intval($url_data[0]));
		} else if (count($url_data) == 2 && is_numeric($url_data[0])) {
			if ($url_data[1] == 'city') { # /users/{id}/city
				$user = new User((array) new UpdateUserCityModel($form_data));
				$user->update_city($db, intval($url_data[0]), );
				success();
			} else if ($url_data[1] == 'status') { # /users/{id}/status
				$user = new User((array) new UpdateStatusModel($form_data));
				$user->update_status($db, intval($url_data[0]));
				success();
			} else if ($url_data[1] == 'role') { # /users/{id}/role
				$user = new User((array) new UpdateUserRoleModel($form_data));
				$user->update_role($db, intval($url_data[0]));
				success();
			} else
				not_found_error();
		} else
			not_found_error();

		break;
	case 'DELETE':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /users/{id}
			User::delete_user($db, intval($url_data[0]));
			success();
		} else
			not_found_error();

		break;
	}
}

?>
