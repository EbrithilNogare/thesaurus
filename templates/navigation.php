<?php

include_once("common/connectToDB.php");
include_once("common/sqlQueries.php");


class Navigation{
	private $searchValue = "";
	private $hiearchyTree = [];

	function __construct(){

	}

	function loadData(){
		$this->hiearchyTree = loadTreeViewByParentID(0);
	}

	function render(){
		$this->loadData();
		$this->searchValue = htmlentities($this->searchValue);
		$hiearchyTreeRendered = "";
		foreach($this->hiearchyTree as &$hiearchyLeaf){
			$hiearchyTreeRendered .= "<li class='collection-item'>";
			$hiearchyTreeRendered .= htmlentities($hiearchyLeaf);
			$hiearchyTreeRendered .= "</li>";
		}

		
		
		echo <<<HTML
		<div class="col s3 offset-s1 card-panel light-blue">
			<div class="card-panel light-blue lighten-5">
				<input type="text" value="$this->searchValue"/>
				<button class="btn light-blue darken-4 waves-effect waves-light" style="width:100%">
					<i class="material-icons left">search</i>
					find
				</button>
			</div>
			<ul class="collection">
				{$hiearchyTreeRendered}
			</ul>
		</div>
HTML;
	}

	
}