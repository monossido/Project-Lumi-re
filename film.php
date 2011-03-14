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

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

$query="SELECT * FROM Film WHERE id_film=".$_GET['id']."";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$film=mysql_fetch_array($result);
$id_film=$film['id_film'];
$visto=$film['visto'];
$titolo=$film['titolo'];
# echo $titolo;

echo "<p><H2><center>$titolo</H2></p></center>";

// MEDIA ARITMETICA DEI VOTI RICEVUTI DAL FILM
$query5="SELECT sum(voto) as somma FROM Votazioni WHERE id_film='$id_film'";
$result5=mysql_query($query5, $conn)
  or die("Query fallita!" . mysql_error());
$voti=mysql_fetch_array($result5);
$somma_tot=$voti['somma'];	// Somma totale dei voti ricevuti dal film		
// $num_voti=$voti['idvotazione'];
// echo "voti="; echo $voti['somma'];
$query6="SELECT count(*) as utenti FROM Votazioni WHERE id_film='$id_film'";
$result6=mysql_query($query6, $conn)
  or die("Query fallita!" . mysql_error());
$utenti=mysql_fetch_array($result6);
// echo "utenti="; echo $utenti['utenti'];
$n_utenti=$utenti['utenti'];	// Numero di utenti che ha votato il film
if ($n_utenti==0)	echo "Non ha ancora votato nessuno questo film.<br><br>";
else 
{
	$media=$somma_tot/$n_utenti;
	echo "<center>Voto medio del film: $media,";
	if ($n_utenti==1) echo " ha votato un solo utente.";
	else	echo " hanno votato in $n_utenti.";
	echo "<br>";
	for ($i=0;$i<$media;$i++)
		echo"* ";
	echo "</center>";
}
// FINE MEDIA

if(isset($_SESSION['logged'])) # Se l'utente è loggato
{
	if (!$_POST['voto']&&!$_POST['sicuro']) // Se entra nella pagina per la prima volta:
	{
		# echo "<center>";
		
		$query3="SELECT * FROM Utenti WHERE username='$_SESSION[logged]'";
		$result3=mysql_query($query3, $conn)
		  or die("Query fallita!" . mysql_error());
		$utente=mysql_fetch_array($result3);	
		$id_utente=$utente['id_utente'];
	
		// CONTROLLA SE L'UTENTE HA GIA' VOTATO IL FILM
		$query4="SELECT * FROM Votazioni WHERE id_film='$id_film' AND id_utente='$id_utente'";
		$result4=mysql_query($query4, $conn)
		  or die("Query fallita!" . mysql_error());
		$votazioni=mysql_fetch_array($result4);
		$voto=$votazioni['voto'];
		$id=$votazioni['idvotazione'];	// PRELEVA L'ID DELLA VOTAZIONE
		// echo "idvotazioni="; echo $id;
		if ($id!=0)	// SE L'ID DELLA VOTAZIONE E' DIVERSO DA 0 VUOL DIRE CHE L'UTENTE HA VOTATO QUEL FILM
			echo "Hai gi&agrave votato questo film e il tuo voto &egrave stato $voto";
		// FINE CONTROLLO
		else 
		{
			echo "Ti &egrave; piaciuto il film? Dagli un voto!";
			echo "<form method=POST action=film.php?id=$id_film>";
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
		// echo "ok";
		// echo "POST[voto]="; echo $_POST['voto'];
		// $id_film=$_GET['id'];
		// echo "id film="; echo $id_film;
		$query="SELECT * FROM Film WHERE id_film=$id_film";
		$result=mysql_query($query, $conn)
		  or die("Query fallita! " . mysql_error());
		$film=mysql_fetch_array($result);
		# echo $film['titolo'];

		$voto=$_POST['voto'];
		
		if ($voto==10)
			echo "<font color=red>WOW! Stai per dare il MASSIMO dei voti al film!</font> Sei proprio sicuro che lo meriti? :)";
		if ($voto==1)
			echo "<font color=red>WOW! Stai per dare il MINIMO dei voti al film!</font> Sei proprio sicuro che lo meriti? :)";		
		if ($voto>=2 && $voto<=9)
			echo "Vuoi mettere $voto al film?";
		echo "<form method=POST action=film.php?id=".$id_film."&voto=".$voto."><input type=submit name=sicuro value='SI!'></input></form>";	
	}
	if ($_POST['sicuro'])
	{	
		// echo "ok";
		$login=$_SESSION['logged'];
		$query2="SELECT * FROM Utenti WHERE Username='$login'";
		$result2=mysql_query($query2, $conn)
		  or die("Query fallita! " . mysql_error());
		$utente=mysql_fetch_array($result2);
		$id_utente=$utente['id_utente'];
		$voto=$_GET['voto'];
		$id_film=$_GET['id'];
		/* echo "id_utente="; echo $id_utente;
		echo "voto="; echo $voto;
		echo "id_film="; echo $id_film; */
		$query="INSERT INTO Votazioni (id_utente,id_film,voto) VALUES ('$id_utente', '$id_film','$voto')";
		mysql_query($query, $conn)
		  or die("Query fallita! " . mysql_error());
		echo "Complimenti! Hai votato il film!";
	}
}
	
else
	echo "Non sei ancora loggato! fai il <a href=login.php>Login</a> o <a href=register.php>Registrati</a>";
