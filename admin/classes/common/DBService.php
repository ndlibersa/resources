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


class DBService extends Object {

	protected $db;
	protected $config;
	protected $error;

	protected function init(NamedArguments $arguments) {
		parent::init($arguments);
		$this->config = new Configuration;
		$this->connect();
	}

	protected function dealloc() {
		$this->disconnect();
		parent::dealloc();
	}

	protected function checkForError() {
		if ($this->error = mysql_error($this->db)) {
			throw new Exception("There was a problem with the database: " . $this->error);
		}
	}

	protected function connect() {
		$host = $this->config->database->host;
		$username = $this->config->database->username;
		$password = $this->config->database->password;
		$this->db = mysql_connect($host, $username, $password);
		$this->checkForError();

		$databaseName = $this->config->database->name;
		mysql_select_db($databaseName, $this->db);
		$this->checkForError();
	}

	protected function disconnect() {
		//mysql_close($this->db);
	}

	public function escapeString($value) {
		return mysql_real_escape_string($value);
	}

	public function processQuery($sql, $type = NULL) {
    	//echo $sql. "\n\n";
		$result = mysql_query($sql, $this->db);
		$this->checkForError();
		$data = array();

		if (is_resource($result)) {
			$resultType = MYSQL_NUM;
			if ($type == 'assoc') {
				$resultType = MYSQL_ASSOC;
			}
			while ($row = mysql_fetch_array($result, $resultType)) {
				if (mysql_affected_rows($this->db) > 1) {
					array_push($data, $row);
				} else {
					$data = $row;
				}
			}
			mysql_free_result($result);
		} else if ($result) {
			$data = mysql_insert_id($this->db);
		}

		return $data;
	}

}

?>