<?php

include_once(dirname(__DIR__)."/common/connectToDB.php");
include_once(dirname(__DIR__)."/common/random.php");


class Login{
	private $errorMessage = "";
	public $logged = false;
	public $userInfo = [];

	function __construct(){
		if($this->validSession()){
			$this->logged = true;
			$this->userInfo["sessionID"] = $_COOKIE["sessionID"];
		}else if ($_SERVER["REQUEST_METHOD"] == "POST"){
			$loginResult = $this->tryLogIn($_POST["username"], $_POST["password"]);
			if($loginResult["success"]){
				$this->logged = true;				
				$this->userInfo["username"] = $loginResult["username"];
				$this->userInfo["status"] = $loginResult["status"];
				$this->userInfo["id"] = $loginResult["id"];
			}else{
				// todo
			}
		}
	}

	function render(){
		echo <<<HTML
		<form method="post" class="loginForm block">
			<b>Username</b> <input type="text" name="username">
			<b>Password</b> <input type="password" name="password">
			<div class="errorMessage">{$this->errorMessage}</div>
			<input type="submit" value="Login">
		</form>
HTML;
	}

	function tryLogIn($username, $password){
		$toReturn = [];
		$conn = connectToDB();
	
		$sql = <<<SQL
			SELECT ID, password, status
			FROM `users`
			WHERE `username` = ?
SQL;

		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $username);
		if(!$stmt->execute()){
			$stmt->close();
			$conn->close();
			echo '{"Status": "Error", "Message": "something wrong with DB"}';
			return;
		}
		$result = $stmt->get_result();
			
		if($result->num_rows == 0){ // wrong username
			$stmt->close();
			$conn->close();
			$toReturn["success"] = false;
			$toReturn["message"] = "Wrong username or password";
			return $toReturn;
		}

		$row = $result->fetch_assoc();
		$hashedPassword = $row['password'];
		$status = $row['status'];
		$userId = $row['ID'];

		if (!hash_equals($hashedPassword, crypt($password, $hashedPassword))) { // wrong password
			$stmt->close();
			$conn->close();
			$toReturn["success"] = false;
			$toReturn["message"] = "Wrong username or password";
			return $toReturn;
		}


		$sql = <<<SQL
			UPDATE users
			SET session = ?, sessionExpiration = ?
			WHERE users.username = ?;
SQL;
		$newSessionID = generateRandomString(32);
		$sessionTimeToDie = time() + 8*60*60;
		$sessionExpiration = date("Y-m-d H:i:s", $sessionTimeToDie);
		
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("sss", $newSessionID, $sessionExpiration, $username);
		if(!$stmt->execute()){
			$stmt->close();
			$conn->close();
			echo '{"Status": "Error", "Message": "something wrong in session validation"}';
			return;
		}

		setcookie("sessionID", $newSessionID, $sessionTimeToDie);

		$stmt->close();
		$conn->close();
		$toReturn["success"] = true;
		$toReturn["username"] = $username;
		$toReturn["status"] = $status;
		$toReturn["id"] = $userId;
		return $toReturn;
	}
	

	function validSession(){
		if(!isset($_COOKIE["sessionID"]) || empty($_COOKIE["sessionID"]))
			return false;

		$conn = connectToDB();
	
		$sql = <<<SQL
			SELECT ID, status, sessionExpiration, username
			FROM `users`
			WHERE `session` = ?
SQL;

		$stmt = $conn->prepare($sql);
		$stmt->bind_param("s", $_COOKIE["sessionID"]);
		if(!$stmt->execute()){
			$stmt->close();
			$conn->close();
			echo '{"Status": "Error", "Message": "something wrong in session validation"}';
			return;
		}
		$result = $stmt->get_result();
			
		if($result->num_rows > 0){
			$toReturn = true;
			$row = $result->fetch_assoc();
			$this->userInfo["status"] = $row["status"];
			$this->userInfo["username"] = $row["username"];
			$this->userInfo["id"] = $row["ID"];
			if(strtotime($row["sessionExpiration"])<=time()){
				$toReturn = false;
				$this->userInfo["status"] = "X";
			}
		}else{
			$toReturn = false;
		}

		$stmt->close();
		$conn->close();

		return $toReturn;
	}
}