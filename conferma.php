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
page_start("Project Lumi&eacute;re - Vota - Conferma");

$id=$_GET['id'];
$round=$_GET['round'];

  $conn=mysql_connect(dbhost, dbuser, dbpwd)
    or die("Connessione al server MySQL fallita!");
  mysql_select_db(dbname);


$query="SELECT Voto FROM Utenti WHERE Username='".$_SESSION['logged']."'";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$voto=mysql_fetch_array($result);

if((isset($_SESSION['logged'])) && ($voto['Voto']==$round) && ($id))
{ 
	$query="UPDATE Film SET voti=voti+1 WHERE id_film='$id'";//Aggiorno voti film
	$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());


	$id_utente=user_to_id($_SESSION['logged']);//Salvo la votazione nella tabella Voti
	$query="INSERT INTO Voti (IdUtente,IdFilm,Round,UltimaVotazione,Data) VALUES ('$id_utente','$id','$round',1,NOW())";
	mysql_query($query, $conn)
  		or die("Query fallita!" . mysql_error());

	$query2="UPDATE Utenti SET Voto=Voto+1 WHERE Username='".$_SESSION['logged']."'";//Aggiorno voto utente
	mysql_query($query2, $conn)
  		or die("Query fallita!" . mysql_error());
	echo "Voto confermato!";
}else
{
	echo "Hai gia' votato brutto stronzo.";
}

?>
