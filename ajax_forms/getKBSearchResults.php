<div id='div_KBsearchResults'>
		<div class='formTitle' style='width:745px;'>
			<span class='headerText'>Add new resource - Search results</span>
		</div>
<?php
include_once $_SERVER['DOCUMENT_ROOT']."resources/admin/classes/domain/GOKbTools.php";

$tool = GOKbTools::getInstance();

$results = $tool->searchOnGokb($_POST['name'], $_POST['issn'], $_POST['publisher']);
$nb_packages = count($results[0]);
$nb_titles = count($results[1]);

//echo "DEBUG _ nb pack = ".$nb_packages."   _   nb_titles = ".$nb_titles."<br/>";


if ($nb_packages > 0){
	echo "<div id='div_packagesResults'>";
	echo "<h3> Packages </h3> <span class='results infos'>".$nb_packages." results </span><a class='moreResults thickbox'>View all packages results</a><br/>";

	$p = array_slice($results[0], 0, 5, true);
	echo '<table>';
	foreach ($p as $key => $value) {
		//	echo '<form method=get action="details.php">';
		echo '<tr><form><td>';
		echo ' - '.$value;
		echo '<input type="hidden" name="type" value="package"/> <input type="hidden" name="id" value="'.$key.'"/></td>';
		echo '<td>  <button type=submit>Details</button></td><td></td>';
		echo '</tr></form>';
	}
	echo '</table>';
	echo "</div>";
}



if ($nb_titles > 0){
echo "<div id='div_titlesResults'>";
echo "<h3> Issues </h3> <span class='results infos'>".$nb_titles." results</span><a class='moreResults thickbox' >View all packages results</a><br/>";

$t=array_slice($results[1], 0,5,true);

echo '<table>';
	foreach ($t as $key => $value) {
		//	echo '<form method=get action="details.php">';
		echo '<tr><form><td>';
		echo ' - '.$value;
		echo '<input type="hidden" name="type" value="package"/> <input type="hidden" name="id" value="'.$key.'"/></td>';
		echo '<td>  <button type=submit>Details</button></td><td></td>';
		echo '</tr></form>';
	}
	echo '</table>';

echo "</div>";
} else {
	echo "No results, please check your search fields <br/>";
}

?>

</div>
