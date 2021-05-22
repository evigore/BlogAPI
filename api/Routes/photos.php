<?php

include_once('Db.php');
include_once('codes.php');
include_once('Models/CreatePhotoModel.php');

function route($method, $url_data, $form_data) {
	echo 'Hello from routes/photos.php<br>';
	$db = new Db();
	Db::UpMigrations();

	$userId = 1;

	switch ($method) {
	case 'GET':
		if (count($url_data) == 0) # /photos
			echo Photo::get_all_by_user($db, $userId);
		else if (count($url_data) == 1 && is_numeric($url_data[0])) # /photos/{UserId}
			echo Photo::get_all_by_user($db, intval($url_data[0]));
		else
			not_found_error();

		break;
	case 'POST':
		if (count($url_data) == 0) { # /photos
			#$link = '../UserPhotos/' . basename($form_data['File']['name']);
			$link = $form_data['File']['tmp_name'];
			#move_uploaded_file($_FILES['File']['tmp_name'], $link);
			
			$photo = new Photo((array) new CreatePhotoModel(['Link' => $link, 'UserId' => $userId]));
			$photo->create($db);
			success();
		} else
			not_found_error();

		break;
	case 'DELETE':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /photos/{id}
			Photo::delete($db, intval($url_data[0]), $userId);
			success();
		} else
			not_found_error();

		break;
	}
}

?>
