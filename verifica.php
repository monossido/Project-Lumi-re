<?php

require("configure.php");
require("library.php");
session_start();

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);
$username=$_GET['nome'];
$query="SELECT * FROM Utenti WHERE Username='$username'";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$dati=mysql_fetch_array($result);

$verificato=$dati['verificato'];
$code = $dati['temp'];
$code2=$_GET['code'];

/* echo "Username="; echo $username;
echo "<br>Codice2="; echo $code2; 
echo "<br>Codice1="; echo $code;
echo "<br>Codice1="; echo $dati['temp']; echo"<br>";
echo "verificato="; echo $verificato; */

if (!$verificato&&($code==$code2))
{
	$query2="UPDATE Utenti SET verificato=1 WHERE Username='$username'";
	mysql_query($query2, $conn)
	  or die("Query fallita! " . mysql_error());	
	echo "<center>Account verificato! Torna alla <a href=http://monossido.ath.cx/bau/cinema/index.php>Home</a></center>";
}
else
	echo "Il tuo account risulta gi&agrave; verificato!";
# echo "vai"; echo "<br>";

?>
