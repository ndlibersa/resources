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


// Useful directory constants, ending with |/|.
define('ADMIN_DIR', dirname(__FILE__) . '/admin/');
define('BASE_DIR', dirname(__FILE__) . '/');
define('CLASSES_DIR', ADMIN_DIR . 'classes/');

// Automatically load undefined classes from subdirectories of |CLASSES_DIR|.
function __autoload( $className ) {
	if (file_exists(CLASSES_DIR) && is_readable(CLASSES_DIR) && is_dir(CLASSES_DIR)) {
		$directory = dir(CLASSES_DIR);

		// Iterate over the files and directories in |CLASSES_DIR|.
		while (false !== ($entry = $directory->read())) {
			$path = CLASSES_DIR . $entry;

			// Look only at subdirectories
			if (is_dir($path)) {
				$filename = $path . '/' . $className . '.php';
				if (file_exists($filename) && is_readable($filename) && is_file($filename)) {
					// Could probably safely use |require()| here, since |__autoload()| is only called when a class isn't loaded.
					require_once($filename);
				}
			}
		}
		$directory->close();
	}
}

// Add lcfirst() for PHP < 5.3.0
if (false === function_exists('lcfirst')) {
	function lcfirst($string) {
		return strtolower(substr($string, 0, 1)) . substr($string, 1);
	}
}


//fix default timezone for PHP > 5.3
if(function_exists("date_default_timezone_set") and function_exists("date_default_timezone_get")){
	@date_default_timezone_set(@date_default_timezone_get());
}





//commonly used to convert price into integer for insert into database
function cost_to_integer($price) {

    $price = preg_replace("/[^0-9\.]/", "", $price);

	$decimal_place = strpos($price,".");

    if (strpos($price,".") > 0) {
		$cents = '.' . substr($price, $decimal_place+1, 2);
        $price = substr($price,0,$decimal_place);
    }else{
    	$cents = '.00';
    }

    $price = preg_replace("/[^0-9]/", "", $price);

    if (is_numeric($price . $cents)){
    	return ($price . $cents) * 100;
    }else{
    	return false;
    }
}

//commonly used to convert integer into a price for display
function integer_to_cost($price) {
	//we know this is an integer
	if ($price > 0){
    	return number_format(($price / 100),2,'.',',');
    }else{
    	return "";
    }
}


function format_date($mysqlDate) {

	return date("m/d/Y", strtotime($mysqlDate));

}


?>