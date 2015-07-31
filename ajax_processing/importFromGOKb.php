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

$recordName = $gokbTool->getResourceName($recordDetails);
$datas['titleText'] = $recordName;
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

       //ResourceType
      $datas['resourceType'] = (string) $recordDetails->{'medium'} ;//TODO _ ID _ create type if needed
     
      
      //Identifiers _ URL ?
      $xml = $recordDetails->{'identifiers'};
      $idToKeep = $xml->children();

//      foreach ($xmlIDs as $key => $value) {
//            $tmp = $value->attributes();
//            $identifiers["$tmp[0]"] = (string) $tmp[1];
//            $string .= "insertion de identifiers[" . $tmp[0] . "] = " . $tmp[1] . "</br>";
//      }
     
     
      //History / Aliases
      $variantName = $recordDetails->{'variantNames'};
      $variants = $variantName->children();
      
      if (count($variants) > 0){
            $datas['alias']['alternate name']=array();
            foreach ($variants as $key => $name) {
                  array_push($datas['alias']['alternate name'], (string) $name);
            }
      }
      
      $history = $recordDetails->{'history'};
      $events = $history->children();
      
  //    if ($history != null){ //comparer date pour identifiers, (publishedTO, event->date)  
      if (count($events) > 0){
            $datas['alias'] ['name change']= array();
            $events = $history->children();
            $checkDate = false;
            $date = 0;
            if ($recordDetails->{'publishedTo'} != null && $recordDetails->{'publishedTo'} != ''){
                  $checkDate = true;
                  $date =(string) $recordDetails->{'publishedTo'} ;
                  $date = $gokbTool->convertXmlDateToDateTime($date);
            } else {
                  $date = new DateTime();
            }
            
            foreach ($events as  $event) {
                 $eventDate = (string) $event->{'date'};
                 $eventDate = $gokbTool->convertXmlDateToDateTime($eventDate);
                 $from =$event->{'from'}->{'title'};
                 $to = $event->{'to'}->{'title'};
                 if ($from != $recordName){
                       array_push($datas['alias'] ['name change'], (string)$from);
                 }
                 if ($to != $recordName){
                       array_push($datas['alias'] ['name change'], (string)$to);
                 }
                 
                 if ($date < $eventDate){
                       $date = $eventDate;
                       $id_tmp = $event->{'to'}->{'identifiers'};
                       $idToKeep = $id_tmp->children();
                 }
                 
            }
            
      }
      
      $identifiers = $gokbTool->createIdentifiersArrayToImport($idToKeep);

      
      $importTool->addResource($datas, $identifiers);

      return $string;
}
?>

Import OK !!