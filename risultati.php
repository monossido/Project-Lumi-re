<?php
require("configure.php");
require("library.php");
include "libchart/classes/libchart.php";
session_start();
page_start("Project Lumi&eacute;re - Risultati");

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

# Stampa tabella film votati
	# Riempio Tabella
	$query4="SELECT * FROM Film WHERE passato=1";
	$result4=mysql_query($query4, $conn)
	  or die("Query fallita!" . mysql_error());
	$film=mysql_fetch_array($result4);

	echo "<TABLE width=\"100%\" border cellpadding=\"5\"><th>Titolo</th><th>Risoluzione</th><th>Lingua</th><th>Durata</th><th>Vota</th>";
	while($film=mysql_fetch_array($result4))
	{
		echo "<form action=film.php method=get><tr><td><a href=film.php?titolo=".$film['titolo'].">".$film['titolo']."</a></td><td>".$film['risoluzione']."</td><td>".$film['lingua']."</td><td>".$film['durata']."</td>";
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


# Grafico

    $chart = new HorizontalBarChart(900, 300);
    $dataSet = new XYDataSet();

    $query="SELECT titolo,voti FROM Film WHERE voti>0";
    $result=mysql_query($query, $conn)
 	 or die("Query fallita!" . mysql_error());

    while($row=mysql_fetch_array($result))
    {
    	$dataSet->addPoint(new Point($row['titolo'], $row['voti']));
    }

    $chart->setDataSet($dataSet);
    $chart->getPlot()->setGraphPadding(new Padding(5, 15, 15, 200));
    $chart->setTitle("Risultati parziali film");
    $chart->render("grafici/grafico1.png");

    echo '<img src="grafici/grafico1.png" alt="grafico" />';


?>
