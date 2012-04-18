<?php
include_once 'CORALInstaller.php';
$installer = new CORALInstaller();

$version = $_REQUEST['version'];

if (!$version || !$installer->isUpdateReady($version)) {
  header('Location: index.php');
  exit;
}

$update = $installer->getUpdate($version);

if ($_POST["submit"]) {
  $database_username = trim($_POST['database_username']);
	$database_password = trim($_POST['database_password']);
  
  if ($database_username) {
    $installer->connect($database_username, $database_password);
    if ($installer->error) {
      $installer->addErrorMessage($installer->error);
    }
  }
  
  if ($installer->hasPermissions($update["privileges"])) {
    $sql_file = "protected/update_$version.sql";
    
    if (!file_exists($sql_file)) {
			$installer->addErrorMessage("Could not open sql file: " . $sql_file . ".  If this file does not exist you must download new install files.");
		} else {
			//run the file - checking for errors at each SQL execution
			$f = fopen($sql_file,"r");
			$contents = fread($f,filesize($sql_file));
			$statements = explode(';',$contents);

			//Process the sql file by statements
			foreach ($statements as $statement) {
			   if (strlen(trim($statement))>3){
					//replace the DATABASE_NAME parameter with what was actually input
					$statement = str_replace("_DATABASE_NAME_", $installer->getDatabaseName(), $statement);

					$result = mysql_query($statement);
					if (!$result){
						$installer->addErrorMessage(mysql_error() . "<br /><br />For statement: " . $statement);
						break;
					}
				}
			}
      
      if (!$installer->hasErrorMessages()) {
        $installer->addMessage("Update $version was successfully applied.");
        header('Location: index.php');
        exit;
      }
		}
  } else if (!$installer->hasErrorMessages()) {
    $installer->addErrorMessage("The database user does not have the required permissions: ".implode(", ", $update["privileges"]));
  }
}

$installer->header("CORAL Licensing Update $version");
?>
<?php $installer->displayMessages();	?>
<h3>Welcome to the CORAL Licensing update for Version <?php echo $version; ?>!</h3>
<?php echo $update["description"]; ?>

<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
  <input type="hidden" name="version" value="<?php echo $version; ?>">
  <?php $installer->displayErrorMessages();	?>
  <?php
  if ($installer->hasPermissions($update["privileges"])) {
  ?>
    <p>The database user for this module already has the required privileges to run this update (<?php echo implode(", ", $update["privileges"]); ?>).</p>
  <?php } else { ?>
    <p>To run this update, please enter the username and password for a MySQL user with the following privileges: <?php echo implode(", ", $update["privileges"]); ?></p>
    <p>This update will use the host and schema specified in your configuration file.</p>
    
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
			<tr>
				<td>&nbsp;Database Username</td>
				<td>
					<input type="text" name="database_username" size="30" value="<?php echo htmlspecialchars($database_username); ?>">
				</td>
			</tr>
			<tr>
				<td>&nbsp;Database Password</td>
				<td>
					<input type="password" name="database_password" size="30" value="<?php echo htmlspecialchars($database_password); ?>">
				</td>
			</tr>
		</table>
	<?php } ?>

	<input type="submit" value="Run Update <?php echo $version; ?>" name="submit">
</form>
<?php
$installer->footer()
?>