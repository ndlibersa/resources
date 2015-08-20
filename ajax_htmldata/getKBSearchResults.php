<div id='div_KBsearchResults'>
      <div class='formTitle' style='width:745px;'>
            <span class='headerText'>Add new resource - Search results</span>
      </div>
      <?php
      include_once $_SERVER['DOCUMENT_ROOT'] . "resources/admin/classes/domain/GOKbTools.php";
      include_once $_SERVER['DOCUMENT_ROOT'] . "resources/ajax_htmldata/getPagination.php";

      $tool = GOKbTools::getInstance();

      $results = $tool->searchOnGokb($_POST['name'], $_POST['issn'], $_POST['publisher'], $_POST['type']);
      $nb_packages = count($results[0]);
      $nb_titles = count($results[1]);
      $isPaginated = ((isset($_POST['paginate'])) && ($_POST['paginate'] == true));

//Display packages results
      if ($nb_packages > 0) {
            echo "<div id='div_packagesResults' class='div_results'>";
            echo "<span class='results_type_title'> Packages </span> <a class='moreResults linkStyle";
            if ($isPaginated || $nb_packages<5)
                  echo " invisible";
            echo '\' onclick=allResults("' . $_POST['name'] . '","' . $_POST['publisher'] . '",-1);';
            echo ">View all packages results</a><br/>";

            echo '<table class="results_table">';
            foreach ($results[0] as $key => $value) {
                  echo "<tr ";
                  if ($isPaginated)
                        echo "class='invisible'";
                  echo "><td class='results_table_title_cell'>";
                  echo ' - ' . $value;
                  echo '<td class="results_table_cell"> <button class=thickbox onclick=getDetails("package","' . $key . '");>Details</button></td>';
                  echo '<td class="results_table_cell"> <button class=thickbox onclick=selectResource("package","' . $key . '");>Select</button></td>';
                  echo "</tr>";
            }
            echo '</table>';
            echo "</div>";
      }

//Display titles results
      if ($nb_titles > 0) {
            echo "<div id='div_titlesResults' class='div_results'>";
            echo '<span class="results_type_title"> Titles </span> <a class="moreResults linkStyle';
            if ($isPaginated || $nb_titles<5)
                  echo " invisible";
            echo '" onclick=allResults("' . $_POST['name'] . '","' . $_POST['publisher'] . '",1);';
            echo '>View all titles results</a><br/>';


            echo '<table class="results_table">';
            foreach ($results[1] as $key => $value) {
                  echo "<tr ";
                  if ($isPaginated)
                        echo " class='invisible'";
                  echo "><td class='results_table_title_cell'>";
                  echo ' - ' . $value;
                  //echo ' - '.utf8_encode($value);
                  echo '<td class="results_table_cell"> <button class=thickbox onclick=getDetails("title","' . $key . '");>Details</button></td>';
                  echo '<td class="results_table_cell"> <button class=thickbox onclick=selectResource("title","' . $key . '");>Select</button></td>';
                  echo "</tr>";
            }
            echo '</table>';

            echo "</div>";
      } else if(($nb_packages == 0) && ($nb_titles == 0) && (!$isPaginated)){
                  echo "No results, please check your search fields <br/>";
      }

//Display pagination
      if ($isPaginated) {
            switch ($_POST['type']) {
                  case -1:
                        $resType = 0;
                        $divId = "div_packagesResults";
                        break;
                  case 1:
                        $resType = 1;
                        $divId = "div_titlesResults";
                        break;
                  default:
                        break;
            }
            echo paginate(count($results[$resType]), "$divId");
            echo "<script>iterator(0);</script>";
      }
      ?>
      <div class="search_nav_button">

            <span id="span_back"><input type=button value='Back' onclick="goBack();"/></span>
            <input type='button' value='Cancel' onclick="tb_remove();">
      </div>
</div>
<script type="text/javascript" src="js/KBSearch.js"></script>
<script type="text/javascript" src="js/plugins/thickbox.js"></script>
