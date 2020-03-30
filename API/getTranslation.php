<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
include_once(dirname(__DIR__)."/common/getTranslation.php");
include_once(dirname(__DIR__)."/templates/login.php");

$login = new Login(); 

if(!$login->logged){
	echo 'You are not logged in';
	http_response_code(400);
	return;
}

if(
	!isset($_GET["id"]) ||
	$_GET["id"] === null ||
	$_GET["id"] === ""){
		echo "error in params";
		http_response_code(400);
		return;
}

$id = intval($_GET["id"]);


$translation = getTranslation($id);
if(!$translation["success"]){
	echo $translation["message"];		
	http_response_code(400);
	return;
}


$responseData = [];
$responseData['id'] = $id;

if($translation["lastUpdate"]["username"]!="")
	$responseData['lastUpdate'] = "{$translation["lastUpdate"]["username"]} ({$translation["lastUpdate"]["time"]})";
else
	$responseData['lastUpdate'] = "";

$responseData['childs'] = $translation["childs"];
$responseData['parent'] = $translation["childsParents"];
$responseData['parentId'] = $translation['parent']['id'];
$responseData['parentLabel'] = $translation['parent']['label'];

foreach ($translation["translations"] as $key => $value){
	$responseData[$key] = $value;
}


echo json_encode($responseData);
