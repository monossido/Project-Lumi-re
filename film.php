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
	if (!$_POST['voto']&&!$_POST['sicuro']) // Se entra nella pagina per la prima volta:
	{
		# echo "<center>";
		$query="SELECT * from Film WHERE titolo='$_GET[titolo]'";
		$result=mysql_query($query, $conn)
		  or die("Query fallita!" . mysql_error());
		$film=mysql_fetch_array($result);
		$id_film=$film['id_film'];
		$visto=$film['visto'];
		echo "visto="; echo $visto;
		if ($visto==0)	# Se il film NON è stato visto
			echo "Il film non &egrave; stato ancora visto, quindi non si pu&ograve; ancora votare!";
		else	# Se è stato visto
		{
			echo "Ti &egrave; piaciuto il film? Dagli un voto!";
	
			echo "<form method=POST action=film.php?idfilm=$id_film>";
			echo "<select name='voto' onchange='this.form.submit()'";
			echo "<OPTION value=' - Dai un Voto! - '>VOTA!</OPTION>";
			for ($i=0; $i<=10; $i++) 
			{
				if ($i==0)
					echo "<OPTION value=' - Dai un Voto! - '>VOTA!</OPTION>";
				else
		                        echo "<option value=$i name=voto>$i</option>";
			}
			echo "</input></select></form>";
		}
	}
	if ($_POST['voto']) // Se ha selezionato un voto:
	{
		echo "POST[voto]="; echo $_POST['voto'];
		$id_film=$_GET['idfilm'];
		echo "id film="; echo $id_film;
		$query="SELECT * FROM Film WHERE id_film='$id_film'";
		$result=mysql_query($query, $conn)
		  or die("Query fallita!" . mysql_error());
		$film=mysql_fetch_array($result);
		# echo $film['titolo'];
	
		echo "<HTML><HEAD><TITLE>$film[titolo]</TITLE></HEAD><BODY><HR /><br />
		<center><H2>$film[titolo]</H2></center>";

		$voto=$_POST['voto'];
		
		if ($voto==10)
			echo "<font color=red>WOW! Stai per dare il MASSIMO dei voti al film!</font> Sei proprio sicuro che lo meriti? :)";
		if ($voto==1)
			echo "<font color=red>WOW! Stai per dare il MINIMO dei voti al film!</font> Sei proprio sicuro che lo meriti? :)";		
		if ($voto>=2 && $voto<=9)
			echo "Vuoi mettere $voto al film?";
		echo "<form method=POST action=film.php?id_film=".$id_film."&voto=".$voto."><input type=submit name=sicuro value='SI!'></input></form>";	
	}
	if ($_POST['sicuro'])
	{	
		// echo "ok";
		$voto=$_GET['voto'];
		$id_film=$_GET['id_film'];
		// echo $voto;
		// echo $id_film;
		$query="UPDATE Film SET giudizio='$voto' WHERE id_film='$id_film'";
		mysql_query($query, $conn)
		  or die("Query fallita!" . mysql_error());
		echo "Complimenti! Hai votato il film!";
	}
}
	
else
	echo "Devi prima fare il <a href=login.php>Login</a>";


