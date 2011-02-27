<?php
require("configure.php");
require("library.php");
session_start();


echo "<HTML>
<HEAD><TITLE>$_GET[titolo]</TITLE></HEAD>
<BODY>
<HR />
<br />
<center><H2>$_GET[titolo]</H2></center>";

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	$voto=$_GET['voto'];
	$id_film=$_GET['id_film'];
	echo $voto;
	echo $id_film;
	$query="UPDATE Film SET giudizio='$voto' WHERE id_film='$id_film'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	mysql_fetch_array($result);
	echo "Complimenti! Hai votato il film!";
}




