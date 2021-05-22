<?php

include_once('Db.php');
include_once('codes.php');
include_once('Models/CreateCityModel.php');
include_once('Models/UpdateCityModel.php');

function route($method, $url_data, $form_data) {
	echo 'Hello from routes/cities.php<br>';
	$db = new Db();
	Db::UpMigrations();

	switch ($method) {
	case 'GET':
		if (count($url_data) == 0) # /cities
			echo City::get_all($db);
		else if (count($url_data) == 1 && is_numeric($url_data[0])) # /cities/{id}
			echo City::get($db, intval($url_data[0]));
		else if (count($url_data) == 2 && is_numeric($url_data[0]) && $url_data[1] == 'peoples') # /cities/{id}/peoples
			echo User::get_users_by_city($db, intval($url_data[0]));
		else
			not_found_error();

		break;
	case 'POST':
		if (count($url_data) == 0) { # /cities
			$city = new City((array) new CreateCityModel($form_data));
			$city->create($db);
			success();
		} else
			not_found_error();

		break;
	case 'PATCH':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /cities/{id}
			$city = new City((array) new UpdateCityModel($form_data));
			$city->update($db, intval($url_data[0]));
			success();
		} else
			not_found_error();

		break;
	case 'DELETE':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /cities/{id}
			City::delete($db, intval($url_data[0]));
			success();
		} else
			not_found_error();

		break;
	}
}

?>
