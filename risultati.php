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
include "libchart/classes/libchart.php";
session_start();
page_start("Project Lumi&eacute;re - Risultati");

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

$query="SELECT * FROM Stato";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$stato=mysql_fetch_array($result);

if(!isset($_SESSION['logged']))
{
	echo '<p  align=center>Per vedere i risultati parziali devi essere loggato</p>';
}
else if($stato['VotazioniAperte'] || $stato['Round']>0)//Devo far vedere i risultati solo quando si è iniziata una votazione cioè round>0 oppure VotazioniAperte=1 e round=0
{
	$query5="SELECT Voto FROM Utenti WHERE Username='".$_SESSION['logged']."'";
	$result5=mysql_query($query5, $conn)
	  or die("Query fallita!" . mysql_error());
	$voto=mysql_fetch_array($result5);
	if($voto['Voto']>$stato['Round'])//Solo se l'utente ha già votato per quel round faccio vedere i risultati parziali
	{
		# Grafico
		$chart = new HorizontalBarChart(1100, 300);
		$dataSet = new XYDataSet();

		$query="SELECT titolo,voti FROM Film WHERE voti>0";
		$result=mysql_query($query, $conn)
		 or die("Query fallita!" . mysql_error());

		while($row=mysql_fetch_array($result))
		{
		$dataSet->addPoint(new Point($row['titolo'], $row['voti']));
		}

		$chart->setDataSet($dataSet);
		$chart->getPlot()->setGraphPadding(new Padding(10, 10, 10, 200));
		$chart->setTitle("Risultati parziali film");
		$chart->render("grafici/grafico1.png");

		echo '<p  align=center><img src="grafici/grafico1.png" alt="grafico" /></p>';
	}else
	{
		echo "<p  align=center>Per vedere i voti parziali devi aver votato per il round attualmente in corso
</p>";
	}

	if($stato['Round']==1 && $stato['VotazioniAperte']==1)
		echo "<br /><p align=center><b>Film passati al secondo turno:</b></p>";
	else if($stato['VotazioniAperte']==0)
		echo "<br /><p align=center><b>Ecco il film vincitore:</b></p>";
	if($stato['Round']==1 || $stato['VotazioniAperte']==0)
	{
		# Stampa tabella film votati
			# Riempio Tabella
			$query="SELECT * FROM Film WHERE passato=".$stato['Round']."";
			$result=mysql_query($query, $conn)
			  or die("Query fallita!" . mysql_error());

			echo "<TABLE width=\"55%\" border=1 align=center cellpadding=5><th>Titolo</th><th>Risoluzione</th><th>Lingua</th><th>Durata</th><th>Vota</th>";
			while($film=mysql_fetch_array($result))
			{
				echo "<form action=film.php method=get><tr><td><a href=film.php?titolo=".$film['id_film'].">".$film['titolo']."</a></td><td>".$film['risoluzione']."</td><td>".$film['lingua']."</td><td>".$film['durata']."</td>";
				if(isset($_SESSION['logged']) && $voto['Voto']=="1" && $stato['VotazioniAperte']==1)
					echo "<td><a href='vota.php?id=".$film['id_film']."&round=".$stato['Round']."'>Vota</a></td>";
				else echo "<td width='8%'><em>Non puoi votare</em></td>";
				echo "</tr>";
			}
			echo "</table>";

		//Film votati da...
			$query="SELECT IdUtente,IdFilm FROM Voti WHERE UltimaVotazione=1 AND Round=".($stato['Round']-1)." ORDER BY IdFilm";
			$result=mysql_query($query, $conn)
			  or die("Query fallita!" . mysql_error());
			while($data=mysql_fetch_array($result))
			{
				$ids_utenti[]=$data[0];
				$ids_film[]=$data[1];
			}
			
			echo "<p align=center>Tutti i film votati:</p>";

			echo "<TABLE width=\"55%\" border=1 align=center cellpadding=5><th>Titolo</th><th>Votati da</th>";
			for($i=0;$i<count($ids_utenti);$i++)
			{
					$query="SELECT * FROM Film WHERE id_film=".$ids_film[$i];
					$result=mysql_query($query, $conn)
				 		 or die("Query fallita!" . mysql_error());
					$film=mysql_fetch_assoc($result);
					echo "<form action=film.php method=get><tr><td><a href=film.php?titolo=".$film['id_film'].">".$film['titolo']."</a></td><td>".id_to_user($ids_utenti[$i]);
				while($ids_film[$i]==$ids_film[$i+1])
				{
					$i++;
					echo "<br />".id_to_user($ids_utenti[$i]);
				}
				echo "</td></tr>";
				
			}
			echo "</table>";

	}
}else
{
	echo "<p align=center><b>Nessuna notazione in corso</p></b>";
}
page_end();
?>
