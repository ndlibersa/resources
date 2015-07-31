<?php
session_start();

include_once 'directory.php';

$resourceID = $_GET['resourceID'];
$archivedFlag = (!empty($_GET['archived']) && $_GET['archived'] == 1) ? true:false;

$resource = new Resource(new NamedArguments(array('primaryKey' => $resourceID)));
$util = new Utility();

$organizationArray = $resource->getOrganizationArray();

$exportIssues = array();
$issueIndex = 0;
if (count($organizationArray) > 0) {
	$issuedOrgs = array();
	foreach ($organizationArray as $orgData) {
		if (!in_array($orgData['organizationID'],$issuedOrgs)) {
// todo: create issues repo so we don't have to initialize an organization object from the wrong module
			$organization = new Organization(new NamedArguments(array('primaryKey' => $orgData['organizationID'])));

			$orgIssues = $organization->getIssues($archivedFlag);
			if (count($orgIssues) > 0) {
				foreach ($orgIssues as $issue) {
					foreach ($issue->attributeNames as $field=>$value) {
						$exportIssues[$issueIndex][$field] = $issue->attributes[$field];
					}
					$issueIndex++;
				}
			}
			$orgIssues = null;
			$issuedOrgs[] = $orgData['organizationID'];
		}
	}
}


//display any resource level issues for the resource (shows any other resources associated with the issue, too)
$resourceIssues = $resource->getIssues($archivedFlag);
if (count($resourceIssues) > 0) {
	foreach ($resourceIssues as $issue) {
		foreach ($issue->attributeNames as $field=>$value) {
			$exportIssues[$issueIndex][$field] = $issue->attributes[$field];
		}
		$issueIndex++;
	}
}

header("Pragma: public");
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=\"issues.csv\"");

$out = fopen('php://output', 'w');

fputcsv($out,array_keys($exportIssues[0]));

foreach ($exportIssues as $issue) {
	fputcsv($out, $issue);
}
fclose($out);

?>