<?php

include_once('Arrayable.php');

class Post extends Arrayable {
	public string $Text;
	public string $Date;
	public int $UserId;

	public function __construct($array) {
		parent::__construct($array);
	}

	public function create($db) {
		$Date = date('Y-m-d H:i:s');
		$res = $db->query("INSERT INTO Post (UserId, Text, Date) VALUES ({$this->UserId}, '{$this->Text}', '{$Date}')");
	}

	public static function get($db, $id) {
		$res = $db->query("SELECT * FROM Post WHERE Id={$id}");
		return json_encode($res->fetch_assoc());
	}

	public static function get_all($db) {
		$res = $db->query("SELECT * FROM Post");
		return json_encode($res->fetch_all());
	}

	public static function get_all_by_user($db, $userId) {
		$res = $db->query("SELECT * FROM Post WHERE UserId={$userId}");
		return json_encode($res->fetch_all());
	}

	public function update($db, $id) {
		$db->query("UPDATE Post SET Text='{$this->Text}' WHERE Id={$id}");
	}

	public static function delete($db, int $id, int $userId) {
		$db->query("DELETE FROM Post WHERE Id={$id} AND UserId={$userId}");
	}

	public static function Up($mysqli) {
		if ($mysqli->query("SHOW TABLES LIKE 'Post'")->fetch_assoc())
			return;

		$res = $mysqli->query("CREATE TABLE Post (" .
			"Id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY," .
			"UserId INT UNSIGNED NOT NULL," .
			"Text TEXT(65535) NOT NULL," .
			"Date DATETIME NOT NULL)");

		if (!$res)
			db_migration_error('Can not create Post table');
	}

	public static function Down($mysqli) {
		$res = $mysqli->query("DROP TABLE Post");
		if (!$res)
			db_migration_error('Can not drop Post table');
	}
}

?>
