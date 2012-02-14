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


include_once 'user.php';

$util = new Utility();
$config = new Configuration();

//get the current page to determine which menu button should be depressed
$currentPage = $_SERVER["SCRIPT_NAME"];
$parts = Explode('/', $currentPage);
$currentPage = $parts[count($parts) - 1];

//get CORAL URL for 'Change Module' and logout link.
$coralURL = $util->getCORALURL();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="public">
<title>Resources Module - <?php echo $pageTitle; ?></title>
<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/datePicker.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/jquery.autocomplete.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/jquery.tooltip.css" type="text/css" media="screen" />
<link rel="SHORTCUT ICON" href="images/butterflyfishfavicon.ico" />
<script type="text/javascript" src="js/plugins/jquery.js"></script>
<script type="text/javascript" src="js/plugins/ajaxupload.3.5.js"></script>
<script type="text/javascript" src="js/plugins/thickbox.js"></script>
<script type="text/javascript" src="js/plugins/date.js"></script>
<script type="text/javascript" src="js/plugins/jquery.datePicker.js"></script>
<script type="text/javascript" src="js/plugins/jquery.autocomplete.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>
<noscript><font face=arial>JavaScript must be enabled in order for you to use CORAL. However, it seems JavaScript is either disabled or not supported by your browser. To use CORAL, enable JavaScript by changing your browser options, then <a href="">try again</a>. </font></noscript>

<div class="wrapper">
<center>
<table>
<tr>
<td style='vertical-align:top;'>
<div style="text-align:left;">

<center>
<table class="titleTable" style="background-image:url('images/resourcestitle.jpg');background-repeat:no-repeat;width:900px;text-align:left;">
<tr style='vertical-align:top;'>
<td style='height:53px;'>
&nbsp;
</td>
<td style='text-align:right;height:53px;'>
<div style='margin-top:1px;'>
<span class='smallText' style='color:#526972;'>
<?php
	echo "Hello, ";
	//user may not have their first name / last name set up
	if ($user->lastName){
		echo $user->firstName . " " . $user->lastName;
	}else{
		echo $user->loginID;
	}

?>
</span>
<br /><?php if($config->settings->authModule == 'Y'){ echo "<a href='" . $coralURL . "auth/?logout'>logout</a>"; } ?>
<?php if ($config->settings->testMode == 'Y') { ?>
  <br><span style="color:red;font-size:90%;">(Test)</span>
<?php } ?>
</div>
</td>
</tr>

<tr style='vertical-align:top'>
<td style='width:870px;height:19px;'>

<?php if ($user->isAdmin()){ ?>
<a href='index.php'><img src="images/menu/menu-home<?php if ($currentPage == 'index.php') { echo "-on"; } ?>.gif" hover="images/menu/menu-home-over.gif" class="rollover" /></a><img src='images/menu/menu-bar.gif'><a href='ajax_forms.php?action=getNewResourceForm&height=503&width=775&resourceID=&modal=true' class='thickbox' id='newResourceForm'><img src='images/menu/menu-newresource.gif' hover="images/menu/menu-newresource-over.gif" class="rollover"></a><img src='images/menu/menu-bar.gif'><a href='queue.php'><img src='images/menu/menu-myqueue<?php if ($currentPage == 'queue.php') { echo "-on"; } ?>.gif' hover="images/menu/menu-myqueue-over.gif" class="rollover"></a><img src='images/menu/menu-bar.gif'><a href='admin.php'><img src='images/menu/menu-admin<?php if ($currentPage == 'admin.php') { echo "-on"; } ?>.gif' hover="images/menu/menu-admin-over.gif" id="menu-last" class="rollover" /></a><img src='images/menu/menu-end<?php if ($currentPage == 'admin.php') { echo "-on"; } ?>.gif' hover="images/menu/menu-end-over.gif" id="menu-end" />
<?php }else if ($user->canEdit()){ ?>
<a href='index.php'><img src="images/menu/menu-home<?php if ($currentPage == 'index.php') { echo "-on"; } ?>.gif" hover="images/menu/menu-home-over.gif" class="rollover" /></a><img src='images/menu/menu-bar.gif'><a href='ajax_forms.php?action=getNewResourceForm&height=503&width=775&resourceID=&modal=true' class='thickbox' id='newResourceForm'><img src='images/menu/menu-newresource.gif' hover="images/menu/menu-newresource-over.gif" class="rollover"></a><img src='images/menu/menu-bar.gif'><a href='queue.php'><img src='images/menu/menu-myqueue<?php if ($currentPage == 'queue.php') { echo "-on"; } ?>.gif' hover="images/menu/menu-myqueue-over.gif" id="menu-last" class="rollover" /></a><img src='images/menu/menu-end<?php if ($currentPage == 'queue.php') { echo "-on"; } ?>.gif' hover="images/menu/menu-end-over.gif" id="menu-end" />
<?php }else{ ?>
<a href='index.php'><img src="images/menu/menu-home<?php if ($currentPage == 'index.php') { echo "-on"; } ?>.gif" hover="images/menu/menu-home-over.gif" class="rollover" id="menu-last" /></a><img src='images/menu/menu-end<?php if ($currentPage == 'index.php') { echo "-on"; } ?>.gif' hover="images/menu/menu-end-over.gif" id="menu-end" />
<?php } ?>
</td>

<td style='width:130px;height:19px;' align='right'>
<?php

//only show the 'Change Module' if there are other modules installed or if there is an index to the main CORAL page

if ((file_exists($util->getCORALPath() . "index.php")) || ($config->settings->licensingModule == 'Y') || ($config->settings->organizationsModule == 'Y') || ($config->settings->cancellationModule == 'Y') || ($config->settings->usageModule == 'Y')) {

	?>

	<div style='text-align:left;'>
		<ul class="tabs">
		<li style="background: url('images/change/coral-change.gif') no-repeat right;">&nbsp;
			<ul class="coraldropdown">
				<?php if (file_exists($util->getCORALPath() . "index.php")) {?>
				<li><a href="<?php echo $coralURL; ?>" target='_blank'><img src='images/change/coral-main.gif'></a></li>
				<?php
				}
				if ($config->settings->licensingModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>licensing/" target='_blank'><img src='images/change/coral-licensing.gif'></a></li>
				<?php
				}
				if ($config->settings->organizationsModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>organizations/" target='_blank'><img src='images/change/coral-organizations.gif'></a></li>
				<?php
				}
				if ($config->settings->cancellationModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>cancellation/" target='_blank'><img src='images/change/coral-cancellation.gif'></a></li>
				<?php
				}
				if ($config->settings->usageModule == 'Y') {
				?>
				<li><a href="<?php echo $coralURL; ?>usage/" target='_blank'><img src='images/change/coral-usage.gif'></a></li>
				<?php } ?>
			</ul>
		</li>
		</ul>

	</div>
	<?php

} else {
	echo "&nbsp;";
}

?>

</td>
</tr>
</table>
<span id='span_message' class='darkRedText' style='text-align:left;'><?php if (isset($_POST['message'])) echo $_POST['message']; if (isset($errorMessage)) echo $errorMessage; ?></span>
