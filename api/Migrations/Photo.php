<?php

include_once('Arrayable.php');

class Photo extends Arrayable {
	public string $Link;
	public int $UserId;

	public function __construct($array) {
		parent::__construct($array);
	}

	public function create($db) {
		$res = $db->query("INSERT INTO Photo (UserId, Link) VALUES ({$this->UserId}, '{$this->Link}')");
	}

	public static function get_all_by_user($db, $userId) {
		$res = $db->query("SELECT Link FROM Photo WHERE UserId={$userId}");
		return json_encode($res->fetch_all());
	}

	public static function delete($db, int $id, int $userId) {
		$db->query("DELETE FROM Photo WHERE Id={$id} AND UserId={$userId}");
	}

	public static function Up($mysqli) {
		if ($mysqli->query("SHOW TABLES LIKE 'Photo'")->fetch_assoc())
			return;

		$res = $mysqli->query("CREATE TABLE Photo (" .
			"Id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY," .
			"UserId INT UNSIGNED NOT NULL," .
			"Link TEXT(3000) NOT NULL)");

		if (!$res)
			db_migration_error('Can not create Photo table');
	}

	public static function Down($mysqli) {
		$res = $mysqli->query("DROP TABLE Photo");
		if (!$res)
			db_migration_error('Can not drop Photo table');
	}

}

?>
