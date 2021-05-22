<?php

function get_error_json(string $name, int $code, string $message) {
	return json_encode(array(
		'error' => $name,
		'code' => $code,
		'message' => $message
	));
}

function not_found_error() {
	header('HTTP/1.0 404 Bad Request');
	echo get_error_json('Bad Request', 404, 'Page not found');
	die();
}

function db_migration_error($message) {
	header('HTTP/1.0 500 Internal server error');
	echo get_error_json('Db error', 500, $message);
	die();
}

function db_connection_error() {
	header('HTTP/1.0 500 Internal server error');
	echo get_error_json('Db error', 500, 'Unable to establish a connection with DB. Code: ' . mysqli_connect_errno() . ', message: ' . mysqli_connect_error());
	die();
}

function success() {
	header('HTTP/1.0 200 OK');
}

?>
