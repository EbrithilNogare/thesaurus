<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
include_once(dirname(__DIR__)."/templates/login.php");

$login = new Login(); 

if(!$login->logged){
	echo 'You are not logged in';
	http_response_code(400);
	return;
}

if(!$_SERVER["REQUEST_METHOD"] == "POST"){
	echo 'Bad request type';
	http_response_code(400);
	return;
}

$data = json_decode(file_get_contents('php://input'), true);

if(
	!isset($data["id"]) ||
	!isset($data["parent"]) ||
	!isset($data["original"]) ||
	!isset($data["original"]["lang"]) ||
	!isset($data["translation"]) ||
	!isset($data["translation"]["lang"]) ||
	empty($data["id"])){
		echo "error in params";
		http_response_code(400);
		return;
}

$conn = connectToDB();

// get previous
$previousData = [];

$stmt = $conn->prepare("SELECT * FROM translations LEFT JOIN words ON translations.word_id = words.ID WHERE `word_id` = ?");
$stmt->bind_param("i", $data["id"]);

if(!$stmt->execute()){
	echo "something wrong with DB";	
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
	$previousData[$row['language']] = $tempArray;
	$previousData['parent'] = $row['parent'];
}



// log original
$settings = [
	"conn"=>$conn,
	"userId"=>$login->userInfo["id"],
	"wordId"=>$data["id"],
];
$lang = $data["original"]["lang"];
logChange($settings, "label", 		$lang, $data["original"]["label"], 		$previousData[$lang]["label"]);
logChange($settings, "definition", 	$lang, $data["original"]["definition"],	$previousData[$lang]["definition"]);
logChange($settings, "scope", 		$lang, $data["original"]["scope"], 		$previousData[$lang]["scope"]);



// update original
$sql = <<<SQL
	UPDATE `translations`
	SET `label` = ?, `definition` = ?, `scope` = ?
	WHERE `translations`.`word_id` = ? AND `translations`.`language` = ?;
SQL;

$stmt = $conn->prepare($sql);

$stmt->bind_param("sssis",
	$data["original"]["label"],
	$data["original"]["definition"],
	$data["original"]["scope"],
	$data["id"],
	$data["original"]["lang"]
);
if(!$stmt->execute()){
	echo "something wrong with DB";	
	echo $stmt->error;	
	$stmt->close();
	$conn->close();
	http_response_code(400);
	return;
}



// log translation
$lang = $data["translation"]["lang"];
logChange($settings, "label", 		$lang, $data["translation"]["label"], 		$previousData[$lang]["label"]);
logChange($settings, "definition", 	$lang, $data["translation"]["definition"], 	$previousData[$lang]["definition"]);
logChange($settings, "scope", 		$lang, $data["translation"]["scope"], 		$previousData[$lang]["scope"]);



// update translation
$stmt->bind_param("sssis",
	$data["translation"]["label"],
	$data["translation"]["definition"],
	$data["translation"]["scope"],
	$data["id"],
	$data["translation"]["lang"]
);
if(!$stmt->execute()){
	echo "something wrong with DB";		
	echo $stmt->error;
	$stmt->close();
	$conn->close();
	http_response_code(400);
	return;
}


if($data["parent"] != $previousData['parent']){
	// log parent change
	logChange($settings, "parent", $lang, $data["parent"], $previousData['parent']);



	// parent change	
	$stmt1 = $conn->prepare("UPDATE words SET parent = ? WHERE ID = ?");
	$stmt2 = $conn->prepare("UPDATE words SET childs = 1 WHERE ID = ?");
	$stmt3 = $conn->prepare("UPDATE words SET childs = 0 WHERE ID = ?");
	$stmt4 = $conn->prepare("UPDATE words, words AS words2 SET words.childs = 1 WHERE words.ID = ? AND words.ID = words2.parent");
	
	$stmt1->bind_param("ii", $data["parent"], $data["id"]);
	$stmt2->bind_param("i", $data["parent"]);
	$stmt3->bind_param("i", $previousData["parent"]);
	$stmt4->bind_param("i", $previousData["parent"]);

	if(
		(!$stmt1->execute()) ||
		(!$stmt2->execute()) ||
		(!$stmt3->execute()) ||
		(!$stmt4->execute())
	){
		echo "something wrong with DB";		
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		http_response_code(400);
		return;
	}
}



$stmt->close();
$conn->close();


function logChange($settings, $fieldName, $language, $currentField, $previousField){
	if($currentField == $previousField)
		return;

	$sql = <<<SQL
		INSERT
		INTO `log` (`word_id`, `language`, `time`, `user`, `field`, `previous`, `after`)
		VALUES ( ?, ?, current_timestamp(), ?, ?, ?, ?);
SQL;	

	$stmt = $settings["conn"]->prepare($sql);
	
	$stmt->bind_param("isisss",
		$settings["wordId"],
		$language,
		$settings["userId"],
		$fieldName,
		$previousField,
		$currentField
	);
	if(!$stmt->execute()){
		echo "something wrong with DB";	
		echo $stmt->error;
		$stmt->close();
		$conn->close();
		http_response_code(400);
		return;
	}
}
