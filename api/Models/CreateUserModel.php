<?php

include_once('Arrayable.php');

class CreateUserModel extends Arrayable {
	public string $Name;
	public string $Surname;
	public string $Username;
	public string $Password;
	public ?string $Birthday = null;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
