<?php
	$alertDaysInAdvanceID = $_GET['alertDaysInAdvanceID'];

	if ($alertDaysInAdvanceID){
		$instance = new AlertDaysInAdvance(new NamedArguments(array('primaryKey' => $alertDaysInAdvanceID)));
	}else{
		$instance = new AlertDaysInAdvance();
	}
?>
		<div id='div_updateForm'>

		<input type='hidden' id='editAlertDaysInAdvanceID' value='<?php echo $alertDaysInAdvanceID; ?>'>

		<div class='formTitle' style='width:245px;'><span class='headerText' style='margin-left:7px;'><?php if ($alertDaysInAdvanceID){ echo _("Edit Alert Days In Advance"); } else { echo _("Add Alert Days In Advance"); } ?></span></div>

		<span class='smallDarkRedText' id='span_errors'></span>

		<table class="surroundBox" style="width:250px;">
		<tr>
		<td>

			<table class='noBorder' style='width:200px; margin:10px;'>
			<td><input type='text' id='daysInAdvanceNumber' value='<?php echo $instance->daysInAdvanceNumber; ?>' style='width:190px;'/></td>
			</td>
			</tr>
			</table>

		</td>
		</tr>
		</table>
		<div class='smallDarkRedText' id='div_form_error'>&nbsp;</div>
		<table class='noBorderTable' style='width:125px;'>
			<tr>
				<td style='text-align:left'><input type='button' value='<?php echo _("submit");?>' id ='submitAddUpdate' class='submit-button'></td>
				<td style='text-align:right'><input type='button' value='<?php echo _("cancel");?>' onclick="window.parent.tb_remove(); return false;" class='cancel-button'></td>
			</tr>
		</table>


		</form>
		</div>

		<script type="text/javascript">
		   //attach enter key event to new input and call add data when hit
		   $('#daysInAdvanceNumber').keyup(function(e) {
				   if(e.keyCode == 13) {
					   window.parent.submitAdminAlertDays();
				   }
		});


		   $('#submitAddUpdate').click(function () {
			       window.parent.submitAdminAlertDays();
		   });


	</script>

