<?php

/*
 * *************************************************************************************************************************
 * * CORAL Resources Module v. 1.0
 * *
 * * Copyright (c) 2010 University of Notre Dame
 * *
 * * This file is part of CORAL.
 * *
 * * CORAL is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * *
 * * CORAL is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 * *
 * * You should have received a copy of the GNU General Public License along with CORAL.  If not, see <http://www.gnu.org/licenses/>.
 * *
 * *************************************************************************************************************************
 */

class AcquisitionType extends DatabaseObject {

      protected function defineRelationships() {
            
      }

      protected function overridePrimaryKeyName() {
            
      }

      public function sortedArray() {
            $query = "SELECT * FROM AcquisitionType ORDER BY IF(UCASE(shortName)='PAID',1, 2), shortName asc";
            $result = $this->db->processQuery($query, 'assoc');

            $resultArray = array();
            $rowArray = array();

            if (isset($result['AcquisitionTypeID'])) {
                  foreach (array_keys($result) as $attributeName) {
                        $rowArray[$attributeName] = $result[$attributeName];
                  }
                  array_push($resultArray, $rowArray);
            } else {
                  foreach ($result as $row) {
                        foreach (array_keys($this->attributeNames) as $attributeName) {
                              $rowArray[$attributeName] = $row[$attributeName];
                        }
                        array_push($resultArray, $rowArray);
                  }
            }

            return $resultArray;
      }

      //returns number of children for this particular contact role
      public function getNumberOfChildren() {

            $query = "SELECT count(*) childCount FROM Resource WHERE acquisitionTypeID = '" . $this->acquisitionTypeID . "';";

            $result = $this->db->processQuery($query, 'assoc');

            return $result['childCount'];
      }

      public static function getAcquisitionTypeID($type) {
            $object = new AcquisitionType();
            $query = "SELECT acquisitionTypeID FROM AcquisitionType WHERE upper(shortName) = '" . str_replace("'", "''", strtoupper($type)) . "'";

            $result = $object->db->processQuery($query, 'assoc');

            if (count($result) == 0) {
                  $object->shortName = $type;
                  $object->save();               
                  $id = $object->acquisitionTypeID;
            } else {
                  $id = $result[0];
            }
            return $id;
      }

}

?>