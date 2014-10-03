<?php

/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.2
**
** Copyright (c) 2010 University of Notre Dame
**
** This file is part of CORAL.
**
** CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
**
** CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
**
**************************************************************************************************************************
*/


session_start();

include_once 'directory.php';

//print header
$pageTitle='Resources import';
include 'templates/header.php';

// CSV configuration
$delimiter = ';';
$required_columns = array('titleText' => 0, 'resourceURL' => 0, 'ISSN' => 0, 'providerText' => 0);

if ($_POST['submit']) {
  $uploaddir = 'attachments/';
  $uploadfile = $uploaddir . basename($_FILES['uploadFile']['name']);
  if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile)) {  
    print '<p>The file has been successfully uploaded</p>';
  
  // Let's analyze this file
  if (($handle = fopen($uploadfile, "r")) !== FALSE) {
    $row = 1;
    while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
      if ($row == 1) {
        $columns_ok = true;
        foreach ($required_columns as $required_column => $value) {
          if (false !== ($position = array_search($required_column, $data))) {
          } else {
            $columns_ok = false;
          }
        }
        if ($columns_ok == false) {
          $error = "One of the required columns has not been found";
          break;
        } 
      } else {
        // Let's insert data
        $resource = new Resource(); 
        $resource->createLoginID    = $loginID;
        $resource->createDate       = date( 'Y-m-d' );
        $resource->updateLoginID    = '';
        $resource->updateDate       = '';
        $resource->titleText        = $data[$required_columns['titleText']];
        $resource->isbnOrISSN       = $data[$required_columns['ISSN']];
        $resource->resourceURL      = $data[$required_columns['resourceURL']];
        $resource->providerText     = $data[$required_columns['providerText']];
        $resource->statusID         = 1;
        
        $resource->save();
      }
      $row++;  
    }
  } else {
    $error = 'Unable to open the uploaded file';
  }

  } else {
    $error = 'Unable to upload the file';
  }

  if ($error) {
    print "<p>Error: $error.</p>";
  } else {
    print "<p>" . ($row - 1) . " rows have been inserted";
  }
} else {
?>
<form enctype="multipart/form-data" action="import.php" method="post" id="importForm">
  <label for="uploadFile">CSV File</label>
  <input type="file" name="uploadFile" id="uploadFile" />
  <input type="submit" name="submit" value="Upload" />
</form>

<?php
}

//print footer
include 'templates/footer.php';
?>
