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
require("library.php");
session_start();

page_start("Project Lumi&eacute;re - Registrati");

$conn=mysql_connect(dbhost, dbuser, dbpwd)
or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

$query="SELECT * FROM Stato";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$stato=mysql_fetch_array($result);

if (!$_POST['submit'])
{
	echo "<p align='center'>";
	if($_SESSION['logged'])
	{
		echo "Sei già loggato, effettua il <a href='logout.php'>logout</a> prima.";
	}
	else if(!$stato['RegistrazioniAperte'])
	{
		echo "Le registrazioni sono chiuse";
	}else
	{
		echo "<table border=0>";

		echo 	"<form method=POST action=register.php>
			<tr><td>Username:</td><td><input type=text name=username></td><tr />
			<tr><td>Password:</td><td><input type=password name=password></td><tr />
			<tr><td>Conferma Password:</td><td><input type=password name=passwordC></td><tr />
			<tr><td>E-mail:</td><td><input type=text name=mail></td><tr />
			<tr><td>Conferma E-mail:</td><td><input type=text name=mail2 autocomplete=off></td><tr /></table><br>
			<input type=submit name=submit value=registrati></form>";
	}
	echo "</p>";
}else{
	/* recupera i dati immessi */
	$login=$_POST['username'];
	$password=$_POST['password'];
	$confirm=$_POST['passwordC'];
	$mail=$_POST['mail'];
	$mail2=$_POST['mail2'];
	
	$errore=0;
	// Verifica se le due mail sono uguali
	if ($mail!=$mail2)
	{
		echo "Errore! I due indirizzi e-mail non coincidono!";
		$errore=1;
	}
	
	if (!$mail&&!$mail2)
	{
		echo "Errore! Devi inserire l'indirizzo e-mail!";
		$errore=1;
	}

	if  ($password != $confirm)
	{
		/* verifica se le password e la conferma sono uguali */
		echo "Errore! Le due Password non coincidono!";
		$errore=1;
	}
	
	elseif (get_pwd($login))
	{
		echo "Errore! Login gia` in uso. ";
		$errore=1;
	}
	
	if (!$errore)	// Se NON ci sono stati errori:
	{
		/* inserisce il login e password nella BD */
		/* connessione al server */	
	
		$conn=mysql_connect(dbhost, dbuser, dbpwd)
		or die("Connessione al server MySQL fallita!");

		mysql_select_db(dbname);
  		$code = rand(1, 9999);	// Genera un numero casuale
		$query="SELECT * FROM Stato";
		$result=mysql_query($query, $conn)
		  or die("Query fallita!" . mysql_error());
		$stato=mysql_fetch_array($result);
		$query= "INSERT INTO Utenti (username,password,voto,mail,verificato,temp) VALUES ('$login', '".SHA1($password)."', '".$voto['Round']."', '$mail', '0', '$code')";
		mysql_query($query,$conn)
			or die("Query fallita" . mysql_error($conn));
		mail ("$mail", "Conferma Indirizzo Project Lumière", "Clicca questo link per validare il tuo account! http://monossido.ath.cx/bau/cinema/verifica.php?code=$code&nome=$login", "From: Project_Lumière");
		echo "Registrazione Effettuata! Devi validare il tuo account! Per farlo, clicca il link che trovi nella mail che &egrave; stata mandata all'indirizzo che ci hai fornito.";
 	}
}

?>
