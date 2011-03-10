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
page_start("Project Lumi&eacute;re - loggati");

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die('Connessione al server MySQL fallita!');
mysql_select_db(dbname);

if(isset($_SESSION['logged']))
	echo "Devi prima fare il logout";
else
{
	if (!$_POST['login'])
	{	
		echo "<center>";
		echo "<table border=0>";
		echo 	"<form method=POST action=login.php>
		<tr><td>Username:</td><td><input type=text name=username></td><tr />
		<tr><td>Password:</td><td><input type=password name=password></td><tr /></table><br>
		<input type=submit name=login value=login></form>";
	}
	else
	{
		$login=$_POST['username']; 
		$password=$_POST['password'];
		$query="SELECT * FROM Utenti WHERE Username='$login'";
		$result=mysql_query($query, $conn)
		  or die('Query fallita!' . mysql_error());
		$stato=mysql_fetch_array($result);				
		$password2=$stato['Password'];

		if (!$password2)	// Se vuoto vuol dire che la query non è andata -> username errato
			echo "Username errato! <a href=login.php>Riprova</a>.";
		else
		{
			echo $stato['username'];
			if (SHA1($password) != get_pwd($login))
				echo "<br>Errore! Password Errata! <a href=login.php>Riprova</a>";
			else
			{
				echo "Login Effettuato! Torna alla <a href=index.php>Home</a>";
				$_SESSION['logged']=$login;
			}
		}
	}
}

?>


