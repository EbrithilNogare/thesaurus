<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
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
$responseData = [];
$responseData['id'] = $id;
$responseData['lastUpdate'] = "no data";
$responseData['childs'] = [];
$responseData['parent'] = [];

$conn = connectToDB();

// get word and translation
$stmt = $conn->prepare("SELECT *  FROM `translations` WHERE `word_id` = ?");
$stmt->bind_param("i", $id);

if(!$stmt->execute()){
	echo "something wrog with DB";	
	echo $stmt->error;
	$stmt->close();
	$conn->close();
	http_response_code(400);
	return;
}

$result = $stmt->get_result();
if($result->num_rows === 0){
	echo "word not found";
	$stmt->close();
	$conn->close();
	http_response_code(400);
	return;
}

while($row = $result->fetch_assoc()) {
	$tempArray = [];
	$tempArray['label'] = $row['label'];
	$tempArray['definition'] = $row['definition'];
	$tempArray['scope'] = $row['scope'];
  	$responseData[$row['language']] = $tempArray;
}

// get childs for tree view
$stmt = $conn->prepare("SELECT ID, label, childs FROM `words` LEFT JOIN translations ON translations.word_id = words.ID WHERE language = 'en' AND `parent` = ?");
$stmt->bind_param("i", $id);

if(!$stmt->execute()){
	echo "something wrog with DB";		
	echo $stmt->error;
	$stmt->close();
	$conn->close();
	http_response_code(400);
	return;
}

$result = $stmt->get_result();
if($result->num_rows != 0){
	while($row = $result->fetch_assoc()) {
		$responseData['childs'][$row['ID']] = $row['label'];
		$responseData['parent'][$row['ID']] = $row['childs'] == "1";
	}
}

// get last update of this word
$sql = <<<SQL
	SELECT username, time
	FROM `log`
	LEFT JOIN `users`
	ON `log`.`user` = `users`.`ID`
	WHERE `word_id` = ?  
	ORDER BY `log`.`time` DESC
	LIMIT 1
SQL;

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if(!$stmt->execute()){
	echo "something wrog with DB";		
	echo $stmt->error;
	$stmt->close();
	$conn->close();
	http_response_code(400);
	return;
}

$result = $stmt->get_result();
if($result->num_rows != 0){
	$row = $result->fetch_assoc();
	$responseData['lastUpdate'] = "${row['username']} (${row['time']})";	
}

$stmt->close();
$conn->close();

echo json_encode($responseData);

