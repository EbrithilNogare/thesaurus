<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
include_once(dirname(__DIR__)."/common/random.php");
include_once(dirname(__DIR__)."/templates/login.php");

$login = new Login(); 

if(!$login->logged){
	echo '{"Status": "Error", "Message": "Your session expired"}';
	return;
}

if(
	!isset($_GET["username"]) ||
	!isset($_GET["password"]) ||
	!isset($_GET["admin"]) ||
	empty($_GET["username"]) ||
	empty($_GET["password"])
){
	echo '{"Status": "Error", "Message": "Parameters are incorect"}';
	return;
}




$conn = connectToDB();
	
$sql = <<<SQL
SELECT ID
FROM `users`
WHERE `username` = ?
SQL;

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_GET["username"]);
if(!$stmt->execute()){
	$stmt->close();
	$conn->close();
	echo '{"Status": "Error", "Message": "something wrong with DB"}';
	return;
}
$result = $stmt->get_result();

if($result->num_rows != 0){ // wrong username
	$stmt->close();
	$conn->close();
	echo '{"Status": "Error", "Message": "User already exist"}';
	return;
}


$sql = <<<SQL
	INSERT INTO `users`
	(`ID`, `username`, `password`, `status`, `session`, `sessionExpiration`)
	VALUES (NULL, ?, ?, ?, ?, '');
SQL;
$isAdmin = $_GET["admin"] == 1 ? "A" : "U";
$hashedPassword =  crypt($_GET["password"], "$1$".generateRandomString(8)."$");
$expiredSession = generateRandomString(32);

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $_GET["username"], $hashedPassword, $isAdmin, $expiredSession);
if(!$stmt->execute()){
	$stmt->close();
	$conn->close();
	echo '{"Status": "Error", "Message": "something wrong with DB"}';
	return;
}

$stmt->close();
$conn->close();
echo '{"Status": "Succes", "Message": "User created successfully"}';
