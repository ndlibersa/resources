<?php

echo 'get kb file <br/>';

include "../admin/classes/domain/GOKbTools.php";
echo 'after include ok name = '.$_POST['name'].'<br/>';

$tool = GOKbTools::getInstance();


$a = $tool->searchByName($_POST['name'], "package");
$b = $tool->searchByName($_POST['name'], "title");


echo '<h4> Search results : </h4>';
echo '<h5> Packages </h5>';

foreach ($a as $key => $value) {
	echo '<form method=get action="details.php">';
	echo ' - '.$value;
	echo '<input type="hidden" name="type" value="package"/> <input type="hidden" name="id" value="'.$key.'"/>';
	echo '  <button type=submit>Details</button>';
	echo '</form>';
}


echo '<h5> Titles </h5>';

foreach ($b as $key => $value) {
	echo '<form method=get action="details.php">';
	echo ' - '.$value;
	echo '<input type="hidden" name="type" value="title"/> <input type="hidden" name="id" value="'.$key.'"/>';
	echo '  <button type=submit>Details</button>';
	echo '</form>';
}





?>