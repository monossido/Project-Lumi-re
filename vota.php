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

page_start("Project Lumi&eacute;re - Vota");

if(isset($_SESSION['logged']))
{
	$round=$_GET['round'];
	$id=$_GET['id'];
	$conn=mysql_connect(dbhost, dbuser, dbpwd)
	    or die("Connessione al server MySQL fallita!");
	  mysql_select_db(dbname);

	$query="SELECT * FROM Film WHERE id_film='$id'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());

	$row=mysql_fetch_array($result);
	echo $row['titolo'];

	echo "<br />Confermi il voto?<br />
	<form method=POST action=conferma.php?id=$id&round=$round>
	<input type=submit name=conferma value=conferma></form>";
}else
{
	echo "<p align=center>Devi prima <a href='login.php'>loggarti</a></p>";
}

?>
