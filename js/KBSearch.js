
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
	//var nbPages = Math.ceil(nbTipps/10);
	var nbPages = pagination.length - 4;
	
	//updating current page
	document.getElementById('currentPage').value = page;
	//pagination[old+2].className = "";
	


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
	}

	//display current page
	console.debug("display lines "+itStart+" to "+stop);
	for (var i=itStart; i<stop; i++){
		lines[i].className="";
	}

	//Display up to 13 pages number, if there are more --> truncate
	if (nbPages > 13){
		//case 1: current page is at the beginning
		if (page < 7){
			document.getElementById("beginning").className = "invisible";
			document.getElementById("end").className = "";
			for (var i=0; i<nbPages; i++){
				if (i<13) pagination[i+2].className = '';
				else pagination[i+2].className ='invisible';
			}
		}
		//case 2: current page is at the end
		else if(page >nbPages-6) {
			document.getElementById("beginning").className = "";
			document.getElementById("end").className = "invisible";
			for (var i = 0; i<nbPages; i++) {
				if(i>nbPages-14) pagination[i+2].className = "";
				else pagination[i+2].className='invisible';
			}
		}
		//case 3: current page is in the middle
		else {
			document.getElementById("beginning").className = "";
			document.getElementById("end").className = "";
			for (var i = 0; i<nbPages; i++) {
				if((i >= page-6) && (i <= page+6)) pagination[i+2].className = "";
				else pagination[i+2].className='invisible';
			}
		}
	}
	pagination[page+2].className = "active";

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