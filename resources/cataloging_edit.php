<?php
include_once '../directory.php';
include_once '../user.php';

$resourceID = $_GET['resourceID'];
$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

?>
<div id='div_catalogingForm'>
<form id='catalogingForm' method="post" action="resources/cataloging_update.php">
<input type='hidden' name='resourceID' id='resourceID' value='<?php echo $resourceID; ?>'>

<div class='formTitle' style='width:715px; margin-bottom:5px;'><span class='headerText'>Edit Cataloging</span></div>

<span class='smallDarkRedText' id='span_errors'></span>

<table class='noBorder' style='width:100%;'>
<tr style='vertical-align:top;'>
<td style='vertical-align:top;' colspan='2'>


<span class='surroundBoxTitle'>&nbsp;&nbsp;<label for='accessHead'><b>Record Set</b></label>&nbsp;&nbsp;</span>

<table class='surroundBox' style='width:710px;'>
<tr>
<td>
  <?php //debug($resource); ?>
  
	<table class='noBorder' style='width:670px; margin:15px 20px 10px 20px;'>
	<tr>
	<td style="width:400px;">
		<table>
		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='recordSetIdentifier'>Identifier:</label></td>
		<td><?php echo text_field('recordSetIdentifier', $resource, array('width' => '240px')) ?>
		</td>
		</tr>

		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='bibSourceURL'>Source URL:</label></td>
		<td><?php echo text_field('bibSourceURL', $resource, array('width' => '240px')) ?>
		</td>
		</tr>
		
		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='catalogingType'>Cataloging Type:</label></td>
		<td>
		  <?php echo select_field('catalogingType', $resource, array('MARCit','Batch','Manual'), array('width' => '150px')); ?>
		</td>
		</tr>
		
		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='catalogingStatus'>Cataloging Status:</label></td>
		<td>
		  <?php echo select_field('catalogingStatus', $resource, array('Rejected','Ongoing','Completed'), array('width' => '150px')); ?>
		</td>
		</tr>
		
		</table>

	</td>
	<td>
		<table>
    
      <tr>
  		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='numberRecordsAvailable'># Records Available:</label></td>
  		<td>
  		  <?php echo text_field('numberRecordsAvailable', $resource, array('width' => '60px')) ?>
  		</td>
  		</tr>

  		<tr>
  		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='numberRecordsLoaded'># Records Loaded:</label></td>
  		<td>
  		  <?php echo text_field('numberRecordsLoaded', $resource, array('width' => '60px')) ?>
  		</td>
  		</tr>
		
		<tr>
		<td style='vertical-align:top;text-align:left;font-weight:bold;'><label for='hasOclcHoldings'>OCLC Holdings:</label></td>
		<td><input type='checkbox' value="1" id='hasOclcHoldings' name='hasOclcHoldings' <?php if ($resource->hasOclcHoldings) { echo 'checked'; } ?> /></td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>

</td>
</tr>
</table>


<hr style='width:710px;margin:15px 0px 10px 0px;' />

<table class='noBorderTable' style='width:125px;'>
<tr>
	<td style='text-align:left'><input type='submit' value='submit' name='submitCatalogingChanges' id ='submitCatalogingChanges'></td>
	<td style='text-align:right'><input type='button' value='cancel' onclick="kill(); tb_remove();"></td>
</tr>
</table>

</form>
</div>
<script type="text/javascript" src="js/forms/catalogingForm.js?random=<?php echo rand(); ?>"></script>