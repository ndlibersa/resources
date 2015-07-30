<div> Import from GOKb . php </div>           
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/GOKbTools.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/ImportTool.php";

/* required $_POST : type, gokbID */

$gokbTool = GOKbTools::getInstance();
$importTool = new ImportTool();

$record = $gokbTool->getRecord($_POST['type'], $_POST['id']);
//echo "DEBUG_ IFG _ request 1 ok\n";
//$nbTipps = $gokbTool->getNbTipps($record);

//echo "DEBUG_ IFG _ all request ok\n";
$datas = array();
$identifiers = array("gokb" => $_POST['id']);

$recordDetails = $record->{'metadata'}->{'gokb'}->{$_POST['type']};
$nbTipps = $gokbTool->getNbTipps($recordDetails);
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
      //TODO medium = resourceType (ajout manuel only pour l'instant)
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

      
      //parents
     if ($nbTipps > 0) {
            $tipps = $recordDetails->{'TIPPs'};
            $type = "package";
            $parents = array();

            foreach ($tipps->children() as $child) {
                  $resource = $child->{$type};
                  $resourceAttr = $resource->attributes();

                  $parentDatas = array();
                  $parentDatas['titleText'] = $gokbTool->getResourceName($resource);
                  $parentDatas['identifiers']['gokb'] = $gokbTool->UriToGokbId("$type" . 's/' . $resourceAttr[0]);
                  $parentDatas['organization']['platform'] = (string) $child->{'platform'}->{'name'};
                  $parentDatas['resourceURL'] = (string) $child->{'url'};
                  //TODO _ coverage and others
                  array_push($parents, $parentDatas);
            }
      }

      $datas['parentResource'] = $parents; //TODO _ modif struct string -> tab

      //History / Aliases
      //$datas['parentResource'] ?
//echo "$string";	
    //  $importTool->addResource($datas, $identifiers);

      return $string;
}
?>

Import OK !!