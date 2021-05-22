<?php

include_once('Arrayable.php');

class Role extends Arrayable {
	public string $Name;

	public function __construct($array) {
		parent::__construct($array);
	}

	public function create($db) {
		$res = $db->query("INSERT INTO Role (Name) VALUES ('{$this->Name}')");
	}

	public static function get_all($db) {
		$res = $db->query("SELECT Id, Name FROM Role");
		return json_encode($res->fetch_all());
	}

	public static function get($db, int $id) {
		$res = $db->query("SELECT Name FROM Role WHERE Id={$id}");
		return json_encode($res->fetch_assoc());
	}

	public function update($db, int $id) {
		$res = $db->query("UPDATE Role SET Name='{$this->Name}' WHERE Id={$id} AND {$id}<>1 AND {$id}<>2 AND {$id}<>3");
	}

	public static function delete($db, int $id) {
		$db->query("DELETE FROM Role WHERE Id={$id} AND {$id}<>1 AND {$id}<>2 AND {$id}<>3");
	}

	public static function Up($mysqli) {
		if ($mysqli->query("SHOW TABLES LIKE 'Role'")->fetch_assoc())
			return;

		$res = $mysqli->query("CREATE TABLE Role (" .
			"id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY," .
			"name VARCHAR(20) NOT NULL UNIQUE)");

		if (!$res)
			db_migration_error('Can not create Role table');

		$res = $mysqli->query("INSERT INTO Role (name) VALUES ('Administrator'), ('Moderator'), ('User')");
		if (!$res)
			db_migration_error('Can not insert into Role default roles');
	}

	public static function Down($mysqli) {
		$res = $mysqli->query("DROP TABLE Role");
		if (!$res)
			db_migration_error('Can not drop Role table');
	}

}

?>
