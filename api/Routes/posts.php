<?php

include_once('Db.php');
include_once('codes.php');
include_once('Models/CreatePostModel.php');
include_once('Models/UpdatePostModel.php');

function route($method, $url_data, $form_data) {
	echo 'Hello from routes/posts.php<br>';
	$db = new Db();
	Db::UpMigrations();

	$userId = 1;

	switch ($method) {
	case 'GET':
		if (count($url_data) == 0) # /posts
			echo Post::get_all($db);
		else if (count($url_data) == 1 && is_numeric($url_data[0])) # /posts/{id}
			echo Post::get($db, intval($url_data[0]));
		else
			not_found_error();

		break;
	case 'POST':
		if (count($url_data) == 0) { # /posts
			$post = new Post((array) new CreatePostModel($form_data));
			$post->create($db);
			success();
		} else
			not_found_error();

		break;
	case 'PATCH':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /posts/{id}
			$post = new Post((array) new UpdatePostModel($form_data));
			$post->update($db, intval($url_data[0]));
			success();
		} else
			not_found_error();

		break;
	case 'DELETE':
		if (count($url_data) == 1 && is_numeric($url_data[0])) { # /posts/{id}
			Post::delete($db, intval($url_data[0]), $userId);
			success();
		} else
			not_found_error();

		break;
	}
}

?>
