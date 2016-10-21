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
		if ($this->error = $this->db->error) {
			throw new Exception(_("There was a problem with the database: ") . $this->error);
		}
	}

	protected function connect() {
		$host = $this->config->database->host;
		$username = $this->config->database->username;
		$password = $this->config->database->password;
		$this->db = new mysqli($host, $username, $password);
		$this->checkForError();
        $this->db->set_charset('utf8');

		$databaseName = $this->config->database->name;
        $this->db->select_db($databaseName);
		$this->checkForError();
	}

	protected function disconnect() {
		//mysqli_close($this->db);
	}

	public function escapeString($value) {
        return $this->db->real_escape_string($value);
	}

    public function query($sql) {
        $result = $this->db->query($sql);
        $this->checkForError();
        return $result;
    }

	public function processQuery($sql, $type = NULL) {
    	//echo $sql. "\n\n";
		$result = $this->db->query($sql);
		$this->checkForError();
		$data = array();

		if ($result instanceof mysqli_result) {
			$resultType = MYSQLI_NUM;
			if ($type == 'assoc') {
				$resultType = MYSQLI_ASSOC;
			}
			while ($row = $result->fetch_array($resultType)) {
				if ($this->db->affected_rows > 1) {
					array_push($data, $row);
				} else {
					$data = $row;
				}
			}
			$result->free();
		} else if ($result) {
			$data = $this->db->insert_id;
		}

		return $data;
	}

	/**
	 * Version of processQuery() that deals with prepared statements. As prepared
	 * statements use variadic functions, much of this function's complexity
	 * comes from wrapping a variadic function in a PHP 5.5 compatible way.
	 *
	 * @param string $query Same format as mysqli::prepare(), with usually one
	 * or more "?" inside it.
	 *
	 * @param string $type Must be "num" or "assoc". Contrary to processQuery(),
	 * it's not an optionnal argument. Due to this function being variadic.
	 *
	 * @param mixed ...$paramsToBind Same format as mysqli_stmt::bind_param()
	 * It's a variadic function based on this PHP 5.5 compatible implementation
	 * https://wiki.php.net/rfc/variadics#introduction
	 * We will be able to simplify this once we require PHP 5.6
	 * https://secure.php.net/manual/en/migration56.new-features.php#migration56.new-features.variadics
	 */
	public function processPreparedQuery($query, $type) {
		$paramsToBind = array_slice(func_get_args(), 2); // additional arguments
		// prepared statements specific code
		$statement = $this->db->prepare($query);
		$this->checkForError();
		// The following is an implementation of the splat operator. This
		// will be simpler with PHP 5.6
		// https://secure.php.net/manual/en/migration56.new-features.php#migration56.new-features.splat
		// We need to pass references to bind_param(), hence the use of refValues()
		call_user_func_array([$statement, "bind_param"], self::refValues($paramsToBind)) ;
		$statement->execute();
		$result = $statement->get_result();

		// same as processQuery()
		$this->checkForError();
		$data = array();

		if ($result instanceof mysqli_result) {
			$resultType = MYSQLI_NUM;
			if ($type == 'assoc') {
				$resultType = MYSQLI_ASSOC;
			}
			while ($row = $result->fetch_array($resultType)) {
				if ($this->db->affected_rows > 1) {
					array_push($data, $row);
				} else {
					$data = $row;
				}
			}
			$result->free();
		} else if ($result) {
			$data = $this->db->insert_id;
		}

		return $data;
	}

	private static function refValues($arr){
		$refs = array();
		foreach($arr as $key => $value) {
			$refs[$key] = &$arr[$key];
		}
		return $refs;
	}
}

?>
