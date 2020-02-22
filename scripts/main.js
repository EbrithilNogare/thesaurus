// todo change it for css tooltip

document.addEventListener('DOMContentLoaded', function() {
	var elems = document.querySelectorAll('.tooltipped');
	var instances = M.Tooltip.init(elems, {enterDelay:1000});
});

function $(id){ return document.getElementById(id);}

function loadLeaf(id){
	event.stopPropagation();
	fetch("API/getTranslation.php/?id="+id)
		.then(result=>result.json())
		.then(json=>{
			// navigator
			const leafCollDOM = $('leafCollection:'+id);
			if(!leafCollDOM.hidden){
				leafCollDOM.hidden = true;
				leafCollDOM.innerHTML = "";
				return;
			}

			if(json["childs"].length != 0){

				leafCollDOM.hidden = false;

				for(let childId in json["childs"]){
					const node = document.createElement("div");
					node.setAttribute("class", "collection-item tooltipped");
					node.setAttribute("data-position", "left");
					node.setAttribute("data-tooltip", "id:"+childId);
					node.setAttribute("onclick", "loadLeaf("+childId+")");
					node.setAttribute("id", "leaf:"+childId);

					const textNode = document.createElement("span");
					textNode.appendChild(document.createTextNode(json["childs"][childId]));
					node.appendChild(textNode);

					const collectionNode = document.createElement("div");
					collectionNode.setAttribute("class", "collection");
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


