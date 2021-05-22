<?php

include_once('Arrayable.php');

class CreatePhotoModel extends Arrayable {
	public string $Link;
	public int $UserId;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
