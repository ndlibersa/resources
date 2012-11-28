</div>
<?php
if ($installer && !$installer->upToDate()) {
  echo $installer->debuggingNotes();
}
?>
</body>
</html>
