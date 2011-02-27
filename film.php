<?php
require("configure.php");
require("library.php");
session_start();
page_start("Project Lumi&eacute;re - Film");

echo "<p align='center'><H2>$_GET[titolo]</H2></p>";

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	# echo "<center>";
	$query="SELECT * from Film WHERE titolo='$_GET[titolo]'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$film=mysql_fetch_array($result);
	$id_film=$film['id_film'];
	$visto=$film['visto'];
	
	if ($visto==0)	# Se il film NON è stato visto
		echo "Il film non &egrave; stato ancora visto, quindi non si pu&ograve; ancora votare!";
	else	# Se è stato visto
	{
		echo "Ti &egrave; piaciuto il film? Dagli un voto!";

		echo "<form method=POST action=film2.php?idfilm=$id_film>";
		echo "<select name='voto' onchange='this.form.submit()'";
		echo "<OPTION value=' - Dai un Voto! - '>VOTA!</OPTION>";
		for ($i=0; $i<=10; $i++) 
		{
			if ($i==0)
				echo "<OPTION value=' - Dai un Voto! - '>VOTA!</OPTION>";
			else
	                        echo "<option value=$i>$i</option>";
		}
		echo "</input></select></form>";
	}
}



