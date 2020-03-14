<?php

include_once("common/connectToDB.php");


class Admin{
	private $status;

	function __construct($userInfo){
		$this->status = isset($userInfo["status"]) ? $userInfo["status"] : "X";
	}

	function render(){
		if($this->status!="A"){
			echo '<div class="block">This section is only for admins</div>';
			return;
		}

		$usersTable = "";
		$usersList = $this->getUsers();
		foreach ($usersList as $key => $value) {
			$isAdmin = $value[1] == "A" ? "checked" : "";
			$usersTable.=<<<HTML
			<tr>
				<td>{$key}</td>
				<td>{$value[0]}</td>
				<td><input type="password" id="userEditPswd:{$key}"></td>
				<td>
					<label class="mtlCheckbox">
						<input type="checkbox" id="userEditAdmin:{$key}" $isAdmin>
						<span class="checkmark"></span>
					</label>
				</td>
				<td>
					<button onclick="updateUser({$key});">save</button>
					<button onclick="removeUser({$key});">remove</button><br>
					<span class="" id="userEditResult:{$key}"></span>
				</td>
			</tr>
HTML;
		}

		echo <<<HTML
			<section class="users">
				<div class="block userAdd">					
					Name:<input type="text" id="userAddName">
					<label class="mtlCheckbox">
						Admin
						<input type="checkbox" id="userAddAdmin">
						<span class="checkmark"></span>
					</label>
					Password:<input type="password" id="userAddPassword">
					<button onclick="createUser();">create</button>
					<div></div>
					<div id="userAddMessage"></div>
				</div>

				<hr>

				<div class="scrollable">	
					<table class="userEdit">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Change password</th>
								<th>Admin</th>
								<th style="width: 200px;"></th>
							</tr>
						</thead>
						<tbody>
							{$usersTable}
						</tbody>
					</table>
				</div>
			</section>
HTML;
	}

	function getUsers(){
		$toReturn = [];
		$conn = connectToDB();
	
		$sql = <<<SQL
			SELECT ID, username, status
			FROM `users`
SQL;

		$stmt = $conn->prepare($sql);
		if(!$stmt->execute()){
			$stmt->close();
			$conn->close();
			echo '{"Status": "Error", "Message": "something wrong with DB"}';
			return;
		}
		
		$result = $stmt->get_result();

		while ($row = $result->fetch_assoc()) {
			$toReturn[$row["ID"]] = [$row["username"], $row["status"]];
		}
		return $toReturn;
	}
}