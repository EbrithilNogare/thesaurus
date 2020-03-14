<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
include_once(dirname(__DIR__)."/templates/login.php");

$login = new Login(); 

if(!$login->logged){
	echo '{"Status": "Error", "Message": "Your session expired"}';
	return;
}

if(
	!isset($_GET["id"]) ||
	empty($_GET["id"])
){
	echo '{"Status": "Error", "Message": "Parameters are incorect"}';
	return;
}


$conn = connectToDB();

$sql = <<<SQL
	DELETE FROM `users`
	WHERE `users`.`ID` = ?
SQL;
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_GET["id"]);

if(!$stmt->execute()){
	$stmt->close();
	$conn->close();
	echo '{"Status": "Error", "Message": "something wrong with DB"}';
	return;
}

$stmt->close();
$conn->close();
echo '{"Status": "Succes", "Message": "User removed successfully"}';
