<?php

include_once("common/connectToDB.php");
include_once("common/sqlQueries.php");


class Navigation{
	private $searchValue = "";
	private $hiearchyTree = [];

	function __construct(){
		$searchValue = "";
		$this->hiearchyTree = loadTreeViewByParentID(0);
	}

	function render(){
		echo <<<HTML
		<div class="col s3 offset-s1 card-panel light-blue">
			{$this->searchBar($this->searchValue)}
			{$this->hiearchyTreeMenu()}
		</div>
HTML;
	}

	function hiearchyTreeMenu(){
		$hiearchyTreeRendered = "";
		foreach($this->hiearchyTree as $id => &$hiearchyLeaf){
			$hiearchyTreeRendered .= $this->hiearchyTreeMenuLeaf($id, $hiearchyLeaf);
		}

		return <<<HTML
			<ul class="collection">
				{$hiearchyTreeRendered}
			</ul>
HTML;
	}

	function hiearchyTreeMenuLeaf($id, $hiearchyLeaf){
		$hiearchyLeaf = htmlentities($hiearchyLeaf);
		$hiearchyLeaf = htmlentities($hiearchyLeaf);
		return <<<HTML
			<li class='collection-item tooltipped' data-position='left' data-tooltip='id:{$id}'>
				{$hiearchyLeaf}
			</li>
HTML;
	}

	function searchBar($searchValue){
		$searchValue = htmlentities($searchValue);
		return <<<HTML
			<div class="card-panel light-blue lighten-5">
				<input type="text" value="{$searchValue}"/>
				<button class="btn light-blue darken-4 waves-effect waves-light" style="width:100%">
					<i class="material-icons left">search</i>
					find
				</button>
			</div>
HTML;
	}
	
}