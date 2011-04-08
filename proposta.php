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
 *      along with Project Lumiére.  If not, see <http://www.gnu.org/licenses/>.
 *      
 */
require("configure.php");
require("library.php");
session_start();
page_start("Project Lumi&eacute;re - Film");

echo "<br>";
$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

$query5="SELECT * FROM Utenti WHERE Username='$_SESSION[logged]'";
$result5=mysql_query($query5, $conn)
   or die("Query fallita!" . mysql_error());
$ris2=mysql_fetch_array($result5);
$id_utente=$ris2['id_utente'];

if (!$_POST['conferma'])
{
	$query3="SELECT * FROM Proposte";
	$result3=mysql_query($query3, $conn)
	   or die("Query fallita! " . mysql_error());

	echo "<TABLE width=\"60%\" align=center border cellpadding=\"5\"><th>Titolo</th><th>Numero Voti</th><th>Vota</th>";
	while($ris=mysql_fetch_array($result3))
	{
		echo "<tr><td>".$ris['titolo']."</td><td width=10%>".$ris['voto']."</td>";

		if ($_SESSION['logged'])
		{
			$query4="SELECT * FROM ProposteVoti WHERE id=".$ris['id_proposta']." AND id_utente='$id_utente'";
			$result4=mysql_query($query4, $conn)
			  or die("Query fallita!" . mysql_error());
			$ris2=mysql_fetch_array($result4);
			$id=$ris2['id_proposta'];	// PRELEVA L'ID DELLA PROPOSTA
			if ($id!=0)	// SE L'ID DELLA VOTAZIONE E' DIVERSO DA 0 VUOL DIRE CHE L'UTENTE HA VOTATO QUEL FILM	
				echo "<td width=40%>Hai gi&agrave votato per proporre questo film!</a>";
			else
				echo "<td width=10%><a href=proposta2.php?id=".$ris['id_proposta'].">Vota</a></td>";
		}
		else
			echo "<td width=30%>Devi fare il login</a></td>";
		echo "</tr>";
	}
	echo "</table>";
}	
if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	if (!$_POST['conferma'])
	{
		echo "<center><b><br>Vuoi proporre un film?</b></center>";
		echo "<p align=center>";
		echo "<table border=0";
		echo "<form method=POST action=proposta.php>
			<tr><td>Titolo Film </td><td><input type=text name=titolo></td><td><input type=submit name=conferma value=Inserisci></td></tr></form></table>";
		echo "</p>";
	}
	else
	{
		echo "Film Aggiunto con successo! <a href=proposta.php>Controlla quanti voti sta avendo!</a>";
		$titolo=$_POST['titolo'];
		$query="SELECT * FROM Utenti WHERE username='$_SESSION[logged]'";
		$result=mysql_query($query, $conn)
		  or die("Query fallita!" . mysql_error());
		$utente=mysql_fetch_array($result);	
		$id_utente=$utente['id_utente'];

		$query2="INSERT INTO Proposte (titolo, id_utente) VALUES('$titolo','$id_utente')";
		$result2=mysql_query($query2, $conn)
		  or die("Query fallita!" . mysql_error());
	}
}

else
	echo "<br><br><center>Per proporre un film devi prima essere <a href=login.php>loggato</a>.</center>";

		
		

