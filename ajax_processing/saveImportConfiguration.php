<?php
$configName = $_POST["configName"];
$configData = $_POST["configData"];

$outfile = fopen("import_configs/" . $configName, "wb");
if(!$outfile)
{
	echo $configName . " : " . $configData . "\n";
}
fwrite($outfile, $configData);
fclose($outfile);
$dir = './import_configs';
$files = array_slice(scandir($dir), 2);
foreach($files as $configuration)
{
	echo "<option value='" . $configuration . "'";
	if($configName==$configuration)
	{
			echo " selected";
	}
	echo ">" . $configuration . "</option>";
}
?>