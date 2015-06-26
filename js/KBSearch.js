$(document).ready(function(){
	console.debug("kbsearch.js is ready");
});

function allResults(s_name, s_pub, s_type){
	$.ajax({
		 type:       "POST",
		 url:        "ajax_forms.php?action=getKBSearchResults&height=503&width=775&resourceID=&modal=true",
		 cache:      false,
		 data:       {name:s_name, issn:'', publisher:s_pub, type:s_type},
		 success:    function(res) {
			 document.getElementById("TB_ajaxContent").innerHTML = "";
		 	$('#TB_ajaxContent').append(res);
		 }
	});
}

function getDetails(s_type, s_gokbID){
	console.debug("getDetails !");
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