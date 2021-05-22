<?php

include_once('Arrayable.php');

class UpdateUserRoleModel extends Arrayable {
	public int $RoleId;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
