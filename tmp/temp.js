index
javascript:TreeView_ToggleNode(VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1_Data,0,document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n0'),' ',document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n0Nodes'))

+1
javascript:TreeView_ToggleNode(VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1_Data,1,document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n1'),' ',document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n1Nodes'))
javascript:VhiIndexingTermTreeviewVhiTreeviewSelectNode('-1\\16575', '(PIQ) Index terms (CONTAINER ONLY)', '16575', '-1');

+2
javascript:TreeView_ToggleNode(VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1_Data,141,document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n141'),' ',document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n141Nodes'))
javascript:VhiIndexingTermTreeviewVhiTreeviewSelectNode('16575\\16734', '(PIQ) ALIAS TYPES', '16734', '16575');

+3
...
javascript:VhiIndexingTermTreeviewVhiTreeviewSelectNode('16734\\16728', '(PIQ) current name', '16728', '16734');



report regexps:
", =>,
,"=>,
""=>"
( )+=> 
 \.=>.





general

TreeView_PopulateNode(
	VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1_Data,
	0,
	document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n'+2),
	null,
	null,
	' ',
	'',
	'-1\\14674',
	'',
	'',
	''
)	

VhiIndexingTermTreeviewVhiTreeviewSelectNode('','', '16575','');


TreeView_ToggleNode(
	VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1_Data,
	141,
	document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n141'),
	' ',
	document.getElementById('VhiControlHost1_VhiIndexingTermTreeview_VhiTree1_TreeView1n141Nodes')
)






document.getElementById("VhiDivScrollVhiIndexingTermTreeview").style.visibility = "hidden"
lastSend = [];
interval = setInterval(()=>{
    for(let img of document.getElementsByTagName("img")){
        if(img.alt == "Collapse "){
			img.parentNode.removeChild(img)
			continue;
		}
        if(img.src != "https://vhi-indexing.usc.edu/VhiTM/WebResource.axd?d=XK7SGpNYRR60OdnciTTnReV27PB6owIH6wOnzUGhPVMaKPD3YlNDqq2Ieyb9FNx0_g2bYRvbjGwrZTIxo0UYqYDOa8q5jk49U1IwFHXnVSmatTmHkGu7wzKDFYab9Kf70&t=637100518445053551"){
			img.parentNode.removeChild(img)
			continue;
		}
        let toEval = img.parentNode.href.split("javascript:")[1];
        if(lastSend.indexOf(toEval)!=-1) continue;
        lastSend.push(toEval)
		eval(toEval)
		if(Math.random()<.01)
			console.log(document.getElementsByTagName("table").length)
		return;
    }
    console.log("canceling interval")
    document.getElementById("VhiDivScrollVhiIndexingTermTreeview").style.visibility = "visible"
	clearInterval(interval)
},600)
-----------------------
clearInterval(interval)






var data = []
for(let tag of document.getElementsByTagName("a")){
    if(!tag.href.includes("javascript:VhiIndexingTermTreeviewVhiTreeviewSelectNode")) continue;
    let jsCode = tag.href.split("javascript:")[1].split("'")
    data[data.length] = {
        "name": jsCode[3],
        "parent": jsCode[7], 
        "current": jsCode[5],
    }
}
console.log(data)