<?php

include_once("common/connectToDB.php");

class Translator{
	function __construct(){

	}

	function render(){
		echo <<<HTML
		<div class="col s6 offset-s1 card-panel light-blue">
			<div class="card-panel light-blue lighten-5">
				<div id='wordID'>word ID</div>
			</div>

			{$this->translatorFieldset("original Label","original Definition","original Scope", "en")}

			<div>
				<button class="btn light-blue darken-4 waves-effect waves-light">
					<i class="material-icons right">send</i>
					update
				</button>
				<button class="btn right light-blue darken-4 waves-effect waves-light">
					<i class="material-icons right">cached</i>
					reverse
				</button>
			</div>

			{$this->translatorFieldset("translated Label","translated Definition","translated Scope", "cs")}

		</div>
HTML;
	}	
	
	function translatorFieldset($label, $definition, $scope, $prefix){
		$label = htmlentities($label);
		$definition = htmlentities($definition);
		$scope = htmlentities($scope);
		
		return <<<HTML
		<div class="card-panel light-blue lighten-5">
			<div class="input-field">
				<textarea id="{$prefix}:label" class="materialize-textarea">$label</textarea>
				<label for="{$prefix}:label">label</label>
			</div>
			
			<div class="input-field">
				<textarea id="{$prefix}:definition" class="materialize-textarea">$definition</textarea>
				<label for="{$prefix}:definition">definition</label>
			</div>
			
			<div class="input-field">
				<textarea id="{$prefix}:scope" class="materialize-textarea">$scope</textarea>
				<label for="{$prefix}:scope">scope</label>
			</div>
		</div>
HTML;
	}

}
