<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
include_once(dirname(__DIR__)."/common/getTranslation.php");


class Navigation{
	private $searchValue = "";
	private $rootId = 0;
	//private $hiearchyTree = [];
	private $triangleSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M10 17l5-5-5-5v10z"/><path d="M0 24V0h24v24H0z" fill="none"/></svg>';
	private $dotSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="m12,8c-2.21,0 -4,1.79 -4,4s1.79,4 4,4s4,-1.79 4,-4s-1.79,-4 -4,-4z"/></svg>';

	function __construct($wordId){
		$this->searchValue = "";
		$this->rootId = intval($wordId);
	//	$this->hiearchyTree = $this->loadTreeViewByParentID($wordId);
	}

	function render(){
		echo <<<HTML
		<section class="navigation">
			{$this->searchBar($this->searchValue)}			
			<div class="scrollable">
				{$this->hiearchyTreeMenu($this->rootId)}
			</div>
		</section>
HTML;
	}

	function searchBar($searchValue){
		$searchValue = htmlentities($searchValue);
		$searchSVG = file_get_contents("images/icons/search.svg");

		return <<<HTML
			<div class="block searchBlock">
				<input type="text" value="{$searchValue}"/>
				<button class="">
					{$searchSVG}
				</button>
			</div>
HTML;
	}

	function hiearchyTreeMenu($id, $childId = null, $restTree = null){
		$translation = getTranslation($id);

		if(!$translation["success"]){
			echo $translation["message"];
			// todo
		}

		if($restTree === null)
			return $this->hiearchyTreeMenu($translation["parent"]["id"], $id, "");


		$hiearchyTreeRendered = "";

		foreach($translation["childs"] as $key => &$value){
			if($childId === $key)
				$hiearchyTreeRendered .= $this->hiearchyTreeMenuLeaf($key, $value, $translation["childsParents"][$key], $restTree);
			else
				$hiearchyTreeRendered .= $this->hiearchyTreeMenuLeaf($key, $value, $translation["childsParents"][$key], "");
		}
		
		if($translation["id"] == 0)
			return $hiearchyTreeRendered;

		return $this->hiearchyTreeMenu($translation["parent"]["id"], $id, $hiearchyTreeRendered);
	}

	function hiearchyTreeMenuLeaf($id, $label, $isParent, $treeCollection){
		$clearLabel = htmlentities($label);
		$svgIcon = $isParent ? $this->triangleSVG : $this->dotSVG;
		$hidden = $treeCollection == "" ? "hidden" : "";

		if($treeCollection != "")
			$svgIcon = substr($svgIcon, 0, 5) . "style='transform: rotate(90deg); '" . substr($svgIcon, 5);

		return <<<HTML
			<div class='treeBlock' onclick='loadLeaf({$id})' id='leaf:{$id}'>
				<div class="treeHeader">{$svgIcon}{$clearLabel}</div>
				<div class="treeCollection" id='leafCollection:{$id}' {$hidden}>{$treeCollection}</div>
			</div>
HTML;
	}	
/*
	function loadTreeViewByParentID($parentID){
		$hiearchyTree = [];
		$conn = connectToDB();
	
		$sql = <<<SQL
			SELECT ID, label, childs
			FROM words
			LEFT JOIN translations ON translations.word_id = words.ID
			WHERE parent = ? AND language = 'en'
			ORDER BY translations.label ASC	
	SQL;
	
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $parentID);
		$stmt->execute();
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			$hiearchyTree[$row["ID"]] = [
				"label" => $row["label"],
				"isParent" => $row["childs"],
			];
		}
	
		$stmt->close();
		$conn->close();
		return $hiearchyTree;
	}
	*/
}