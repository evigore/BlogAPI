<?php

class Auth {
	public static function get_user_info($db) {
		global $token;
		if (is_null($token))
			return null;

		$res = $db->query("SELECT * FROM User WHERE Token='{$token}'");
		return $res->fetch_assoc();
	}

	public static function role_is_admin($db, $roleId) {
		$res = $db->query("SELECT * FROM Role WHERE Name='Administrator' AND Id={$roleId}");
		return !!$res->fetch_assoc();
	}
}

?>
