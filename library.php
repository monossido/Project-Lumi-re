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


/*inizio pagina*/
function page_start($title) {
  echo "
<HTML>
<HEAD><TITLE>$title</TITLE></HEAD>
<BODY>
<HR />
<H2>$title</H2>
<p align='right'><a href='index.php'><b>Home</b></a></p><HR />
";
};

function page_end() {
  echo "
</BODY>
</HTML>";
};


function get_pwd($login) {
  $query= "SELECT * FROM Utenti WHERE Username='".$login."'";

  /* connessione al server */
  $conn=mysql_connect(dbhost, dbuser, dbpwd)
    or die("Connessione al server MySQL fallita!");

  mysql_select_db(dbname);

  $result=mysql_query($query,$conn)
    or die("Query fallita" . mysql_error($conn));

  $output=mysql_fetch_assoc($result);

  if ($output)
    return $output['Password'];
  else 
    return FALSE;
};

function get_film() {
	/* connessione al server */
	$conn=mysql_connect(dbhost, dbuser, dbpwd)
		or die("Connessione al server MySQL fallita!");
	mysql_select_db(dbname);

	$query="SELECT * FROM Film WHERE voti>=1 ORDER BY voti DESC";
	$result=mysql_query($query, $conn)
		or die("Query fallita!" . mysql_error());

	$i=2;
	$voti=0;
	while($row=mysql_fetch_array($result))
		$film[]=$row['voti'];
	for(;$film[$i]==$film[$i-1];$i++)
	for($z=0;$z<=$i;$z++)
	{
		$voti=$voti+$film[$z]['voti'];
	}
	if($film[0]['voti']>$voti/2)
		return 1;
	else		
		return $i;

};

function set_film($film,$i) {
	if($film[0]>$film[1])
	{
		return 0;
	}else if($film[1]>$film[2])
	{
		return 1;
	}
	else
	{
		$n=rand(0,$i);
		return $n;
	}
}


?>
