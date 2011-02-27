<?php
require("configure.php");
require("library.php");
session_start();


$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	$id_film=$_GET['idfilm'];
	# echo $id_film;
	$query="SELECT * FROM Film WHERE id_film='$id_film'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$film=mysql_fetch_array($result);
	# echo $film;

	echo "<HTML><HEAD><TITLE>$film[titolo]</TITLE></HEAD><BODY><HR /><br />
	<center><H2>$film[titolo]</H2></center>";
	

	$voto=$_POST['voto'];
	
	if ($voto==10)
		echo "<font color=red>WOW! Stai per dare il MASSIMO dei voti al film!</font> Sei proprio sicuro che lo meriti? :)";
	if ($voto==1)
		echo "<font color=red>WOW! Stai per dare il MINIMO dei voti al film!</font> Sei proprio sicuro che lo meriti? :)";		
	if ($voto>=2 && $voto<=9)
		echo "Vuoi mettere $voto al film?";
	echo "<form method=POST action=film3.php?id_film=$id_film&voto=$voto><input type=submit value=SI!>";	
}

