<?php

include_once('Db.php');
include_once('codes.php');
include_once('Models/CreateRoleModel.php');
include_once('Models/UpdateRoleModel.php');

function route($method, $url_data, $form_data) {
	echo 'Hello from routes/roles.php<br>';
	$db = new Db();
	Db::UpMigrations();

	switch ($method) {
	case 'GET':
		if (count($url_data) == 0) # /roles
			echo Role::get_all($db);
		else if (count($url_data) == 1 && is_numeric($url_data[0])) # /roles/{id}
			echo Role::get($db, intval($url_data[0]));
		else
			not_found_error();

		break;
	case 'POST':
		if (count($url_data) == 0) { # /roles
			$role = new Role((array) new CreateRoleModel($form_data));
			$role->create($db);
			success();
		} else
			not_found_error();

		break;
	case 'PATCH':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /roles/{id}
			$role = new Role((array) new UpdateRoleModel($form_data));
			$role->update($db, intval($url_data[0]));
			success();
		} else
			not_found_error();

		break;
	case 'DELETE':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /roles/{id}
			Role::delete($db, intval($url_data[0]));
			success();
		} else
			not_found_error();

		break;
	}
}

?>
