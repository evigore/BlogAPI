<?php

include_once('Arrayable.php');
include_once('Auth.php');

function generate_token() {
	return bin2hex(openssl_random_pseudo_bytes(8, $cstrong));
}

class User extends Arrayable {
	public string $Status = 'Offline';

	public string $Name;
	public string $Surname;
	public string $Username;
	public string $Password;
	public ?string $Birthday = NULL;
	public ?string $Avatar = null;

	public int $RoleId = 3; // User
	public ?int $CityId = null;

	public function __construct(array $array) {
		parent::__construct($array);

		if (isset($this->Birthday))
			$this->Birthday = date('Y-m-d', strtotime($this->Birthday));
	}

	public function create($db) {
		$res = $db->query("INSERT INTO User" .
			"(CityId, RoleId, Name, Surname, Username, Password, Birthday, Status, Avatar) VALUES (" .
			(is_null($this->CityId) ? 'NULL' : $this->CityId) .
			", {$this->RoleId}, '{$this->Name}', '{$this->Surname}', '{$this->Username}', '{$this->Password}', " .
			(is_null($this->Birthday) ? 'NULL' : "'" . $this->Birthday . "'") .
			", '{$this->Status}', " .
			(is_null($this->Avatar) ? "NULL" : "'" . $this->Avatar . "'") .
			")");
	}

	public static function get_all($db) {
		# Не выводит людей без города
		$res = $db->query("SELECT t1.Id, t2.Name, t3.Name, t1.Name, t1.Surname, t1.Username, t1.Birthday, t1.Status, t1.Avatar FROM User t1 INNER JOIN City t2 ON t1.CityId = t2.Id INNER JOIN Role t3 ON t1.RoleId = t3.Id");
		return json_encode($res->fetch_all());
	}

	public static function get_user($db, int $id) {
		# Не выводит людей без города
		$res = $db->query("SELECT t1.Id, t2.Name, t1.RoleId, t1.Name, t1.Surname, t1.Username, t1.Birthday, t1.Status, t1.Avatar FROM User t1 INNER JOIN City t2 ON t1.CityId = t2.Id WHERE t1.id={$id}");
		return json_encode($res->fetch_assoc());
	}

	public static function get_users_by_city($db, int $cityId) {
		$res = $db->query("SELECT t1.Id, t2.Name, t1.RoleId, t1.Name, t1.Surname, t1.Username, t1.Status FROM User t1 INNER JOIN City t2 ON t1.CityId = t2.Id WHERE t2.Id={$cityId}");
		return json_encode($res->fetch_all());
	}

	public static function delete_user($db, int $id) {
		$db->query("DELETE FROM User WHERE id={$id}");
	}

	public function update($db, int $id) {
		$data = User::get_user($db, $id);
		if (!$data)
			not_found_error();

		if (empty($this->Name))
			$this->Name = $data->Name;

		if (empty($this->Surname))
			$this->Surname = $data->Surname;

		if (empty($this->Username))
			$this->Username = $data->Username;

		if (empty($this->Password))
			$this->Password = $data->Password;

		if (empty($this->Birthday))
			$this->Birthday = $data->Birthday;

		if (empty($this->Avatar))
			$this->Avatar = $data->Avatar;

		$db->query("UPDATE User SET Name={$this->Name}, Surname={$this->Surname}, Username={$this->Username}, Password={$this->Password}, Birthday={$this->Birthday}, Avatar={$this->Avatar} WHERE id={$id}");
	}

	public function update_city($db, int $id) {
		// check cityId
		$db->query("UPDATE User SET CityId={$this->CityId} WHERE id={$id}");
	}

	public function update_status($db, int $id) {
		$user = Auth::get_user_info($db);
		if (is_null($user))
			return;

		$is_admin = Auth::role_is_admin($db, $user['RoleId']);
		if ($is_admin)
			$db->query("UPDATE User SET Status='{$this->Status}'");
		else
			$db->query("UPDATE User SET Status='{$this->Status}' WHERE id={$user['Id']}");
	}

	public function update_role($db, int $id) {
		// check roleId
		$db->query("UPDATE User SET RoleId={$this->RoleId} WHERE id={$id}");
	}

	public function login($db) {
		$res = $db->query("SELECT * FROM User WHERE Username='{$this->Username}' AND Password='{$this->Password}'");

		while ($row = $res->fetch_assoc())
			$id = $row['Id'];

		$token = generate_token();
		$db->query("UPDATE User SET Token='{$token}' WHERE Id={$id}");
		return $token;
	}

	public static function logout($db) {
		global $token;
		$res = $db->query("SELECT * FROM User WHERE Token='{$token}'");
		if ($res->num_rows == 0)
			return;

		while ($row = $res->fetch_assoc())
			$id = $row['Id'];

		$db->query("UPDATE User SET Token=NULL WHERE Id={$id}");
	}

	public static function Up($mysqli) {
		if ($mysqli->query("SHOW TABLES LIKE 'User'")->fetch_assoc())
			return;

		$res = $mysqli->query("CREATE TABLE User (" .
			"Id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY," .
			"CityId INT UNSIGNED NULL," .
			"RoleId INT UNSIGNED NOT NULL," .
			"Name VARCHAR(40) NULL," .
			"Surname VARCHAR(40) NULL," .
			"Username VARCHAR(40) NOT NULL UNIQUE," .
			"Password VARCHAR(40) NOT NULL," .
			"Birthday DATETIME NULL," .
			"Token VARCHAR(40) NULL," .
			"Status ENUM('Online', 'Offline', 'Do not disturb', 'In panic', 'Want to die') NOT NULL," .
			"Avatar VARCH)");

		if (!$res)
			db_migration_error('Can not create User table');

		$res = $db->query("INSERT INTO User (RoleId, Username, Password, Status) VALUES (1, 'admin', 'admin', 'Online')");

	}

	public static function Down($mysqli) {
		$res = $mysqli->query("DROP TABLE User");
		if (!$res)
			db_migration_error('Can not drop User table');
	}
}

?>
