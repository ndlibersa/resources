<?php
include_once 'CORALInstaller.php';
$installer = new CORALInstaller();

if (!$installer->installed()) {
  header('Location: install.php');
  exit;
} else if ($next_version = $installer->getNextUpdateVersion()) {
  header('Location: update.php?version='.$next_version);
  exit;
}

$installer->header('CORAL Maintenance');
?>
  <?php $installer->displayMessages(); ?>
  <?php $installer->displayErrorMessages(); ?>
  <h3>CORAL Resources</h3>
	<p>Your CORAL Resources Module is correctly installed and there are no pending updates.</p>
  <p><a href="..">Go to Resources Module</a></p>
<?php
$installer->footer();
?>