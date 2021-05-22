<?php

include_once('Arrayable.php');

class City extends Arrayable {
	public string $Name;

	public function __construct($array) {
		parent::__construct($array);
	}

	public function create($db) {
		$res = $db->query("INSERT INTO City (Name) VALUES ('{$this->Name}')");
	}

	public static function get_all($db) {
		$res = $db->query("SELECT Id, Name FROM City");
		return json_encode($res->fetch_all());
	}

	public static function get($db, int $id) {
		$res = $db->query("SELECT Name FROM City WHERE id={$id}");
		return json_encode($res->fetch_assoc());
	}

	public function update($db, int $id) {
		$res = $db->query("UPDATE City SET Name='{$this->Name}' WHERE id={$id}");
		#$mysqli = $db->get_mysqli();
		#echo json_encode($res) . ' ' . $mysqli->error;
	}

	public static function delete($db, int $id) {
		$db->query("DELETE FROM City WHERE id={$id}");
	}

	public static function Up($mysqli) {
		if ($mysqli->query("SHOW TABLES LIKE 'City'")->fetch_assoc())
			return;

		$res = $mysqli->query("CREATE TABLE City (" .
			"id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY," .
			"name VARCHAR(40) NOT NULL)");

		if (!$res)
			db_migration_error('Can not create City table');
	}

	public static function Down($mysqli) {
		$res = $mysqli->query("DROP TABLE City");
		if (!$res)
			db_migration_error('Can not drop City table');
	}
}

?>
