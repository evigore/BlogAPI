<?php

include_once('Arrayable.php');

class CreatePostModel extends Arrayable {
	public int $UserId;
	public string $Text;

	public function __construct($array) {
		parent::__construct($array);
	}
}

?>
