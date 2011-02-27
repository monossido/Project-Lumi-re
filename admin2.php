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

echo "<center>";
if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	$query="SELECT amministratore FROM Utenti WHERE Username='".$_SESSION['logged']."'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$admin=mysql_fetch_array($result);

	if ($admin['amministratore']==1) # Se l'utente è anche amministratore
	{
		$query2="UPDATE Utenti SET Voto=0";
			mysql_query($query2, $conn);
		$query3="UPDATE Film SET voti=0, passato=0";
			mysql_query($query3, $conn);
		echo "Ho Azzerato Tutto";
	}
	else	# Se NON è amministratore
		echo "Non sei un Amministratore!";		
}
else # Se l'utente deve ancora fare il login
	echo "Devi prima fare il <a href=login.php>login</a>";
echo "</center>";
		




