<div> Import from GOKb . php </div>           
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/GOKbTools.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/ImportTool.php";

$gokbTool = GOKbTools::getInstance();
$importTool = new ImportTool();

$record = $gokbTool->getRecord($_POST['type'], $_POST['id']);
$recordDetails = $record->{'metadata'}->{'gokb'}->{$_POST['type']};
$nbTipps = $gokbTool->getNbTipps($recordDetails);

$datas = array();
$identifiers = array("gokb" => $_POST['id']);

$datas['titleText'] = $gokbTool->getResourceName($recordDetails);
$string = "";


if ($_POST['type'] == 'package') {
      //resource parent (package lui meme)
      $acqID = AcquisitionType::getAcquisitionTypeID($recordDetails->{"paymentType"});
      if ($acqID != null) {
            $datas["acquisitionTypeID"] = $acqID;
            $string .= "insertion de datas[acquisitionTypeID] = " . $acqID . "</br>";
      }
      
      //Organization:
      $datas['organization'] = array(
          "platform" => $recordDetails->{"nominalPlatform"},
          "provider" => $recordDetails->{"nominalProvider"}
      );
      $string .= "insertion organizations ";



      //ensemble des tipps (boucle)
      //$tippsDatas = array();
      
} else { //import title; 
     
      //Organization
      $org = $recordDetails->{"publisher"}->{'name'};
      if ($org != NULL) {
            $datas['organization'] = array("publisher" => $org);
            $string .= "insertion de datas['organization'] = " . $org . "</br>";
      }

      //Identifiers _ URL ?
      $xml = $recordDetails->{'identifiers'};
      $xmlIDs = $xml->children();

      foreach ($xmlIDs as $key => $value) {
            $tmp = $value->attributes();
            $identifiers["$tmp[0]"] = (string) $tmp[1];
            $string .= "insertion de identifiers[" . $tmp[0] . "] = " . $tmp[1] . "</br>";
      }
     
      //ResourceType
      $datas['resourceType'] = (string) $recordDetails->{'medium'} ;//TODO _ ID _ create type if needed
     
      //History / Aliases
      

      
      $importTool->addResource($datas, $identifiers);

      return $string;
}
?>

Import OK !!