<?php
class Header{
	private $status;
	private $username;

	function __construct($userInfo){
		$this->status = isset($userInfo["status"]) ? $userInfo["status"] : "X";	
		$this->username = isset($userInfo["username"]) ? $userInfo["username"] : null;
	}

	function render(){
		$menuButtons = [
			'admin'=>'<a href="?action=admin">admin</a>',
			'home'=>'<a href="?action=home">home</a>',
			'logout'=>'<a href="?action=logout">logout</a>',
		];
		$menuButtonsToRender = "";
		switch($this->status){
			case "A":
				$menuButtonsToRender.=$menuButtons['home']."\n";
				$menuButtonsToRender.=$menuButtons['admin']."\n";
			case "U":
				$menuButtonsToRender.=$menuButtons['logout']."\n";
		}
		$logInfo = "";
		if($this->username != null)
			$logInfo = "logged as:<br>{$this->username}";


		echo <<<HTML
		<header>
			<div class="title">Thesaurus UK</div>
			<div class="userInfo">$logInfo</div>
			<div class="menu">
				{$menuButtonsToRender}
			</div>
		</header>
HTML;
	}
}