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

$resourceID = $_GET['resourceID'];
$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
$status = new Status(new NamedArguments(array('primaryKey' => $resource->statusID)));

//used to get default email address for feedback link in the right side panel
$config = new Configuration();

//set this to turn off displaying the title header in header.php
$pageTitle=$resource->titleText;;
include 'templates/header.php';


//set referring page
if ((isset($_GET['ref'])) && ($_GET['ref'] == 'new')){
	$_SESSION['ref_script']="new";
}else{
	$_SESSION['ref_script']=$currentPage;
}


if ($resource->titleText){
	?>
	<input type='hidden' name='resourceID' id='resourceID' value='<?php echo $resourceID; ?>'>

	<table style="background-image:url('images/header.gif');background-repeat:no-repeat;margin:0; padding:0; width:900px;">
	<tr>
	<td style='margin:0;padding:0;text-align:left;'>
		<table style='width:100%; height:35px; margin:0;padding:0;'>
		<tr style='vertical-align:top;'>
		<td>
		<span class="headerText" id='span_resourceName'><?php echo $resource->titleText; ?></span>
		<br />
		</td>
		<td>
			&nbsp;
		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>

	<div style='width:900px;'>
	<div style='float:left; width:597px;vertical-align:top;margin:0; padding:0;'>
		<?php if (!isset($_GET['showTab'])){ ?>
		<div style="width: 597px;" id ='div_product'>
		<?php } else { ?>
		<div style="display:none;width: 597px;" id ='div_product'>
		<?php } ?>
			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tr>
					<td class="sidemenu">
						<div class="sidemenuselected" style='position: relative; width: 105px'><span class='icon' id='icon_product'><img src='images/butterflyfishicon.jpg'></span><span class='link'>Product</span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/acquisitions_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAcquisitions'>Acquisitions</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/key_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccess'>Access</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/contacts_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showContacts'>Contacts</a></span></div>
						<?php if ($user->accountTabIndicator == '1') { ?><div class='sidemenuunselected'><span class='icon'><img src='images/lock_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccounts'>Accounts</a></span></div><?php } ?>
						<div class='sidemenuunselected'><span class='icon'><img src='images/attachment_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAttachments'>Attachments</a></span><span class='span_AttachmentNumber smallGreyText' style='clear:right; margin-left:18px;'></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/routing_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showRouting'>Routing</a></span></div>
					</td>
					<td class='mainContent'>

						<div class='div_mainContent'>
						</div>
					</td>
				</tr>
			</table>

		</div>

		<?php if ((isset($_GET['showTab'])) && ($_GET['showTab'] == 'acquisitions')){ ?>
		<div style="width: 597px;" id ='div_acquisitions'>
		<?php } else { ?>
		<div style="display:none;width: 597px;" id ='div_acquisitions'>
		<?php } ?>
			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tr>
					<td class="sidemenu">
						<div class='sidemenuunselected'><span class='icon'><img src='images/butterflyfishicon_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showProduct'>Product</a></span></div>
						<div class="sidemenuselected" style='position: relative; width: 105px'><span class='icon' id='icon_acquisitions'><img src='images/acquisitions.gif'></span><span class='link'>Acquisitions</span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/key_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccess'>Access</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/contacts_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showContacts'>Contacts</a></span></div>
						<?php if ($user->accountTabIndicator == '1') { ?><div class='sidemenuunselected'><span class='icon'><img src='images/lock_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccounts'>Accounts</a></span></div><?php } ?>
						<div class='sidemenuunselected'><span class='icon'><img src='images/attachment_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAttachments'>Attachments</a></span><span class='span_AttachmentNumber smallGreyText' style='clear:right; margin-left:18px;'></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/routing_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showRouting'>Routing</a></span></div>
					</td>
					<td class='mainContent'>

						<div class='div_mainContent'>
						</div>
					</td>
				</tr>
			</table>

		</div>





		<?php if ((isset($_GET['showTab'])) && ($_GET['showTab'] == 'access')){ ?>
		<div style="width: 597px;" id ='div_access'>
		<?php } else { ?>
		<div style="display:none;width: 597px;" id ='div_access'>
		<?php } ?>

			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tr>
					<td class="sidemenu">
						<div class='sidemenuunselected'><span class='icon'><img src='images/butterflyfishicon_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showProduct'>Product</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/acquisitions_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAcquisitions'>Acquisitions</a></span></div>
						<div class="sidemenuselected" style='position: relative; width: 105px'><span class='icon' id='icon_access'><img src='images/key.gif'></span><span class='link'>Access</span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/contacts_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showContacts'>Contacts</a></span></div>
						<?php if ($user->accountTabIndicator == '1') { ?><div class='sidemenuunselected'><span class='icon'><img src='images/lock_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccounts'>Accounts</a></span></div><?php } ?>
						<div class='sidemenuunselected'><span class='icon'><img src='images/attachment_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAttachments'>Attachments</a></span><span class='span_AttachmentNumber smallGreyText' style='clear:right; margin-left:18px;'></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/routing_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showRouting'>Routing</a></span></div>
					</td>
					<td class='mainContent'>

						<div class='div_mainContent'>
						</div>
					</td>
				</tr>
			</table>

		</div>



		<div style="display:none;width: 597px;" id ='div_contacts'>
			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tr>
					<td class="sidemenu">
						<div class='sidemenuunselected'><span class='icon'><img src='images/butterflyfishicon_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showProduct'>Product</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/acquisitions_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAcquisitions'>Acquisitions</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/key_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccess'>Access</a></span></div>
						<div class="sidemenuselected" style='position: relative; width: 105px'><span class='icon' id='icon_contacts'><img src='images/contacts.gif'></span><span class='link'>Contacts</span></div>
						<?php if ($user->accountTabIndicator == '1') { ?><div class='sidemenuunselected'><span class='icon'><img src='images/lock_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccounts'>Accounts</a></span></div><?php } ?>
						<div class='sidemenuunselected'><span class='icon'><img src='images/attachment_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAttachments'>Attachments</a></span><span class='span_AttachmentNumber smallGreyText' style='clear:right; margin-left:18px;'></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/routing_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showRouting'>Routing</a></span></div>
					</td>
					<td class='mainContent'>

						<div class='div_mainContent'></div>
						<div id='div_archivedContactDetails'></div>

					</td>
				</tr>
			</table>

		</div>


		<?php if ($user->accountTabIndicator == '1') { ?>


		<div style="display:none;width: 597px;" id ='div_accounts'>
			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tr>
					<td class="sidemenu">
						<div class='sidemenuunselected'><span class='icon'><img src='images/butterflyfishicon_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showProduct'>Product</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/acquisitions_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAcquisitions'>Acquisitions</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/key_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccess'>Access</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/contacts_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showContacts'>Contacts</a></span></div>
						<div class="sidemenuselected" style='position: relative; width: 105px'><span class='icon' id='icon_accounts'><img src='images/lock.gif'></span><span class='link'>Accounts</span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/attachment_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAttachments'>Attachments</a></span><span class='span_AttachmentNumber smallGreyText' style='clear:right; margin-left:18px;'></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/routing_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showRouting'>Routing</a></span></div>
					</td>
					<td class='mainContent'>

						<div class='div_mainContent'>
						</div>
					</td>
				</tr>
			</table>

		</div>


		<?php } ?>

		<div style="display:none;width: 597px;" id ='div_attachments'>
			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tr>
					<td class="sidemenu">
						<div class='sidemenuunselected'><span class='icon'><img src='images/butterflyfishicon_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showProduct'>Product</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/acquisitions_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAcquisitions'>Acquisitions</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/key_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccess'>Access</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/contacts_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showContacts'>Contacts</a></span></div>
						<?php if ($user->accountTabIndicator == '1') { ?><div class='sidemenuunselected'><span class='icon'><img src='images/lock_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccounts'>Accounts</a></span></div><?php } ?>
						<div class="sidemenuselected" style='position: relative; width: 105px'><span class='icon' id='icon_attachments'><img src='images/attachment.gif'></span><span class='link'>Attachments</span><br /><span class='span_AttachmentNumber smallGreyText' style='clear:right; margin-left:18px;'></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/routing_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showRouting'>Routing</a></span></div>
					</td>
					<td class='mainContent'>

						<div class='div_mainContent'>
						</div>
					</td>
				</tr>
			</table>

		</div>

		<div style="display:none;width: 897px;" id ='div_routing'>
			<table cellpadding="0" cellspacing="0" style="width: 100%; table-layout: fixed;">
				<tr>
					<td class="sidemenu">
						<div class='sidemenuunselected'><span class='icon'><img src='images/butterflyfishicon_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showProduct'>Product</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/acquisitions_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAcquisitions'>Acquisitions</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/key_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccess'>Access</a></span></div>
						<div class='sidemenuunselected'><span class='icon'><img src='images/contacts_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showContacts'>Contacts</a></span></div>
						<?php if ($user->accountTabIndicator == '1') { ?><div class='sidemenuunselected'><span class='icon'><img src='images/lock_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAccounts'>Accounts</a></span></div><?php } ?>
						<div class='sidemenuunselected'><span class='icon'><img src='images/attachment_bw.gif'></span><span class='link'><a href='javascript:void(0)' class='showAttachments'>Attachments</a></span><span class='span_AttachmentNumber smallGreyText' style='clear:right; margin-left:18px;'></span></div>
						<div class="sidemenuselected" style='position: relative; width: 105px'><span class='icon' id='icon_routing'><img src='images/routing.gif'></span><span class='link'>Routing</span></div>
					</td>
					<td class='mainContent'>

						<div class='div_mainContent'>
						</div>
					</td>
				</tr>
			</table>

		</div>
	</div>
	<div style='float:right; vertical-align:top; width:303px; text-align:left; padding:0; margin:0; background-color:white;' id='div_fullRightPanel' class='rightPanel'>
		<div style="background-image:url('images/helpfullinks-top.jpg');background-repeat:no-repeat;width:265px;text-align:left;padding:6px;margin:10px 19px 0px 19px;">
			<div style='margin:29px 8px 0px 8px;' id='div_rightPanel'>
			</div>

		</div>

		<div style="background-image:url('images/helpfullinks-bottom.jpg');background-repeat:no-repeat;width:265px;height:50px;padding:6px; margin:0px 19px 15px 19px;">
			<div style='margin:0px 8px 10px 8px;'>
				<div style='width:219px; padding:7px; margin-bottom:5px;'>
					<a href="mailto:<?php echo $config->settings->feedbackEmailAddress; ?>?subject=<?php echo $resource->titleText . ' (Resource ID: ' . $resource->resourceID . ')'; ?>" class='helpfulLink'>Send feedback on this resource</a>
				</div>
			</div>
		</div>

	</div>
	</div>
	<script type="text/javascript" src="js/resource.js"></script>

	<?php

}

//print footer
include 'templates/footer.php';
?>