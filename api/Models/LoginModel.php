<?php

include_once('Arrayable.php');

class LoginModel extends Arrayable {
	public string $Username;
	public string $Password;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
