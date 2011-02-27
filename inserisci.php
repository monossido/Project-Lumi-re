<?php
require("configure.php");
$film=$_GET['titoli'];
$risoluzione=$_GET['ris'];
$lingua=$_GET['lin'];
$durata=$_GET['dur'];
	$conn=mysql_connect(dbhost, dbuser, dbpwd)
	    or die("Connessione al server MySQL fallita!");
	  mysql_select_db(dbname);

	$query="INSERT INTO Film (titolo, risoluzione, lingua, durata) VALUES ('$film','$risoluzione', '$lingua', '$durata') ";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());


?>
