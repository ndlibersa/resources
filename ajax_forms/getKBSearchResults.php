<div id='div_KBsearchResults'>
	<div class='formTitle' style='width:745px;'>
		<span class='headerText'>Add new resource - Search results</span>
	</div>
<?php
include_once $_SERVER['DOCUMENT_ROOT']."resources/admin/classes/domain/GOKbTools.php";

$tool = GOKbTools::getInstance();

$results = $tool->searchOnGokb($_POST['name'], $_POST['issn'], $_POST['publisher'], $_POST['type']);
$nb_packages = count($results[0]);
$nb_titles = count($results[1]);


if ($nb_packages > 0){
	echo "<div id='div_packagesResults'>";
	echo "<h3> Packages </h3> <span class='results infos'>".$nb_packages." results </span><a class='moreResults'";
	echo 'onclick=allResults("'.$_POST['name'].'","'.$_POST['publisher'].'",-1);';
	echo ">View all packages results</a><br/>";

	echo '<table>';
	foreach ($results[0] as $key => $value) {
		echo "<tr><td>";
		echo ' - '.$value;
		echo '<td>  <button class=thickbox onclick=getDetails("package","'.$key.'");>Details</button></td>';
		echo '<td> <button class=thickbox onclick=select("package","'.$key.'");>Select</button></td>';
		echo "</tr>";
	}
	echo '</table>';
	echo "</div>";
}



if ($nb_titles > 0){
echo "<div id='div_titlesResults'>";
echo '<h3> Issues </h3> <span class="results infos">'.$nb_titles.' results</span><a class="moreResults"';
echo 'onclick=allResults("'.$_POST['name'].'","'.$_POST['publisher'].'",1);';
echo '>View all issues results</a><br/>';


echo '<table>';
	foreach ($results[1] as $key => $value) {
		echo "<tr><td>";
		echo ' - '.$value;
		echo '<td> <button class=thickbox onclick=getDetails("title","'.$key.'");>Details</button></td>';
		echo '<td> <button class=thickbox onclick=select("title","'.$key.'");>Select</button></td>';
		echo "</tr>";
	}
	echo '</table>';

echo "</div>";
} else {
	echo "No results, please check your search fields <br/>";
}

?>
<a id="backToNewResourceForm" class="thickbox" href="ajax_forms.php?action=getNewResourceForm&height=503&width=775&resourceID=&modal=true">
<span>Back</span>

</a>

<input type='button' value='cancel' onclick="tb_remove()">
</div>

<script type="text/javascript" src="js/KBSearch.js"></script>
<script type="text/javascript" src="js/plugins/thickbox.js"></script>
