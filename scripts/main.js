function $(id){ return document.getElementById(id);}

function loadLeaf(id){
	const triangleSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M10 17l5-5-5-5v10z"/><path d="M0 24V0h24v24H0z" fill="none"/></svg>';
	const dotSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="m12,8c-2.21,0 -4,1.79 -4,4s1.79,4 4,4s4,-1.79 4,-4s-1.79,-4 -4,-4z"/></svg>';
	event.stopPropagation();
	fetch("API/getTranslation.php/?id="+id)
		.then(result=>result.json())
		.then(json=>{
			// navigator
			const leafCollDOM = $('leafCollection:'+id);
			if(!leafCollDOM.hidden){
				leafCollDOM.hidden = true;
				leafCollDOM.innerHTML = "";
				leafCollDOM.parentNode.getElementsByTagName("svg")[0].style="transform: rotate(0deg);";
				return;
			}
			leafCollDOM.parentNode.getElementsByTagName("svg")[0].style="transform: rotate(90deg);";

			if(json["childs"].length != 0){

				leafCollDOM.hidden = false;

				for(let childId in json["childs"]){
					const node = document.createElement("div");
					node.setAttribute("class", "treeBlock");
					node.setAttribute("onclick", "loadLeaf("+childId+")");
					node.setAttribute("id", "leaf:"+childId);

					const textNode = document.createElement("div");
					textNode.setAttribute("class", "treeHeader");
					if(json["parent"][childId])
						textNode.innerHTML=triangleSVG;
					else
						textNode.innerHTML=dotSVG;
					textNode.innerHTML+=json["childs"][childId];
					node.appendChild(textNode);

					const collectionNode = document.createElement("div");
					collectionNode.setAttribute("class", "treeCollection");
					collectionNode.setAttribute("id", "leafCollection:"+childId);
					collectionNode.setAttribute("hidden", true);
					node.appendChild(collectionNode);

					leafCollDOM.appendChild(node);
				}
			}


			history.pushState({word: json["en"].id}, "", "?word="+json["en"].id)
			

			// translation
			$("wordID").innerText = "ID: " + json["en"].id;
			$("wordLastUpdate").innerText = "last update: "; // todo
			
			$("en:label").value = json["en"].label;
			$("en:definition").value = json["en"].definition;
			$("en:scope").value = json["en"].scope;
			
			$("cs:label").value = json["cs"].label;
			$("cs:definition").value = json["cs"].definition;
			$("cs:scope").value = json["cs"].scope;		
		});
}

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
