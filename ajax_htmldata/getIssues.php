<?php
	$resourceID = $_GET['resourceID'];

	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

	$util = new Utility();
	$getIssuesFormData = "action=getIssuesList&resourceID=".$resourceID;
	$getDowntimeFormData = "action=getDowntimeList&resourceID=".$resourceID;
	$exportIssuesUrl = "export_issues.php?resourceID={$resourceID}";
	$exportDowntimesUrl = "export_downtimes.php?resourceID={$resourceID}";


?>

	<table id="issueTable" class='linedFormTable issueTabTable'>
		<tr>
			<th>Issues/Problems</th>
		</tr>
		<tr>
			<td><a id="createIssueBtn" class="thickbox" href="ajax_forms.php?action=getNewIssueForm&resourceID=<?php echo $resourceID; ?>&modal=true&height=425&width=500">report new issue</a></td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $getIssuesFormData; ?>" class="issuesBtn" id="openIssuesBtn">view open issues</a> 
				<a target="_blank" href="<?php echo $exportIssuesUrl;?>"><img src="images/xls.gif" /></a>
				<div class="issueList" id="openIssues" style="display:none;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $getIssuesFormData."&archived=1"; ?>" class="issuesBtn" id="archivedIssuesBtn">view archived issues</a> 
				<a target="_blank" href="<?php echo $exportIssuesUrl;?>&archived=1"><img src="images/xls.gif" /></a>
				<div class="issueList" id="archivedIssues"></div>
			</td>
		</tr>
	</table>

	<table id="downTimeTable" class='linedFormTable issueTabTable'>
		<tr>
			<th>Downtime</th>
		</tr>
		<tr>
			<td><a id="createDowntimeBtn" class="thickbox" href="ajax_forms.php?action=getNewDowntimeForm&resourceID=<?php echo $resourceID; ?>&height=200&width=390&modal=true">report new Downtime</a></td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $getDowntimeFormData; ?>" class="downtimeBtn" id="openDowntimeBtn">view current/upcoming downtime</a> 
				<a target="_blank" href="<?php echo $exportDowntimesUrl;?>"><img src="images/xls.gif" /></a>
				<div class="downtimeList" id="currentDowntime" style="display:none;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $getDowntimeFormData."&archived=1"; ?>" class="downtimeBtn" id="archiveddowntimeBtn">view archived downtime</a> 
				<a target="_blank" href="<?php echo $exportDowntimesUrl;?>&archived=1"><img src="images/xls.gif" /></a>
				<div class="downtimeList" id="archivedDowntime"></div>
			</td>
		</tr>
	</table>

