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
require("library.php");
require("configure.php");
session_start();
page_start("Movie Manager - Registrati");


if ($_POST['submit']) {
	/* recupera i dati immessi */
	$login=$_POST['username'];
	$password=$_POST['password'];
	$confirm=$_POST['passwordC'];
	$mail=$_POST['mail'];

	if  ($password != $confirm)
	/* verifica se le password e la conferma sono uguali */
	echo "Errore! Password e Conferma sono diverse. ";
	elseif (get_pwd($login))
	echo "Errore! Login gia` in uso. ";
	else {
	/* inserisce il login e password nella BD */


	/* connessione al server */
	$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");

	mysql_select_db(dbname);
  
	$query="SELECT * FROM Stato";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$stato=mysql_fetch_array($result);
	$query= "INSERT INTO Utenti (username,password,voto) VALUES ('$login', '".SHA1($password)."', '".$voto['Round']."')";

	mysql_query($query,$conn)
		or die("Query fallita" . mysql_error($conn));

 }
}
else echo "Registrazione Effettuata";

?>

