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
		echo "<center>";
		echo "<table border=0>";
		echo "<form method=POST action=inserisci-film2.php>";
		echo "<tr><td>Titolo</td><td><input type=text name=titolo></tr></td>
			<tr><td>Visto</td><td><input type=text name=visto></tr></td>
			<tr><td>Durata</td><td><input type=text name=durata></tr></td>
			<tr><td><input type=submit name=submit value=Inserisci></form></tr></td>";
		echo "</center>";
	}
	else	echo "Non sei un amministratore!";
}
else	echo "Devi prima loggarti";	




