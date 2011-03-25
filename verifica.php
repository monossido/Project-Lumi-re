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

page_start("Project Lumi&eacute;re - Verifica");

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);
$username=$_GET['nome'];
$query="SELECT * FROM Utenti WHERE Username='$username'";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$dati=mysql_fetch_array($result);

$verificato=$dati['verificato'];
$code = $dati['temp'];
$code2=$_GET['code'];

/* echo "Username="; echo $username;
echo "<br>Codice2="; echo $code2; 
echo "<br>Codice1="; echo $code;
echo "<br>Codice1="; echo $dati['temp']; echo"<br>";
echo "verificato="; echo $verificato; */

if (!$verificato&&($code==$code2))
{
	$query2="UPDATE Utenti SET verificato=1 WHERE Username='$username'";
	mysql_query($query2, $conn)
	  or die("Query fallita! " . mysql_error());	
	echo "<center>Account verificato! Torna alla <a href=http://monossido.ath.cx/bau/cinema/index.php>Home</a></center>";
}
else
	echo "Il tuo account risulta gi&agrave; verificato!";
# echo "vai"; echo "<br>";

?>
