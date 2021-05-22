<?php

include_once('codes.php');
include_once('Migrations/User.php');
include_once('Migrations/Role.php');
include_once('Migrations/Post.php');
include_once('Migrations/City.php');
include_once('Migrations/Photo.php');
include_once('Migrations/Message.php');

$mysqli;

class Db {
	public function __construct() {
		global $mysqli;
		if (!$mysqli)
			$mysqli = Db::get_connection();
	}

	public function __destruct() {
		global $mysqli;
		$mysqli->close();
	}

	public static function get_connection() {
		$mysqli = @new mysqli("127.0.0.1", "admin", "Admin_112", "test_db");
		if ($mysqli->connect_errno)
			db_connection_error();

		return $mysqli;
	}

	public function get_mysqli() {
		global $mysqli;
		return $mysqli;
	}

	public function query($query) {
		global $mysqli;
		return $mysqli->query($query);
	}

	public static function UpMigrations() {
		global $mysqli;
		if (!$mysqli)
			$mysqli = Db::get_connection();

		User::Up($mysqli);
		Role::Up($mysqli);
		Post::Up($mysqli);
		City::Up($mysqli);
		Photo::Up($mysqli);
		Message::Up($mysqli);
	}

	public static function DownMigrations() {
		global $mysqli;
		if (!$mysqli)
			$mysqli = Db::get_connection();

		User::Down($mysqli);
		Role::Down($mysqli);
		Post::Down($mysqli);
		City::Down($mysqli);
		Photo::Down($mysqli);
		Message::Down($mysqli);
	}
}

?>
