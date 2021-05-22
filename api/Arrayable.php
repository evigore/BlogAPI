<?php

class Arrayable {
	public function __construct($array) {
		foreach ($array as $key => $value) {
			if (property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}
	}
}

?>
