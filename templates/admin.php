<?php

include_once("common/connectToDB.php");


class Admin{
	private $status;

	function __construct($userInfo){
		$this->status = isset($userInfo["status"]) ? $userInfo["status"] : "X";
	}

	function render(){
		if($this->status!="A"){
			echo <<<HTML
				<div class="block">This section is only for admins</div>
HTML;
			return;
		}

		echo <<<HTML
			<section class="users">a</section>
HTML;
	}
}