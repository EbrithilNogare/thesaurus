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
			{$this->translatorFieldset("","","", "en")}

			<div class="actions">
				<button onclick="updateTranslation()">
					update
					{$sendSVG}
				</button>
				
				<div class="block" id="wordID">
					ID:
				</div>
				
				<div class="block" id="wordParent" onclick="changeParent()">
					parent:
				</div>
				
				<div class="block" id="wordLastUpdate" onclick="showLastUpdates()">
					last update:
				</div>
								
				<button onclick="Thesaurus.updateTranslationView()">
					reverse
					{$cachedSVG}
				</button>
			</div>

			{$this->translatorFieldset("","","", "cs")}

		</section>
HTML;
	}	
	
	function translatorFieldset($label, $definition, $scope, $prefix){
		$label = htmlentities($label);
		$definition = htmlentities($definition);
		$scope = htmlentities($scope);
		
		return <<<HTML
		<div class="block grid">
		
			<label for="{$prefix}:label">label</label>
			<textarea id="{$prefix}:label">$label</textarea>
			
			<label for="{$prefix}:definition">definition</label>
			<textarea id="{$prefix}:definition">$definition</textarea>
			
			<label for="{$prefix}:scope">scope</label>
			<textarea id="{$prefix}:scope">$scope</textarea>
			
		</div>
HTML;
	}

}
