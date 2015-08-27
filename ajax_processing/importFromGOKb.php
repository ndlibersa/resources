<div class='formTitle' style='width:745px;'>
            <span class='headerText'>Import from GOKb</span>
      </div>
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/GOKbTools.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/ImportTool.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/AcquisitionType.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/ResourceFormat.php";

$gokbTool = GOKbTools::getInstance();
$importTool = new ImportTool();

$record = $gokbTool->getRecord($_POST['type'], $_POST['id']);
$recordDetails = $record->{'metadata'}->{'gokb'}->{$_POST['type']};
$nbTipps = $gokbTool->getNbTipps($recordDetails);

$datas = array();
$identifiers = array();

$recordName = $gokbTool->getResourceName($recordDetails);
$datas['titleText'] = $recordName;


if ($_POST['type'] == 'package') {
      //resource parent (package lui meme)
      $acqID = AcquisitionType::getAcquisitionTypeID($recordDetails->{"paymentType"});
      if ($acqID != null) {
            $datas["acquisitionTypeID"] = $acqID;
      }

      //Organization:
      $platform = (string)$recordDetails->{"nominalPlatform"};
      $datas['organization'] = array(
          "platform" => $platform,
          "provider" => (string)$recordDetails->{"nominalProvider"}
      );

      //Alternate Names
      $variantName = $recordDetails->{'variantNames'};
      $variants = $variantName->children();

      if (($variants != NULL) && (count($variants) > 0)) {
            $datas['alias']['alternate name'] = array();
            foreach ($variants as $key => $name) {
                  array_push($datas['alias']['alternate name'], (string) $name);
            }
      }

      $identifiers['gokb']=$_POST['id'];
      
      $importTool->addResource($datas, $identifiers);

      //TIPPs (titles included in package)
      $tipps = $recordDetails->{'TIPPs'};
      $tippsList = $tipps->children();

      foreach ($tippsList as $tipp) {
            unset($datas);
            unset($identifiers);
            
            //package
            $datas['parentResource'] = $recordName;
            
            //Resource Format
            $format = (string) $tipp->{'medium'};
            $datas['resourceFormatID'] = ResourceFormat::getResourceFormatID($format);

            //Resource name
            $title = $tipp->{'title'};
            $datas['titleText'] = (string) $title->{'name'};

            //Resource identifiers
            $ids = $title->{'identifiers'}->children();
            $identifiers = $gokbTool->createIdentifiersArrayToImport($ids);
            
            $titleAttr = $title->attributes();
            $gokbTitleID = $titleAttr['id'];
            $gokbID = "org.gokb.cred.TitleInstance:".$gokbTitleID;
            $identifiers ['gokb'] = $gokbID;
            
            //Organization/platform
            $datas['organization']['platform'] = $platform;
            
            //URL
            $datas['resourceURL'] = (string)$tipp{'url'};
            
            //coverage
            $covAttr = $tipp->{'coverage'}->attributes();
            $covText = "";
            foreach ($covAttr as $key=>$value){
                  if ($value != ''){
                        $covText .= $key."= ".$value."; ";
                  }
            }
            $datas['coverageText'] = $covText;
            
            $importTool->addResource($datas, $identifiers);
            
            
      }
} else { //import single title; 
      //Organization
      $org = $recordDetails->{"publisher"}->{'name'};
      if ($org != NULL) {
            $datas['organization'] = array("publisher" => $org);
      }

      //ResourceType
      $datas['resourceType'] = (string) $recordDetails->{'medium'};


      //Identifiers _ URL ?
      $xml = $recordDetails->{'identifiers'};
      $idToKeep = $xml->children();

      //History / Aliases
      $variantName = $recordDetails->{'variantNames'};
      $variants = $variantName->children();

      if (count($variants) > 0) {
            $datas['alias']['alternate name'] = array();
            foreach ($variants as $key => $name) {
                  array_push($datas['alias']['alternate name'], (string) $name);
            }
      }

      $history = $recordDetails->{'history'};
      $events = $history->children();

      //    if ($history != null){ //comparer date pour identifiers, (publishedTO, event->date)  
      if (count($events) > 0) {
            $datas['alias'] ['name change'] = array();
            $events = $history->children();
            $checkDate = false;
            $date = 0;
            if ($recordDetails->{'publishedTo'} != null && $recordDetails->{'publishedTo'} != '') {
                  $checkDate = true;
                  $date = (string) $recordDetails->{'publishedTo'};
                  $date = $gokbTool->convertXmlDateToDateTime($date);
            } else {
                  $date = new DateTime();
            }

            foreach ($events as $event) {
                  $eventDate = (string) $event->{'date'};
                  $eventDate = $gokbTool->convertXmlDateToDateTime($eventDate);
                  $from = $event->{'from'}->{'title'};
                  $to = $event->{'to'}->{'title'};
                  if ($from != $recordName) {
                        array_push($datas['alias'] ['name change'], (string) $from);
                  }
                  if ($to != $recordName) {
                        array_push($datas['alias'] ['name change'], (string) $to);
                  }

                  if ($date < $eventDate) {
                        $date = $eventDate;
                        $id_tmp = $event->{'to'}->{'identifiers'};
                        $idToKeep = $id_tmp->children();
                  }
            }
      }

      $identifiers = $gokbTool->createIdentifiersArrayToImport($idToKeep);
      $identifiers['gokb'] = $_POST['id'];


      $importTool->addResource($datas, $identifiers);
}

