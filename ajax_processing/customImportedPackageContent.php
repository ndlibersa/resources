<div class='formTitle' style='width:745px;'>
      <span class='headerText'>Customize imported package content</span>  
</div>

<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/Resource.php";
$resID = "";
if ($_POST['id'])
      $resID = $_POST['id'];
else
      $resID = $_GET['id'];


$displayResourceTabLines = function ($r_tab) {
      foreach ($r_tab as $resource) {
            $title = new Resource(new NamedArguments(array('primaryKey' => $resource->resourceID)));
            echo _("<tr><td class='customCb'><input type ='checkBox' checked='checked' name='cbs' value='" . $title->resourceID . "'></td><td class='customText'>" . $title->titleText . "</td></tr>");
      }
};

$customDone = false;

if ($_POST['tab']) {
      $resToRemove = $_POST['tab'];
      echo _("<br/><h4>The " . count($resToRemove) . " following resources have been removed from package :</h4></br>");
      echo _("<table id='removedRes'>");
      foreach ($resToRemove as $res) {
            $object = new Resource(new NamedArguments(array('primaryKey' => $res)));
            echo _("<tr><td> -     " . $object->titleText."</td></tr>");
            $object->removeResource();
      }
      echo _("</table>");
      $customDone = true;
} else {
      echo _('<p>Uncheck titles you want to remove from this package. Unselect all will import package only without any titles. <br/>
      To delete this package and all resources included in <a class="tippLink" onclick="removeResAndChildren(\'' . $resID . '\');">Click here</a></p>');

      $package = new Resource(new NamedArguments(array('primaryKey' => $resID)));
      if (count($package) > 1) {
            echo _("more than 1 resource correspond to this package !! "); //TODO _ DEBUG _ 
      } else {
            ?>
            <div id='customDivContainer'>
                  <form enctype="multipart/form-data" action="#" method="post" id="customPackageForm" name="customPackageForm">
                        <fieldset id="customPackageFieldset">
                              <legend><h3>Package content</h3></legend>
                              <table id ="customPackageTable">
                                    <thead>
                                          <tr>
                                                <th class='customCb'> All </th>
                                                <th class='customText'> Name filter</th>
                                          </tr>
                                          <tr>
                                                <td class="customCb">
                                                      <input type='checkbox' id='selectAll' onclick="checkAll(this);" checked='checked'>
                                                </td>
                                                <td class="customText"><input type='text' id='customFilter'></td>
                                          </tr>
                                    </thead>
                                    <tbody id='customTbody'>
                                          <?php
                                          if ($_POST['filter']) {
                                                $displayResourceTabLines($_POST['filter']);
                                          } else {
                                                $titles = $package->getChildResources(); //return an array of ResourceRelationship
                                                $displayResourceTabLines($titles);
                                          }
                                          ?>
                                    </tbody>
                              </table>
                        </fieldset>
                  </form>
            </div>
      <?php }
} ?>
<div class="search_nav_button">
      <?php if ($customDone) { ?>
            <input type="button" value="OK" onclick="tb_remove();"/>
             <?php } else { ?>
            <input type="button" name="submit" value="Confirm customization" onclick="submitCustom(
            <?php echo $package->resourceID; ?>);"/>
            <input type="button" value="Cancel" onclick="tb_remove();"/>
<?php } ?>
</div>


<script type="text/javascript" src="js/KBSearch.js"></script>




