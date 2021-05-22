<?php

include_once('Arrayable.php');

class UpdateCityModel extends Arrayable {
	public string $Name;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
