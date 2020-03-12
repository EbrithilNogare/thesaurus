<?php
class Header{
	private $status;

	function __construct($userInfo){
		$this->status = isset($userInfo["status"]) ? $userInfo["status"] : "X";		
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


		echo <<<HTML
		<header>
			<div class="title">Thesaurus UK - {$this->status}</div>
			<div class="menu">
				{$menuButtonsToRender}
			</div>
		</header>
HTML;
	}
}