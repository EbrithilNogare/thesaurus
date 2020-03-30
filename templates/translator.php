<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
include_once(dirname(__DIR__)."/common/getTranslation.php");

class Translator{
	private $wordToLoad;
	private $translationData;
	function __construct($wordId){
		$wordToLoad = $wordId;
		$this->translationData = getTranslation($wordId);
		if(!$this->translationData["success"]){
			// todo something ??
		}
	}

	

	function render(){
		$cachedSVG = file_get_contents("images/icons/cached.svg");
		$sendSVG = file_get_contents("images/icons/send.svg");
		$lastUpdateRendered = $this->translationData["lastUpdate"]["username"];
		if($this->translationData["lastUpdate"]["username"] != "")
			$lastUpdateRendered .= "({$this->translationData['lastUpdate']['username']})";

		echo <<<HTML
		<section class="translation">
			{$this->translatorFieldset(
				$this->translationData["translations"]["en"]["label"],
				$this->translationData["translations"]["en"]["definition"],
				$this->translationData["translations"]["en"]["scope"],
				"en")}

			<div class="actions">
				<button onclick="updateTranslation()">
					update
					{$sendSVG}
				</button>
				
				<div class="block" id="wordID">
					ID: {$this->translationData["id"]}
				</div>
				
				<div class="block" onclick="changeParent()">
					<input type="hidden" id="wordParentId" value="{$this->translationData["parent"]["id"]}">
					parent: <span id="wordParentLabel">{$this->translationData["parent"]["label"]}</span>
				</div>
				
				<div class="block" id="wordLastUpdate" onclick="showLastUpdates()">
					last update: {$lastUpdateRendered}
				</div>
								
				<button onclick="Thesaurus.updateTranslationView()">
					reverse
					{$cachedSVG}
				</button>
			</div>

			{$this->translatorFieldset(
				$this->translationData["translations"]["cs"]["label"],
				$this->translationData["translations"]["cs"]["definition"],
				$this->translationData["translations"]["cs"]["scope"],
				"cs")}

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
