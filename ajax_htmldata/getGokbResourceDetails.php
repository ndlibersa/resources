<script type="text/javascript" src="js/plugins/thickbox.js"></script>
<script type="text/javascript" src="js/KBSearch.js"></script>


<div id='resourceDetails'>
	<div class='formTitle' style='width:745px;'>
		<span class='headerText'>Resource details</span>
	</div>

<?php

include_once $_SERVER['DOCUMENT_ROOT']."resources/admin/classes/domain/GOKbTools.php";
include_once $_SERVER['DOCUMENT_ROOT']."resources/ajax_htmldata/getPagination.php";

$tool = GOKbTools::getInstance();
$record = $tool->getDetails($_POST['type'], $_POST['id']);
$nbTipps = $tool->getNbTipps($record);

 ?>

	<div id="resourceName">
		<span id="resName">
			<?php echo $tool->getResourceName($record);?>
		</span>
		<span id="resType">
			(<?php echo $_POST['type'];?>)
		</span>
	</div>

	<div id="detailsTabs">
	    <ul>
	        <li id='globalDetails' class='selected' onclick="loadDetailsContent(0);">Details</li> 
	        <li id='tippsDetails' onclick="loadDetailsContent(1); iterator(0);">TIPPs (<?php echo $nbTipps;?>)</li> 
	    </ul>
	</div>

	<div id="detailsContainer">
		<div id="globalDet">
			<?php echo $tool->displayRecord($record); ?>
		</div>
		<div id="tippsDet" class='invisible'>
			<?php 	echo $tool->displayRecordTipps($record, $_POST['type']);
					echo paginate($nbTipps, "tippsDet");  ?>
		</div>
	</div>

	<div class="search_nav_button">
		<a id="backToNewResourceForm" class="thickbox" href="ajax_forms.php?action=getNewResourceForm&height=503&width=775&resourceID=&modal=true">
		<span class='linkStyle'>Back</span></a>

		<input type='button' value='cancel' onclick="tb_remove();">
	</div>

</div>