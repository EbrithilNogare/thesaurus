<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");


if(
	!isset($_GET["id"]) ||
	$_GET["id"] === null ||
	$_GET["id"] === ""){
		echo "error in params";
		http_response_code(400);
		return;
}

// todo add some autorization

$id = intval($_GET["id"]);
$responseData = [];
$responseData['childs'] = [];

$conn = connectToDB();

$stmt = $conn->prepare("SELECT *  FROM `translations` WHERE `word_id` = ?");
$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows === 0) return;

while($row = $result->fetch_assoc()) {
	$tempArray = [];
	$tempArray['id'] = $row['word_id'];
	$tempArray['label'] = $row['label'];
	$tempArray['definition'] = $row['definition'];
	$tempArray['scope'] = $row['scope'];
  	$responseData[$row['language']] = $tempArray;
}

$stmt = $conn->prepare("SELECT ID, label FROM `words` LEFT JOIN translations ON translations.word_id = words.ID WHERE language = 'en' AND `parent` = ?");
$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows != 0){
	while($row = $result->fetch_assoc()) {
		$responseData['childs'][$row['ID']] = $row['label'];
	}
}

$stmt->close();
$conn->close();

echo json_encode($responseData);

