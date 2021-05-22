<?php

include_once('Arrayable.php');

class Message extends Arrayable {
	public string $Text;
	public string $Date;

	public function __construct($array) {
		parent::__construct($array);
	}

	public function create($db, int $senderId, $receiverId) {
		$Date = date('Y-m-d H:i:s');
		$db->query("INSERT INTO Message (SenderId, ReceiverId, Text, Date) VALUES ({$senderId}, {$receiverId}, '{$this->Text}', '{$Date}')");

		$res = $db->query("SELECT Id FROM Message ORDER BY Id DESC LIMIT 1");
		return json_encode($res->fetch_assoc());
	}

	public static function get($db, int $id) {
		$res = $db->query("SELECT * FROM Message WHERE Id={$id}");
		return json_encode($res->fetch_assoc());
	}

	public static function get_all_by_user($db, $userId) {
		$res = $db->query("SELECT * FROM Message WHERE SenderId={$userId} OR ReceiverId={$userId}");
		return json_encode($res->fetch_all());
	}

	public static function delete($db, int $id, int $userId) {
		$db->query("DELETE FROM Message WHERE Id={$id} AND (SenderId={$userId} OR ReceiverId={$userId})");
	}

	public static function Up($mysqli) {
		if ($mysqli->query("SHOW TABLES LIKE 'Message'")->fetch_assoc())
			return;

		$res = $mysqli->query("CREATE TABLE Message (" .
			"Id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY," .
			"SenderId INT UNSIGNED NOT NULL," .
			"ReceiverId INT UNSIGNED NOT NULL," .
			"Text TEXT(65535) NOT NULL," .
			"Date DATETIME NULL)");

		if (!$res)
			db_migration_error('Can not create Message table');
	}

	public static function Down($mysqli) {
		$res = $mysqli->query("DROP TABLE Message");
		if (!$res)
			db_migration_error('Can not drop Message table');
	}
}

?>
