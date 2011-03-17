<?php
/*
**************************************************************************************************************************
** CORAL Resources Module v. 1.0
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


class LdapPerson extends DynamicObject {

	public function __construct($userKey) {

		$config = new Configuration;

		//try to connect to ldap if the settings are entered
		if ($config->ldap->host) {

			//If you are using OpenLDAP 2.x.x you can specify a URL instead of the hostname. To use LDAP with SSL, compile OpenLDAP 2.x.x with SSL support, configure PHP with SSL, and set this parameter as ldaps://hostname/.
			//note that connect happens regardless if host is valid
			$ds = ldap_connect($config->ldap->host);

			//may need ldap_bind( $ds, $username, $password )
			$bd = ldap_bind($ds) or die("<br /><h3>Could not connect to " . $config->ldap->host . "</h3>");

			if ($bd){
				$filter = $config->ldap->search_key . "=" . $userKey;

				$sr = ldap_search($ds, $config->ldap->base_dn, $filter);

				if ($entries = ldap_get_entries($ds, $sr)) {
					$entry = $entries[0];

					$fieldNames = array('fname', 'lname', 'email', 'phone', 'department', 'title', 'address');

					foreach ($fieldNames as $fieldName) {
						$configName = $fieldName . '_field';

						$this->$fieldName = $entry[$config->ldap->$configName][0];

					}
					$this->fullname = addslashes($this->fname . ' ' . $this->lname);

				}

				ldap_close($ds);

			}
		}
	}

}

?>