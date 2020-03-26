function $(id){ return document.getElementById(id);}

const triangleSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M10 17l5-5-5-5v10z"/><path d="M0 24V0h24v24H0z" fill="none"/></svg>';
const dotSVG = '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="m12,8c-2.21,0 -4,1.79 -4,4s1.79,4 4,4s4,-1.79 4,-4s-1.79,-4 -4,-4z"/></svg>';

const Thesaurus = {
	id: 0,
	parent: {
		id:0,
		label:"",
	},
	lastUpdate: "",
	original:{
		language: "en",
		label: "",
		definition: "",
		scope: "",
	},
	translation:{
		language: "cs",
		label: "",
		definition: "",
		scope: "",
	},
	childs:[],
	updateTranslationView(){
		history.pushState({word: this.id}, "", "?word="+this.id);
		
		$("wordID").innerText = "ID: " + this.id;

		$("wordParentId").value = this.parent.id;
		$("wordParentLabel").innerText = this.parent.label;

		$("wordLastUpdate").innerText = "last update: " + this.lastUpdate;
		
		$("en:label").value = this.original.label;
		$("en:definition").value = this.original.definition;
		$("en:scope").value = this.original.scope;
		
		$("cs:label").value = this.translation.label;
		$("cs:definition").value = this.translation.definition;
		$("cs:scope").value = this.translation.scope;	
	},
	updateTreeView(){
		const leafCollDOM = $(`leafCollection:${this.id}`);

		if(leafCollDOM == null)
			return;

		leafCollDOM.hidden = !leafCollDOM.hidden;
		if (leafCollDOM.hidden) {
			leafCollDOM.parentNode.getElementsByTagName("svg")[0].style = "transform: rotate(0deg);";
			return;
		}
		
		leafCollDOM.parentNode.getElementsByTagName("svg")[0].style = "transform: rotate(90deg);";

		for(let childId in this.childs){
			const node = document.createElement("div");
			node.setAttribute("class", "treeBlock");
			node.setAttribute("onclick", `loadLeaf(${childId})`);
			node.setAttribute("id", `leaf:${childId}`);

			const headerNode = document.createElement("div");
			headerNode.setAttribute("class", "treeHeader");
			if(this.childs[childId].parent)
				headerNode.innerHTML = triangleSVG;
			else
				headerNode.innerHTML = dotSVG;
			node.appendChild(headerNode);

			const textNode = document.createElement("span");
			textNode.innerText += this.childs[childId].title;
			headerNode.appendChild(textNode);
			
			if(this.childs[childId].parent){
				const collectionNode = document.createElement("div");
				collectionNode.setAttribute("class", "treeCollection");
				collectionNode.setAttribute("id", `leafCollection:${childId}`);
				collectionNode.setAttribute("hidden", true);
				node.appendChild(collectionNode);
			}

			leafCollDOM.appendChild(node);
		}
	}
}

function loadLeaf(id){
	event.stopPropagation();

	if(Thesaurus.id == id){
		Thesaurus.updateTreeView();
		return;
	}

	fetch("API/getTranslation.php/?id="+id)
		.then(result=>result.json())
		.then(json=>{
			console.log(json);
			Thesaurus.id = json["id"];	
			Thesaurus.parent.id = json["parentId"];		
			Thesaurus.parent.label = json["parentLabel"];			
			Thesaurus.lastUpdate = json["lastUpdate"];
			
			Thesaurus.childs = [];
			if(json["childs"].length != 0){
				for(let childId in json["childs"]){
					Thesaurus.childs[childId] = {
						title: json["childs"][childId],
						parent: json["parent"][childId],
					}
				}
			}
			
			Thesaurus.original.label = json["en"].label;
			Thesaurus.original.definition = json["en"].definition;
			Thesaurus.original.scope = json["en"].scope;
			
			Thesaurus.translation.label = json["cs"].label;
			Thesaurus.translation.definition = json["cs"].definition;
			Thesaurus.translation.scope = json["cs"].scope;	
	
			Thesaurus.updateTreeView();
			Thesaurus.updateTranslationView();
		})
		.catch(error => console.error(`loadLeaf => ${error.message}`));
}

function updateTranslation(){
	if(Thesaurus.id == 0)
		return;

	const postBody = {
		id: Thesaurus.id,
		parent: $("wordParentId").value,
		original: {
			lang: Thesaurus.original.language,
			label: $("en:label").value,
			definition: $("en:definition").value,
			scope: $("en:scope").value,
		},
		translation: {
			lang: Thesaurus.translation.language,
			label: $("cs:label").value,
			definition: $("cs:definition").value,
			scope: $("cs:scope").value,
		},
	}
	
	fetch('API/updateTranslation.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(postBody),
	})
}

function changeParent(){
	let newID = prompt("write ID of new parent", $("wordParentId").value);
	if(newID == null)
		return;
	
	fetch("API/getTranslation.php/?id="+newID)
		.then(result=>result.json())
		.then(json=>{
			$("wordParentId").value = json["id"];
			$("wordParentLabel").innerText = json["en"].label;
		})
		.catch(error => console.error(`loadLeaf => ${error.message}`));
}

function showLastUpdates(){
	
}