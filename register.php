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
session_start();
page_start("Project Lumi&eacute;re - registrati");

$conn=mysql_connect(dbhost, dbuser, dbpwd)
or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

$query="SELECT * FROM Stato";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$stato=mysql_fetch_array($result);

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

	echo 	"<form method=POST action=registerPost.php>
		<tr><td>Username:</td><td><input type=text name=username></td><tr />
		<tr><td>Password:</td><td><input type=password name=password></td><tr />
		<tr><td>Conferma Password:</td><td><input type=password name=passwordC></td><tr />
		<tr><td>E-mail:</td><td><input type=text name=mail></td><tr />
		<tr><td>Conferma E-mail:</td><td><input type=text name=mail2 autocomplete=off></td><tr /></table><br>
		<input type=submit name=submit value=registrati></form>";
}
echo "</p>";

?>
