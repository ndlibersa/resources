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

class AliasType extends DatabaseObject {

      protected function defineRelationships() {
            
      }

      protected function overridePrimaryKeyName() {
            
      }

      //returns number of children for this particular alias type
      public function getNumberOfChildren() {

            $query = "SELECT count(*) childCount FROM Alias WHERE aliasTypeID = '" . $this->aliasTypeID . "';";

            $result = $this->db->processQuery($query, 'assoc');

            return $result['childCount'];
      }

      public static function getAliasTypeID($type) {
           $object = new AliasType();
            $query = "SELECT aliasTypeID FROM AliasType WHERE upper(shortName) = '" . str_replace("'", "''", strtoupper($type)) . "'";

            $result = $object->db->processQUery($query);

            if (count($result) == 0) {
                  $id = null;
            } else {
                  $id = $result[0];
            }
            return $id;
      }

}

?>