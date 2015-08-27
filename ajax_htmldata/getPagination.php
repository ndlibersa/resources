<?php
/**
* Provide pagination to display results or details _ nav managed by js function "iterator(page)"
* @param $nbEntry 	int 	number of items to display
* @param $nbPerPage int 	number of items to display on each page (optional)
* @return 			string  html content to insert
*/
function paginate($nbEntry, $divId, $nbPerPage=10){
	//initialisation
	$pagesCount = ceil($nbEntry/$nbPerPage); //number of pages
	$content = "";//string to fill with html content

	$content .= "<div id='pageIterator'>
					<input id='currentPage' type='hidden' value='0'/>
					<input id='currentDiv' type='hidden' value='".$divId."'/>
					<ul>
						<li id='previousTipps' onclick=\"navIterator('-');\">prev</li>";
	$content .=			"<li id='beginning' onclick=\"iterator(0);\" class='invisible'>&hellip;</li>";

	for ($i=0; $i < $pagesCount ; $i++) { 
		$content .= 	"<li onclick='iterator(".$i.");'>".($i+1)."</li>";
	}

	$content .=			"<li id='end' onclick=\"iterator($pagesCount-1);\" class='invisible'>&hellip;</li>";
	$content .= 		"<li id='nextTipps' onclick=\"navIterator('+');\">next</li>
					</ul>
				</div>";
	return $content;
}

?>
