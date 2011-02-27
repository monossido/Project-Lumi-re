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
		if ($_POST['submit'])
		{
			echo "cambio";
			# Preleva film che sono stati votati 
			# $query2="UPDATE Film SET passato=1, voti=0 WHERE voti>=1";
			#	mysql_query($query2, $conn);
			# Fine prelievo
			
			# Imposto il voto di TUTTI gli utenti a 1
			$query3="UPDATE Utenti SET voto=1";
			$result3=mysql_query($query3, $conn)
			  or die("Query fallita!" . mysql_error());
			# $film=mysql_fetch_array($result2);
			
			# Riempio Tabella
			$query4="SELECT TOP 3 * FROM Film WHERE voti>=1 ORDER BY voti DESC";
			$result4=mysql_query($query4, $conn)
			  or die("Query fallita!" . mysql_error());
			$film=mysql_fetch_array($result4);
			
			echo "<TABLE width=\"100%\" border cellpadding=\"5\"><th>Titolo</th><th>Risoluzione</th><th>Lingua</th><th>Durata</th><th>Vota</th>";
			while($film=mysql_fetch_array($result4))
			{
				echo "<tr><td><a href=film.php?titolo=".$film['titolo'].">".$film['titolo']."</a></td><td>".$film['risoluzione']."</td><td>".$film['lingua']."</td><td>".$film['durata']."</td>";
				$query5="SELECT Voto FROM Utenti WHERE Username='".$_SESSION['logged']."'";
				$result5=mysql_query($query5, $conn)
				  or die("Query fallita!" . mysql_error());
				$voto=mysql_fetch_array($result5);
				if(isset($_SESSION['logged']) && $voto['Voto']=="1")
					echo "<td><a href='vota.php?id=".$row['id_film']."'>Vota</a></td>";
				else echo "<td width='8%'><em>Non puoi votare</em></td>";
				echo "</tr>";
			}
			echo "</table>";
		echo "<br /><form method=POST action=admin2.php><input type=submit value=AZZERA></form>";
		}
		else
		{
			echo "<p align='right'><font color=red>ADMIN</font></p>";
			echo "<form method=POST action=admin.php><input type=submit name=submit value=BLOCCA-VOTI></form>";
		}
	}
	
	else	# Se NON è amministratore
		echo "Non sei un Amministratore!";		
}

else # Se l'utente deve ancora fare il login
	echo "Devi prima fare il <a href=login.php>login</a>";
echo "</center>";
