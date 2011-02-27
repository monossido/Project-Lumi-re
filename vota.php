<?php
require("configure.php");
require("library.php");
session_start();
page_start("Movie Manager - Vota");

if(isset($_SESSION['logged']))
{
	$id=$_GET['id'];
	$conn=mysql_connect(dbhost, dbuser, dbpwd)
	    or die("Connessione al server MySQL fallita!");
	  mysql_select_db(dbname);

	$query="SELECT * FROM Film WHERE id_film='$id'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());

	$row=mysql_fetch_array($result);
	echo $row['titolo'];

	echo "<br />Confermi il voto?<br />
	<form method=POST action=conferma.php?id=$id>
	<input type=submit name=conferma value=conferma></form>";
}

?>
