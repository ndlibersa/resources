<?php

/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.0
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/


session_start();

include_once 'directory.php';

//print header
$pageTitle='Home';
include 'templates/header.php';

//used for creating a "sticky form" for back buttons
//except we don't want it to retain if they press the 'index' button
//check what referring script is

if ($_SESSION['ref_script'] != "resource.php"){
	$reset='Y';
}

$_SESSION['ref_script']=$currentPage;

?>

<div style='text-align:left;'>
<table class="headerTable" style="background-image:url('images/header.gif');background-repeat:no-repeat;">
<tr style='vertical-align:top;'>
<td style="width:155px;padding-right:10px;">
	<table class='noBorder'>
	<tr><td style='text-align:left;width:75px;' align='left'>
	<span style='font-size:130%;font-weight:bold;'>Search</span><br />
	<a href='javascript:void(0)' class='newSearch'>new search</a>
	</td>
	<td><div id='div_feedback'>&nbsp;</div>
	</td></tr>
	</table>

	<table class='borderedFormTable' style="width:150px">

	<tr>
	<td class='searchRow'><label for='searchResourceTitle'><b>Name (contains)</b></label>
	<br />
	<input type='text' name='searchName' id='searchName' style='width:145px' value='<?php if ($reset != 'Y') echo $_SESSION['res_name']; ?>' /><br />
	<div id='div_searchName' style='<?php if ((!$_SESSION['res_name']) || ($reset == 'Y')) echo "display:none;"; ?>margin-left:123px;'><input type='button' name='btn_searchName' value='go!' class='searchButton' /></div>
	</td>
	</tr>



	<tr>
	<td class='searchRow'><label for='searchResourceISBNOrISSN'><b>ISBN/ISSN</b></label>
	<br />
	<input type='text' name='searchResourceISBNOrISSN' id='searchResourceISBNOrISSN' style='width:145px' value='<?php if ($reset != 'Y') echo $_SESSION['res_resourceISBNOrISSN']; ?>' /><br />
	<div id='div_searchISBNOrISSN' style='<?php if ((!$_SESSION['res_resourceISBNOrISSN']) || ($reset == 'Y')) echo "display:none;"; ?>margin-left:123px;'><input type='button' name='btn_searchResourceISBNOrISSN' value='go!' class='searchButton' /></div>
	</td>
	</tr>



	<tr>
	<td class='searchRow'><label for='searchFund'><b>Fund</b></label>
	<br />
	<input type='text' name='searchFund' id='searchFund' style='width:145px' value='<?php if ($reset != 'Y') echo $_SESSION['res_fund']; ?>' /><br />
	<div id='div_searchFund' style='<?php if ((!$_SESSION['res_fund']) || ($reset == 'Y')) echo "display:none;"; ?>margin-left:123px;'><input type='button' name='btn_searchFund' value='go!' class='searchButton' /></div>
	</td>
	</tr>



	<tr>
	<td class='searchRow'><label for='searchAcquisitionTypeID'><b>Acquisition Type</b></label>
	<br />
	<select name='searchAcquisitionTypeID' id='searchAcquisitionTypeID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$acquisitionType = new AcquisitionType();

		foreach($acquisitionType->allAsArray() as $display) {
			if (($_SESSION['res_acquisitionTypeID'] == $display['acquisitionTypeID']) && ($reset != 'Y')){
				echo "<option value='" . $display['acquisitionTypeID'] . "' selected>" . $display['shortName'] . "</option>";
			}else{
				echo "<option value='" . $display['acquisitionTypeID'] . "'>" . $display['shortName'] . "</option>";
			}
		}

	?>
	</select>
	</td>
	</tr>


	<tr>
	<td class='searchRow'><label for='searchStatusID'><b>Status</b></label>
	<br />
	<select name='searchStatusID' id='searchStatusID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$status = new Status();

		foreach($status->allAsArray() as $display) {
			//exclude saved status
			if (strtoupper($display['shortName']) != 'SAVED'){
				if (($_SESSION['res_statusID'] == $display['statusID']) && ($reset != 'Y')){
					echo "<option value='" . $display['statusID'] . "' selected>" . $display['shortName'] . "</option>";
				}else{
					echo "<option value='" . $display['statusID'] . "'>" . $display['shortName'] . "</option>";
				}
			}
		}

	?>
	</select>
	</td>
	</tr>






	<tr>
	<td class='searchRow'><label for='searchCreatorLoginID'><b>Creator</b></label>
	<br />
	<select name='searchCreatorLoginID' id='searchCreatorLoginID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$resource = new Resource();

		foreach($resource->getCreatorsArray() as $display) {
			if ($display['firstName']){
				$name = $display['lastName'] . ", " . $display['firstName'];
			}else{
				$name = $display['loginID'];
			}

			if (($_SESSION['res_creatorLoginID'] == $display['loginID']) && ($reset != 'Y')){
				echo "<option value='" . $display['loginID'] . "' selected>" . $name . "</option>";
			}else{
				echo "<option value='" . $display['loginID'] . "'>" . $name . "</option>";
			}
		}

	?>
	</select>
	</td>
	</tr>



	<tr>
	<td class='searchRow'><label for='searchResourceFormatID'><b>Resource Format</b></label>
	<br />
	<select name='searchResourceFormatID' id='searchResourceFormatID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$resourceFormat = new ResourceFormat();

		foreach($resourceFormat->allAsArray() as $display) {
			if (($_SESSION['res_resourceFormatID'] == $display['resourceFormatID']) && ($reset != 'Y')){
				echo "<option value='" . $display['resourceFormatID'] . "' selected>" . $display['shortName'] . "</option>";
			}else{
				echo "<option value='" . $display['resourceFormatID'] . "'>" . $display['shortName'] . "</option>";
			}
		}

	?>
	</select>
	</td>
	</tr>


	<tr>
	<td class='searchRow'><label for='searchResourceTypeID'><b>Resource Type</b></label>
	<br />
	<select name='searchResourceTypeID' id='searchResourceTypeID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$resourceType = new ResourceType();

		foreach($resourceType->allAsArray() as $display) {
			if (($_SESSION['res_resourceTypeID'] == $display['resourceTypeID']) && ($reset != 'Y')){
				echo "<option value='" . $display['resourceTypeID'] . "' selected>" . $display['shortName'] . "</option>";
			}else{
				echo "<option value='" . $display['resourceTypeID'] . "'>" . $display['shortName'] . "</option>";
			}
		}

	?>
	</select>
	</td>
	</tr>


	<tr>
	<td class='searchRow'><label for='searchResourceID'><b>Record ID</b></label>
	<br />
	<input type='text' name='searchResourceID' id='searchResourceID' style='width:145px' value='<?php if ($reset != 'Y') echo $_SESSION['res_resourceID']; ?>' /><br />
	<div id='div_searchID' style='<?php if ((!$_SESSION['res_resourceID']) || ($reset == 'Y')) echo "display:none;"; ?>margin-left:123px;'><input type='button' value='go!' id='searchResourceIDButton' /></div>
	</td>
	</tr>


	<tr>
	<td class='searchRow'><label for='searchFirstLetter'><b>Starts with</b></label>
	<br />
	<?php
	$resource = new Resource();

	$alphArray = range('A','Z');
	$resAlphArray = $resource->getAlphabeticalList;

	foreach ($alphArray as $letter){
		if ((isset($resAlphArray[$letter])) && ($resAlphArray[$letter] > 0)){
			echo "<span class='searchLetter' id='span_letter_" . $letter . "'><a href='javascript:setStartWith(\"" . $letter . "\")'>" . $letter . "</a></span>";
			if ($letter == "N") echo "<br />";
		}else{
			echo "<span class='searchLetter'>" . $letter . "</span>";
			if ($letter == "N") echo "<br />";
		}
	}


	?>
	<br />
	</td>
	</tr>

	</table>

	<div id='hideShowOptions'><a href='javascript:void(0);' name='showMoreOptions' id='showMoreOptions'>more options...</a></div>
	<div id='div_additionalSearch' style='display:none;'>
	<table class='borderedFormTable' style="width:150px">

	<tr>
	<td class='searchRow'><label for='searchNoteTypeID'><b>Note Type</b></label>
	<br />
	<select name='searchNoteTypeID' id='searchNoteTypeID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$noteType = new NoteType();

		foreach($noteType->allAsArray() as $display) {
			if (($_SESSION['res_noteTypeID'] == $display['noteTypeID']) && ($reset != 'Y')){
				echo "<option value='" . $display['noteTypeID'] . "' selected>" . $display['shortName'] . "</option>";
			}else{
				echo "<option value='" . $display['noteTypeID'] . "'>" . $display['shortName'] . "</option>";
			}
		}

	?>
	</select>
	</td>
	</tr>


	<tr>
	<td class='searchRow'><label for='searchResourceNote'><b>Note (contains)</b></label>
	<br />
	<input type='text' name='searchResourceNote' id='searchResourceNote' style='width:145px' value='<?php if ($reset != 'Y') echo $_SESSION['res_resourceNote']; ?>' /><br />
	<div id='div_searchResourceNote' style='<?php if ((!$_SESSION['res_resourceNote']) || ($reset == 'Y')) echo "display:none;"; ?>margin-left:123px;'><input type='button' name='btn_searchResourceNote' value='go!' class='searchButton' /></div>
	</td>
	</tr>




	<tr>
	<td class='searchRow'><label for='createDate'><b>Date Created Between</b></label><br />
	<input class='date-pick' name='searchCreateDateStart' id='searchCreateDateStart' style='width:65px' value='<?php if ($reset != 'Y') echo $_SESSION['res_createDateStart']; ?>' />&nbsp;&nbsp;<b>and</b>
	</td>
	</tr>
	<tr>
	<td style="border-top:0px;padding-top:0px;">
	<input class='date-pick' name='searchCreateDateEnd' id='searchCreateDateEnd' style='width:65px' value='<?php if ($reset != 'Y') echo $_SESSION['res_createDateEnd']; ?>' /><br />
	<div id='div_searchCreateDate' style='display:none;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' class='searchButton' value='go!' /></div>
	</td>
	</tr>



	<tr>
	<td class='searchRow'><label for='searchPurchaseSiteID'><b>Purchase Site</b></label>
	<br />
	<select name='searchPurchaseSiteID' id='searchPurchaseSiteID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$purchaseSite = new PurchaseSite();

		foreach($purchaseSite->allAsArray() as $display) {
			if (($_SESSION['res_purchaseSiteID'] == $display['purchaseSiteID']) && ($reset != 'Y')){
				echo "<option value='" . $display['purchaseSiteID'] . "' selected>" . $display['shortName'] . "</option>";
			}else{
				echo "<option value='" . $display['purchaseSiteID'] . "'>" . $display['shortName'] . "</option>";
			}
		}

	?>
	</select>
	</td>
	</tr>




	<tr>
	<td class='searchRow'><label for='searchAuthorizedSiteID'><b>Authorized Site</b></label>
	<br />
	<select name='searchAuthorizedSiteID' id='searchAuthorizedSiteID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$authorizedSite = new AuthorizedSite();

		foreach($authorizedSite->allAsArray() as $display) {
			if (($_SESSION['res_authorizedSiteID'] == $display['authorizedSiteID']) && ($reset != 'Y')){
				echo "<option value='" . $display['authorizedSiteID'] . "' selected>" . $display['shortName'] . "</option>";
			}else{
				echo "<option value='" . $display['authorizedSiteID'] . "'>" . $display['shortName'] . "</option>";
			}
		}

	?>
	</select>
	</td>
	</tr>




	<tr>
	<td class='searchRow'><label for='searchAdministeringSiteID'><b>Administering Site</b></label>
	<br />
	<select name='searchAdministeringSiteID' id='searchAdministeringSiteID' style='width:150px' onchange='javsacript:updateSearch();'>
	<option value=''>All</option>
	<?php

		$display = array();
		$administeringSite = new AdministeringSite();

		foreach($administeringSite->allAsArray() as $display) {
			if (($_SESSION['res_administeringSiteID'] == $display['administeringSiteID']) && ($reset != 'Y')){
				echo "<option value='" . $display['administeringSiteID'] . "' selected>" . $display['shortName'] . "</option>";
			}else{
				echo "<option value='" . $display['administeringSiteID'] . "'>" . $display['shortName'] . "</option>";
			}
		}

	?>
	</select>
	</td>
	</tr>




	</table>
	</div>

	</div>
</td>
<td>
<div id='div_searchResults'></div>
</td></tr>
</table>
</div>
<br />
<script type="text/javascript" src="js/index.js"></script>
<script type='text/javascript'>
<?php
  //used to default to previously selected values when back button is pressed
  //if the startWith is defined set it so that it will default to the first letter picked
  if ((isset($_SESSION['res_startWith'])) && ($reset != 'Y')){
	  echo "startWith = '" . $_SESSION['res_startWith'] . "';";
	  echo "$(\"#span_letter_" . $_SESSION['res_startWith'] . "\").removeClass('searchLetter').addClass('searchLetterSelected');";
  }

  if ((isset($_SESSION['res_pageStart'])) && ($reset != 'Y')){
	  echo "pageStart = '" . $_SESSION['res_pageStart'] . "';";
  }

  if ((isset($_SESSION['res_recordsPerPage'])) && ($reset != 'Y')){
	  echo "recordsPerPage = '" . $_SESSION['res_recordsPerPage'] . "';";
  }

  if ((isset($_SESSION['res_orderBy'])) && ($reset != 'Y')){
	  echo "orderBy = \"" . $_SESSION['res_orderBy'] . "\";";
  }

  echo "</script>";

  //print footer
  include 'templates/footer.php';
?>