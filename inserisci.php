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
require("configure.php");
$film=$_GET['titoli'];
$risoluzione=$_GET['ris'];
$lingua=$_GET['lin'];
$durata=$_GET['dur'];
	$conn=mysql_connect(dbhost, dbuser, dbpwd)
	    or die("Connessione al server MySQL fallita!");
	  mysql_select_db(dbname);

	$query="INSERT INTO Film (titolo, risoluzione, lingua, durata) VALUES ('$film','$risoluzione', '$lingua', '$durata') ";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());


?>
