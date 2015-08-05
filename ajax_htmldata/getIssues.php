<?php
	$resourceID = $_GET['resourceID'];

	$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));

	$util = new Utility();
	$getIssuesFormData = "action=getIssuesList&resourceID=".$resourceID;
	$exportUrl = "export_issues.php?resourceID={$resourceID}";


?>

	<table class='linedFormTable issueTable'>
		<tr>
			<th>Issues/Problems</th>
		</tr>
		<tr>
			<td><a id="createIssueBtn" class="thickbox" href="ajax_forms.php?action=getNewIssueForm&resourceID=<?php echo $resourceID; ?>&modal=true">report new issue</a></td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $getIssuesFormData; ?>" class="issuesBtn" id="openIssuesBtn">view open issues</a> 
				<a target="_blank" href="<?php echo $exportUrl;?>"><img src="images/xls.gif" /></a>
				<div class="issueList" id="openIssues" style="display:none;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<a href="<?php echo $getIssuesFormData."&archived=1"; ?>" class="issuesBtn" id="archivedIssuesBtn">view archived issues</a> 
				<a target="_blank" href="<?php echo $exportUrl;?>&archived=1"><img src="images/xls.gif" /></a>
				<div class="issueList" id="archivedIssues"></div>
			</td>
		</tr>
	</table>

