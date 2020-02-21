<?php

include_once("common/connectToDB.php");

class Translator{
	function __construct(){

	}

	function render(){
		echo <<<HTML
		<div class="col s6 offset-s1 card-panel light-blue">
			<div class="card-panel light-blue lighten-5">
				<div>word ID</div>
			</div>

			{$this->translatorFieldset("original Label","original Definition","original Scope")}

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

			{$this->translatorFieldset("translated Label","translated Definition","translated Scope")}

		</div>
HTML;
	}	
	
	function translatorFieldset($label, $definition, $scope){
		$label = htmlentities($label);
		$definition = htmlentities($definition);
		$scope = htmlentities($scope);
		
		return <<<HTML
		<div class="card-panel light-blue lighten-5">
			<div class="input-field">
				<textarea id="textarea1" class="materialize-textarea">$label</textarea>
				<label for="textarea1">label</label>
			</div>
			
			<div class="input-field">
				<textarea id="textarea1" class="materialize-textarea">$definition</textarea>
				<label for="textarea1">definition</label>
			</div>
			
			<div class="input-field">
				<textarea id="textarea1" class="materialize-textarea">$scope</textarea>
				<label for="textarea1">scope</label>
			</div>
		</div>
HTML;
	}

}
