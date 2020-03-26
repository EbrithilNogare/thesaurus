<?php

include_once("common/connectToDB.php");


class Navigation{
	private $searchValue = "";
	private $hiearchyTree = [];
	private $triangleSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M10 17l5-5-5-5v10z"/><path d="M0 24V0h24v24H0z" fill="none"/></svg>';

	function __construct($wordId){
		$searchValue = "";
		$this->hiearchyTree = $this->loadTreeViewByParentID($wordId);
	}

	function render(){
		echo <<<HTML
		<section class="navigation">
			{$this->searchBar($this->searchValue)}
			{$this->hiearchyTreeMenu()}
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

	function hiearchyTreeMenu(){
		$hiearchyTreeRendered = "";
		foreach($this->hiearchyTree as $id => &$hiearchyLeaf){
			$hiearchyTreeRendered .= $this->hiearchyTreeMenuLeaf($id, $hiearchyLeaf);
		}

		return <<<HTML
			<div class="scrollable">
				{$hiearchyTreeRendered}
			</div>
HTML;
	}

	function hiearchyTreeMenuLeaf($id, $hiearchyLeaf){
		$hiearchyLeaf = htmlentities($hiearchyLeaf);

		return <<<HTML
			<div class='treeBlock' onclick='loadLeaf({$id})' id='leaf:{$id}'>
				<div class="treeHeader">{$this->triangleSVG}{$hiearchyLeaf}</div>
				<div class="treeCollection" id='leafCollection:{$id}' hidden></div>
			</div>
HTML;
	}	

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
			$hiearchyTree[$row["ID"]] = $row["label"];
		}
	
		$stmt->close();
		$conn->close();
		return $hiearchyTree;
	}
}