$displayStat = function ($val, $word, $text){
      $string = "";
      $verb = "has been ";
      if ($val != 0){
            if ($val > 1) {$word .= "s"; $verb="have been ";}
            $string = $val." ".$word." ".$verb.$text;
      }
      return $string;
};

print "</br><h2>Results</h2>";


//print "<p>" . (ImportTool::getNbRow()) . " rows have been processed. " . ImportTool::getNbInserted() . " rows have been inserted.</p>";
print "<p>".$displayStat(ImportTool::getNbRow(), "row", "processed. ");
print $displayStat(ImportTool::getNbInserted(), "row", "inserted. ")."</p>";

//print "<p>" . ImportTool::getNbParentInserted() . " parents have been created. " . ImportTool::getNbParentAttached() . " resources have been attached to an existing parent.</p>";
print "<p>".$displayStat(ImportTool::getNbParentInserted(), "parent", "created. ");
print $displayStat(ImportTool::getNbParentAttached(), "parent", "attached to an existing parent. ")."</p>";

//print "<p>" . ImportTool::getNbOrganizationsInserted() . " organizations have been created";
print "<p>".$displayStat(ImportTool::getNbOrganizationsInserted(), "organization", "created ");
if (count(ImportTool::getArrayOrganizationsCreated()) > 0) {
      print " (" . implode(',', ImportTool::getArrayOrganizationsCreated()) . "). ";
}
//print ". " . ImportTool::getNbOrganizationsAttached() . " resources have been attached to an existing organization.</p>";
print $displayStat(ImportTool::getNbOrganizationsAttached(), "resource", "attached to an existing organization. ")."</p>";



if ($_POST['type'] == 'package') {
      $res = new Resource();
      $packID = $res->getResourceByIdentifierAndType($_POST['id'], 'gokb');
      $nb = count($packID);
      if(($nb>1) || ($nb == 0) ){
            echo "None or more than one resource correspond => ERROR"; //DEBUG _ TODO
      } else {
            $p_id = $packID[0]->resourceID;
      }
      
      print "<div>"
              . "Personalize this package content <input type='button' value='Custom' onclick=\"getCustomizationScreen('".$p_id."');\">"
              . "</div>";
}
?>

<div class="search_nav_button">
      <span id="span_back"><input type=button value='Back' onclick="goBack();"/></span>
      <input type='button' value='Cancel' onclick="tb_remove();">
</div>
