<?php

	function get_connection() {
		$userid   = 'ak99'; //Change this to yours
		$password = 'ekfuxvaw'; //Change this to yours
		$userid   = 'khoadoan'; //Change this to yours
		$password = 'Kh04MySQLPr0'; //Change this to yours
		$host     = 'cssgate.insttech.washington.edu';
		$dbname   = 'ak99'; //Change this to yours
		$dbname   = 'khoadoan'; //Change this to yours
		
		$dsn = 'mysql:host='.$host. ';dbname='.$dbname;
		
		try {
		    $db = new PDO($dsn, $userid, $password);
		}
		catch(PDOException $e) {
			echo "Error connecting to database";
	    }
	    return $db;
	}
?>