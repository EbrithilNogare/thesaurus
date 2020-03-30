<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");

function getTranslation($id){
	$toReturn = [
		"success" => true,
		"message" => "",
		"id" => $id,
		"parent" => [
			"id" => 0,
			"label" => 0,
		],
		"childs" => [],
		"childsParents" => [],
		"lastUpdate" => [
			"username" => "",
			"time" => "",
		],
		"translations" => [],
	];

	$conn = connectToDB();

	// get word and translation
	$stmt = $conn->prepare("SELECT *  FROM `translations` WHERE `word_id` = ?");
	$stmt->bind_param("i", $id);

	if(!$stmt->execute()){
		$toReturn["message"] .= "something wrong with DB";
		$toReturn["message"] .= $stmt->error;	
		$stmt->close();
		$conn->close();
		$toReturn["success"] = false;
		return $toReturn;
	}

	$result = $stmt->get_result();
	if($result->num_rows === 0){
		$toReturn["message"] .= "word not found";
		$stmt->close();
		$conn->close();
		$toReturn["success"] = false;
		return $toReturn;
	}

	while($row = $result->fetch_assoc()) {
		$tempArray = [];
		$tempArray['label'] = $row['label'];
		$tempArray['definition'] = $row['definition'];
		$tempArray['scope'] = $row['scope'];
		$toReturn["translations"][$row['language']] = $tempArray;
	}


	// get paarent ID and name
	$sql = <<<SQL
		SELECT parent, label
		FROM words
		LEFT JOIN translations
		ON translations.word_id = words.parent
		WHERE `ID` = ? AND `language` = "en"
	SQL;

	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);

	if(!$stmt->execute()){
		$toReturn["message"] .= "something wrong with DB";
		$toReturn["message"] .= $stmt->error;	
		$stmt->close();
		$conn->close();
		$toReturn["success"] = false;
		return $toReturn;
	}

	$result = $stmt->get_result();
	if($result->num_rows === 0){
		$toReturn["message"] .= "word not found";
		$stmt->close();
		$conn->close();
		$toReturn["success"] = false;
		return $toReturn;
	}

	$row = $result->fetch_assoc();
	$toReturn['parent']['id'] = $row['parent'];
	$toReturn['parent']['label'] = $row['label'];


	// get childs for tree view
	$sql = <<<SQL
		SELECT ID, label, childs
		FROM words
		LEFT JOIN translations
		ON translations.word_id = words.ID
		WHERE `parent` = ? AND language = 'en' AND ID != parent
		ORDER BY translations.label ASC	
	SQL;

	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);

	if(!$stmt->execute()){
		$toReturn["message"] .= "something wrong with DB";
		$toReturn["message"] .= $stmt->error;	
		$stmt->close();
		$conn->close();
		$toReturn["success"] = false;
		return $toReturn;
	}

	$result = $stmt->get_result();
	if($result->num_rows != 0){
		while($row = $result->fetch_assoc()) {
			$toReturn['childs'][$row['ID']] = $row['label'];
			$toReturn['childsParents'][$row['ID']] = $row['childs'] == "1";
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
		$toReturn["message"] .= "something wrong with DB";
		$toReturn["message"] .= $stmt->error;	
		$stmt->close();
		$conn->close();
		$toReturn["success"] = false;
		return $toReturn;
	}

	$result = $stmt->get_result();
	if($result->num_rows != 0){
		$row = $result->fetch_assoc();
		$toReturn['lastUpdate']['username'] = $row['username'];
		$toReturn['lastUpdate']['time'] = $row['time'];
	}

	$stmt->close();
	$conn->close();
	return $toReturn;
}
