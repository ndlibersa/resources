<script type="text/javascript" src="js/plugins/thickbox.js"></script>
<script type="text/javascript" src="js/KBSearch.js"></script>



<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/GOKbTools.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "resources/ajax_htmldata/getPagination.php";

$tool = GOKbTools::getInstance();
$record = $tool->getDetails($_POST['type'], $_POST['id']);
$nbTipps = $tool->getNbTipps($record);
$displayTippsTab = ($nbTipps > 0);
?>

<div id='resourceDetails'>
      <div class='formTitle' style='width:745px;'>
            <span class='headerText'>
                  <?php echo ucfirst($_POST['type']); ?>
                  details for
                  <?php echo $tool->getResourceName($record); ?>
            </span>
      </div>

      <div id="detailsTabs">
            <ul>
                  <li id='globalDetails' class='selected' onclick="loadDetailsContent(0);">Details</li> 
                  <?php
                  if ($displayTippsTab) {
                        echo "<li id='tippsDetails' onclick=\"loadDetailsContent(1);
                              iterator(0);\">";
                        //<?php
                        if ($_POST['type'] == 'package') {
                              echo "Titles ";
                        } else {
                              echo "Available in ";
                        }
                        echo "(" . $nbTipps . ")";

                        echo "</li>";
                  }
                  ?>
            </ul>
      </div>

      <div id="detailsContainer">
            <div id="globalDet">
            <?php 
            if (!$displayTippsTab) { 
                  echo "<p id='noTipps'> No TIPPs for this resource </p>";
            }
            echo $tool->displayRecord($record); ?>
            </div>
                  <?php if ($displayTippsTab) { ?>
                  <div id="tippsDet" class='invisible'>
                        <?php
                        echo $tool->displayRecordTipps($record, $_POST['type']);
//                  echo paginate($nbTipps, "tippsDet");
                        ?>
                  </div>
            <?php } ?>
      </div>
      <?php if ($displayTippsTab) { ?>
            <div id="paginationDiv" class="invisible">
                  <?php echo paginate($nbTipps, "tippsDet"); ?>
            </div>
<?php } ?>
      <div class="search_nav_button">
<?php echo "<input type=button value='Select' onclick=\" selectResource('" . $_POST['type'] . "','" . $_POST['id'] . "');\">"; ?>
            <span id="span_back"><input type=button value='Back' onclick="goBack();"/></span>
            <input type='button' value='Cancel' onclick="tb_remove();">

      </div>

</div>