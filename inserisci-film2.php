<?php
require("configure.php");
require("library.php");
session_start();

echo "<HTML>
<HEAD><TITLE>ADMIN</TITLE></HEAD>
<BODY>
<HR />
<H2>ADMIN PAGE</H2>";
echo "<p align=right><a href=index.php>HOME</a></p>";

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	echo "<center>";
	$query="SELECT amministratore FROM Utenti WHERE Username='".$_SESSION['logged']."'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$admin=mysql_fetch_array($result);

	if ($admin['amministratore']==1) # Se l'utente è anche amministratore
	{
	# qui

	$titolo=$_POST['titolo'];
	$durata=$_POST['durata'];
	if ($_POST['visto']=="si") $visto=1;
	else	$visto=0;
	$query10="INSERT INTO Film (titolo, visto, risoluzione, durata, lingua) VALUES('$titolo','$visto','bluray','$durata', 'tutte')";
	mysql_query($query10, $conn)
	  or die("Query fallita! " . mysql_error());
	# Fine qui
	echo "Inserimento Compiuto! Torna alla <a href=index.php>HOME</a>";
	}
	else
		echo "Non sei un amministratore";
}
else
	echo "Devi prima fare il login";


