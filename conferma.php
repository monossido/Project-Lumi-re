<?php
require("configure.php");
require("library.php");
session_start();
page_start("Project Lumi&eacute;re - Vota - Conferma");

$id=$_GET['id'];

  $conn=mysql_connect(dbhost, dbuser, dbpwd)
    or die("Connessione al server MySQL fallita!");
  mysql_select_db(dbname);


$query="SELECT Voto FROM Utenti WHERE Username='".$_SESSION['logged']."'";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$voto=mysql_fetch_array($result);

if((isset($_SESSION['logged'])) && ($voto['Voto']=="0") && ($id))
{ 
	$query="UPDATE Film SET voti=voti+1 WHERE id_film='$id'";
	$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
	$query="UPDATE Utenti SET Voto=1 WHERE Username='".$_SESSION['logged']."'";
	$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
echo "Voto confermato!";
}else
{
	echo "Hai gia' votato brutto stronzo.";
}

?>
