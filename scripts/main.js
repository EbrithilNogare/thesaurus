// todo change it for css tooltip

document.addEventListener('DOMContentLoaded', function() {
	var elems = document.querySelectorAll('.tooltipped');
	var instances = M.Tooltip.init(elems, {enterDelay:1000});
});

function $(id){ return document.getElementById(id);}

function loadLeaf(id){
	const triangleSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M10 17l5-5-5-5v10z"/><path d="M0 24V0h24v24H0z" fill="none"/></svg>';
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
					textNode.innerHTML=triangleSVG;
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


			

			// translation
			$("wordID").innerText = json["en"].id;
			
			$("en:label").innerText = json["en"].label;
			$("en:definition").innerText = json["en"].definition;
			$("en:scope").innerText = json["en"].scope;
			
			$("cs:label").innerText = json["cs"].label;
			$("cs:definition").innerText = json["cs"].definition;
			$("cs:scope").innerText = json["cs"].scope;		
		});
}


