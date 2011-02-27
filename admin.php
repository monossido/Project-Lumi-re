<?php
require("configure.php");
require("library.php");
session_start();
page_start("Project Lumi&eacute;re - Admin Page");


$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	echo "<p align='center'>";
	$query="SELECT amministratore FROM Utenti WHERE Username='".$_SESSION['logged']."'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$admin=mysql_fetch_array($result);

	if ($admin['amministratore']==1) # Se l'utente è anche amministratore
	{
		if ($_POST['blocca'])
		{

			# Preleva film che sono stati votati 
			# $query2="UPDATE Film SET passato=1, voti=0 WHERE voti>=1";
			#	mysql_query($query2, $conn);
			# Fine prelievo

			# Imposto il round a 1 nella tabella Stato
			$query="UPDATE Stato SET Round=1";
			mysql_query($query, $conn)
			  or die("Query fallita!" . mysql_error());
			
			# Imposto il voto di TUTTI gli utenti a 1
			$query1="UPDATE Utenti SET voto=1";
			mysql_query($query1, $conn)
			  or die("Query fallita!" . mysql_error());

			
			# Riempio Tabella
			$query2="SELECT * FROM Film WHERE voti>=1 ORDER BY voti LIMIT 3";
			$result2=mysql_query($query2, $conn)
			  or die("Query fallita!" . mysql_error());
			
			echo "<TABLE width=\"100%\" border cellpadding=\"5\"><th>Titolo</th><th>Risoluzione</th><th>Lingua</th><th>Durata</th><th>Vota</th>";
			while($film=mysql_fetch_array($result2))
			{
				echo "<tr><td><a href=film.php?titolo=".$film['titolo'].">".$film['titolo']."</a></td><td>".$film['risoluzione']."</td><td>".$film['lingua']."</td><td>".$film['durata']."</td>";
				$query3="SELECT Voto FROM Utenti WHERE Username='".$_SESSION['logged']."'";
				$result3=mysql_query($query3, $conn)
				  or die("Query fallita!" . mysql_error());
				$voto=mysql_fetch_array($result3);
				if(isset($_SESSION['logged']) && $voto['Voto']=="1")
					echo "<td><a href='vota.php?id=".$row['id_film']."'>Vota</a></td>";
				else echo "<td width='8%'><em>Non puoi votare</em></td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<br /><form align='center' method=POST action=admin.php><input type=submit name=azzera value='Azzera voti' /></form>";
		}
		else if($_POST['azzera'])
		{
			$query2="UPDATE Utenti SET Voto=0";
				mysql_query($query2, $conn);
			$query3="UPDATE Film SET voti=0, passato=0";
				mysql_query($query3, $conn);
			echo "Ho Azzerato Tutto";
		}
		else
		{
			echo "<form align='center' method=POST action=admin.php><input type=submit name=blocca value='Blocca Voti' /></form>";
		}
	}
	else	# Se NON è amministratore
		echo "Non sei un Amministratore!";		
}
else # Se l'utente deve ancora fare il login
	echo "Devi prima fare il <a href=login.php>login</a>";
echo "</p>";

page_end();
?>
