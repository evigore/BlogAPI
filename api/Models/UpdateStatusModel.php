<?php

include_once('Arrayable.php');

class UpdateStatusModel extends Arrayable {
	public string $Status;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
