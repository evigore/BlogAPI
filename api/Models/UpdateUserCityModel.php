<?php

include_once('Arrayable.php');

class UpdateUserCityModel extends Arrayable {
	public int $CityId;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
