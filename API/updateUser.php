<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
include_once(dirname(__DIR__)."/templates/login.php");

$login = new Login(); 

if(!$login->logged){
	echo '{"Status": "Error", "Message": "Your session expired"}';
	return;
}

if($login->userInfo["status"] != "A"){
	echo '{"Status": "Error", "Message": "You are not admin"}';
	return;
}

if(
	!isset($_GET["id"]) ||
	!isset($_GET["password"]) ||
	!isset($_GET["admin"]) ||
	empty($_GET["id"])
){
	echo '{"Status": "Error", "Message": "Parameters are incorect"}';
	return;
}


$conn = connectToDB();

$isAdmin = $_GET["admin"] == 1 ? "A" : "U";
$hashedPassword =  crypt($_GET["password"], "$1$".generateRandomString(8)."$");
if(empty($_GET["password"])){
	$sql = <<<SQL
		UPDATE `users`
		SET `status` = ?
		WHERE `users`.`ID` = ?;
SQL;
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("ss", $isAdmin, $_GET["id"]);
}else{
	$sql = <<<SQL
		UPDATE `users`
		SET `status` = ?, `password` = ?
		WHERE `users`.`ID` = ?;
SQL;
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("sss", $isAdmin, $hashedPassword, $_GET["id"]);
}

if(!$stmt->execute()){
	$stmt->close();
	$conn->close();
	echo '{"Status": "Error", "Message": "something wrong with DB"}';
	return;
}

$stmt->close();
$conn->close();
echo '{"Status": "Succes", "Message": "User edited successfully"}';
