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


if ($_SESSION['logged'])
{
	$id=$_GET['id'];
	$query="SELECT * FROM Proposte WHERE id_proposte=$id";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	while(ris=mysql_fetch_array($result))
	{
		$query10="INSERT INTO Proposte (titolo, visto, risoluzione, durata, lingua) VALUES('$titolo','$visto','bluray','$durata', 'tutte')";
		mysql_query($query10, $conn)
	           or die("Query fallita! " . mysql_error());	
	


