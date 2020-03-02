<?php

include_once("common/connectToDB.php");

class Translator{
	function __construct(){

	}

	function render(){
		$cachedSVG = file_get_contents("images/icons/cached.svg");
		$sendSVG = file_get_contents("images/icons/send.svg");

		echo <<<HTML
		<section class="translation">
			{$this->translatorFieldset("original Label","original Definition","original Scope", "en")}

			<div class="actions">
				<button class="btn light-blue darken-4 waves-effect waves-light">
					update
					{$sendSVG}
				</button>
				
				<div class="translationInfo">
					<div id='wordID'>word ID</div>
					<div id='wordID'>last update</div>
				</div>
				
				<button class="btn right light-blue darken-4 waves-effect waves-light">
					reverse
					{$cachedSVG}
				</button>
			</div>

			{$this->translatorFieldset("translated Label","translated Definition","translated Scope", "cs")}

		</section>
HTML;
	}	
	
	function translatorFieldset($label, $definition, $scope, $prefix){
		$label = htmlentities($label);
		$definition = htmlentities($definition);
		$scope = htmlentities($scope);
		
		return <<<HTML
		<div class="block grid">
			<div class="inputField">
				<label for="{$prefix}:label">label</label>
				<textarea id="{$prefix}:label" class="materialize-textarea">$label</textarea>
			</div>
			
			<div class="inputField">
				<label for="{$prefix}:definition">definition</label>
				<textarea id="{$prefix}:definition" class="materialize-textarea">$definition</textarea>
			</div>
			
			<div class="inputField">
				<label for="{$prefix}:scope">scope</label>
				<textarea id="{$prefix}:scope" class="materialize-textarea">$scope</textarea>
			</div>
		</div>
HTML;
	}

}
