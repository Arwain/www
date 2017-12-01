<?php

	function get_connection() {
		$userid   = 'bmsg'; //Change this to yours
		$host     = 'cssgate.insttech.washington.edu';
		$dbname   = 'bmsg'; //Change this to yours
		
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