<div class='formTitle' style='width:745px;'>
      <span class='headerText'>Customize imported package content</span>  
</div>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/Resource.php";

if ($_POST['tab']) {    
      $resToRemove = $_POST['tab']; 
      echo "The ".count($resToRemove)." following resources have been removed from package :";
      
      foreach ($resToRemove as $res) {
          $object = new Resource(new NamedArguments(array('primaryKey' => $res)));
          echo "<br/>- ".$object->titleText;
          $object->removeResource();
      }
      
} else {
      echo '<p>Uncheck titles you want to remove from this package. Unselect all will import package only without any titles. <br/>
      To delete this package and all resources included in <a onclick="removeResAndChildren(\''.$_POST['id'].'\');">Click here</a></p>';
      $resource = new Resource();
      $package = $resource->getResourceByIdentifierAndType($_POST['id'], 'gokb');
      if (count($package) > 1) {
            echo "more than 1 resource correspond to this package !! "; //TODO _ DEBUG _ 
      } else {
            $titles = $package[0]->getChildResources(); //return an array of ResourceRelationship
            //ajax_processing/customImportedPackageContent.php
            echo "<div>"
                    . "<form enctype=\"multipart/form-data\" action=\"#\" method=\"post\" id=\"customPackageForm\" name=\"customPackageForm\">"
                    . "<fieldset id=\"customPackageFieldset\">"
                    . "<legend>Package content</legend>"
                    . " <input type='checkbox' id='selectAll' onclick=\"checkAll(this);\" checked='checked'>Select all"
                    . "<table id =\"customPackageTable\"> ";
            
            foreach ($titles as $relationship) {
                  $title = new Resource(new NamedArguments(array('primaryKey' => $relationship->resourceID)));
                  echo "<tr><td><input type ='checkBox' checked='checked' name='cbs' value='" . $title->resourceID . "'></td><td>" . $title->titleText."</td></tr>";
            }
            echo  "</table>"
            . "</fieldset>"
                    . "<input type=\"button\" name=\"submit\" value=\"confirmCustomization\" onclick=\"submitCustom('".$_POST['id']."');\"/>"
                    . "</form>"
                    . "</div>";
            
      }

}
      
?>

<div class="search_nav_button">
      <span id="span_back"><input type=button value="Back" onclick="goBack();"/></span>
      <input type="button" value="OK" onclick="tb_remove();"/>
</div>
      


      
      

        
        