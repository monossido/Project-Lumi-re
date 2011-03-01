<?php
/*
 *      This file is part of Project Lumiére <http://monossido.ath.cx/cinema>
 *      
 *      Project Lumiére is free software: you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation, either version 3 of the License, or
 *      (at your option) any later version.
 *      
 *      Project Lumiére  is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with Transdroid.  If not, see <http://www.gnu.org/licenses/>.
 *      
 */
require("configure.php");
require("library.php");
session_start();
page_start("Project Lumi&eacute;re - Admin Page");


$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	$query="SELECT amministratore FROM Utenti WHERE Username='".$_SESSION['logged']."'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$admin=mysql_fetch_array($result);

	if ($admin['amministratore']==1) # Se l'utente è anche amministratore
	{
		if ($_POST['blocca1'])
		{

			# Preleva film che sono stati votati 
			# $query2="UPDATE Film SET passato=1, voti=0 WHERE voti>=1";
			#	mysql_query($query2, $conn);
			# Fine prelievo
		
			$filmNum=get_film();
			
			# Imposto il round a 1 nella tabella Stato
			$query="UPDATE Stato SET Round=1";
			mysql_query($query, $conn)
			  or die("Query fallita!" . mysql_error());
			
			# Imposto il voto di TUTTI gli utenti a 1
			$query1="UPDATE Utenti SET voto=1";
			mysql_query($query1, $conn)
			  or die("Query fallita!" . mysql_error());
			if($filmNum==1)
			{
				# Imposto le votazioni chiuse
				$query1="UPDATE Stato SET VotazioniAperte=0";
				mysql_query($query1, $conn)
				  or die("Query fallita!" . mysql_error());
			}

			$queryPassato="Update Film SET passato=1 WHERE id_film=(SELECT id_film FROM (Select * FROM Film) AS temp WHERE voti>=1 ORDER BY voti DESC LIMIT ".$filmNum.")";
			mysql_query($queryPassato, $conn)
			  or die("Query fallita!" . mysql_error());

			# Riempio Tabella
			$query2="SELECT * FROM Film WHERE passato=1";
			$result2=mysql_query($query2, $conn)
			  or die("Query fallita!" . mysql_error());
			
			echo "<TABLE width=\"100%\" align=center border cellpadding=\"5\"><th>Titolo</th><th>Risoluzione</th><th>Lingua</th><th>Durata</th><th>Vota</th>";
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
			$query="SELECT * FROM Stato";
			$result=mysql_query($query, $conn)
			  or die("Query fallita!" . mysql_error());
			$stato=mysql_fetch_array($result);
			echo "<p align='center'><b>Round attuale: ".$stato['Round']." round</b>";
			
			if($stato['Round']==1)
			{
				echo "<br />Blocca voti primo round:";
				echo "<form align='center' method=POST action=admin.php><input type=submit disabled name=blocca1 value='Blocca' /></form>";
				echo "<p align='center'>Blocca voti secondo round:";
				echo "<form align='center' method=POST action=admin.php><input type=submit name=blocca2 value='Blocca' /></form>";
			}else
			{
				echo "<br />Blocca voti primo round:";
				echo "<form align='center' method=POST action=admin.php><input type=submit name=blocca1 value='Blocca' /></form>";
				echo "<p align='center'>Blocca voti secondo round:";
				echo "<form align='center' method=POST action=admin.php><input type=submit disabled name=blocca2 value='Blocca' /></form>";
			}
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
