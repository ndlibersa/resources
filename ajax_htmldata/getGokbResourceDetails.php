<?php echo "DEBUG_ getGOKBdetails";

include_once $_SERVER['DOCUMENT_ROOT']."resources/admin/classes/domain/GOKbTools.php";

$tool = GOKbTools::getInstance();

$record = $tool->getDetails($_POST['type'], $_POST['id']);
echo $tool->displayRecord($record);


 ?>