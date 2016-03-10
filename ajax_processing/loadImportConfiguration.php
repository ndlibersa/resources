<?php
$configName = $_POST["configName"];

//$infile = fopen("import_configs/" . $configName, "rb");
$infile = fopen("import_configs/" . $configName, "rb");
$configData = fgets($infile,1000);
fclose($infile);
echo $configData;
?>