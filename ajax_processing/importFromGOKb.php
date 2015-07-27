<div> Import from GOKb . php </div>           
<?php

include_once $_SERVER['DOCUMENT_ROOT']."resources/admin/classes/domain/GOKbTools.php";
include_once $_SERVER['DOCUMENT_ROOT']."resources/admin/classes/domain/ImportTool.php";

/* required $_POST : type, gokbID */

$gokbTool = GOKbTools::getInstance();
$importTool = new ImportTool();

$record = $gokbTool->getRecord($_POST['type'], $_POST['id']);
//echo "DEBUG_ IFG _ request 1 ok\n";
$nbTipps = $gokbTool->getNbTipps($record);

//echo "DEBUG_ IFG _ all request ok\n";
$datas = array();
$identifiers = array("gokb" => $_POST['id']);

$recordDetails=$record->{'metadata'}->{'gokb'}->{$_POST['type']};

$datas['titleText'] = $recordDetails->{'name'};

$string = "";


if ($_POST['type'] == 'package'){
	//resource parent (package lui meme)
	

	//ensemble des tipps (boucle)
	$tippsDatas = array();

} else{ //import title; 
	//TODO medium = resourceType (ajout manuel only pour l'instant)

	//Organization
	$org = $recordDetails->{"publisher"}->{'name'};     
	if ($org ){
		$data["organization"]=array("publisher" => $org);
		$string .= "insertion de datas['organization'] = ".$org."</br>";
	}
	
	
	//Identifiers _ URL ?
	$xml = $recordDetails->{'identifiers'};
	$xmlIDs =$xml->children();

	foreach ($xmlIDs as $key => $value) {
		$tmp = $value->attributes();
		$datas[$tmp[0]] = $tmp[1];
		$string .= "insertion de datas[".$tmp[0]."] = ".$tmp[1]."</br>";
	}
	
	
	
	//History / Aliases


echo "$string";
	//$datas['parentResource'] ?


}



?>

Import OK !!