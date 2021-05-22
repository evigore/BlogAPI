<?php

include_once('Arrayable.php');

class CreateAvatarModel extends Arrayable {
	public File $File;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
