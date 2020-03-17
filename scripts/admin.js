function createUser(){
	const username = $("userAddName").value;
	const password = $("userAddPassword").value;
	const adminStatus = $("userAddAdmin").checked ? 1 : 0;

	fetch("API/addUser.php/?username="+username+"&password="+password+"&admin="+adminStatus)
		.then(result=>result.json())
		.then(json=>{			
			$("userAddMessage").innerHTML = json["Message"];
			if(json["Status"] == "Succes"){
				$("userAddMessage").style.color = "#1b1464";
			}else{
				$("userAddMessage").style.color = "#f00";
			}
		})
}

function updateUser(id){
	const password = $("userEditPswd:"+id).value;
	const adminStatus = $("userEditAdmin:"+id).checked ? 1 : 0;

	fetch("API/updateUser.php/?id="+id+"&password="+password+"&admin="+adminStatus)
		.then(result=>result.json())
		.then(json=>{			
			$("userEditResult:"+id).innerHTML = json["Message"];
			if(json["Status"] == "Succes"){
				$("userEditResult:"+id).style.color = "#1b1464";
			}else{
				$("userEditResult:"+id).style.color = "#f00";
			}
		})
}

function removeUser(id){
	fetch("API/removeUser.php/?id="+id)
		.then(result=>result.json())
		.then(json=>{			
			$("userEditResult:"+id).innerHTML = json["Message"];
			if(json["Status"] == "Succes"){
				$("userEditResult:"+id).style.color = "#1b1464";
			}else{
				$("userEditResult:"+id).style.color = "#f00";
			}
		})
}