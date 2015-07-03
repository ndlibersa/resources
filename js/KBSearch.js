
function allResults(s_name, s_pub, s_type){
	$.ajax({
		 type:       "POST",
		 url:        "ajax_forms.php?action=getKBSearchResults&height=503&width=775&resourceID=&modal=true",
		 cache:      false,
		 data:       {name:s_name, issn:'', publisher:s_pub, type:s_type, paginate:true},
		 success:    function(res) {
			document.getElementById("TB_ajaxContent").innerHTML = "";
		 	$('#TB_ajaxContent').append(res);
		 }
	});
}

function getDetails(s_type, s_gokbID){
	$.ajax({
			 type:       "POST",
			 url:        "ajax_htmldata.php?action=getGokbResourceDetails&height=503&width=775&resourceID=&modal=true",
			 cache:      false,
			 data:       {type:s_type, id:s_gokbID},
			 success:    function(res) {
				 document.getElementById("TB_ajaxContent").innerHTML = "";
			 	$('#TB_ajaxContent').append(res);
			 }
	});

}

function loadDetailsContent(element_nb){
	
	var tabs=document.getElementById("detailsTabs").getElementsByTagName("li");
	var divs = document.getElementById("detailsContainer").getElementsByTagName("div");

	for (var i = 0; i < tabs.length; i++) {
		if(i == element_nb){
			tabs[i].className="selected";
			divs[i].className="";
		}else{
			tabs[i].className="";
			divs[i].className="invisible";
		}

	}
	
}

function iterator(page){
	//initialisation
	var divId = $("#currentDiv").val();
	var old = parseInt($("#currentPage").val());
	var pagination = document.getElementById("pageIterator").getElementsByTagName("li");
	
	var lines = document.getElementById(divId).getElementsByTagName("tr");
	var nbTipps = lines.length;
	var nbPages = Math.ceil(nbTipps/10);
	
	//updating current page
	document.getElementById('currentPage').value = page;
	pagination[old+1].className = "";
	pagination[page+1].className = "active";


	var itStart= page*10;
	var stop = itStart+10;

	//Display/hide prev and next buttons
	if (itStart == 0) { document.getElementById('previousTipps').className='invisible';	}
	else { document.getElementById('previousTipps').className=''; }

	if (page == (nbPages-1)){ document.getElementById('nextTipps').className='invisible'; }
	else { document.getElementById('nextTipps').className=''; }

	if (stop >= nbTipps) { stop=nbTipps; }


	//hide previous page
	var disableStart = old*10;
	var disableStop = (old+1)*10;
	if (disableStop > nbTipps) disableStop=nbTipps;

	console.debug("disable lines "+disableStart+" to "+disableStop);
	for (var i = disableStart; i < disableStop; i++) {
		lines[i].className="invisible";
	};

	//display current page
	console.debug("display lines "+itStart+" to "+stop);
	for (var i=itStart; i<stop; i++){
		lines[i].className="";
	}

	//Display up to 15 pages number, if there are more --> truncate
	if (nbPages > 15){

	}


}

function navIterator(op){
	var current = parseInt($("#currentPage").val());
	var param = 0;

	switch(op){
		case '+':
			param = current+1;
			break;
		case '-':
			param = current-1;
			break;
		default:
			break;
	}

	iterator(param);
}