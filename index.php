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
require("library.php");
session_start();

echo '<HTML>
<HEAD><TITLE>Project Lumi&eacute;re 0.1</TITLE></HEAD>
<BODY>
<HR />
<center><img src="images/logo0.2e.jpg" border="0"></center>';

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

$query2="SELECT * FROM Stato";
$result2=mysql_query($query2, $conn)
  or die("Query fallita!" . mysql_error());
$stato=mysql_fetch_array($result2);

if ($_POST['login']) {
	  /* recupera i dati immessi */
	  $login=$_POST['username'];
	  $password=$_POST['password'];

	if (($login) && (SHA1($password) == get_pwd($login)))
	{
		$_SESSION['logged']=$login;
	}
}

if(isset($_SESSION['logged']))
{
	$query="SELECT Voto,amministratore FROM Utenti WHERE Username='".$_SESSION['logged']."'";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$utente=mysql_fetch_array($result);

	# echo $admin['amministratore'];
	if ($utente['amministratore'])
	{
		echo "<div align='left'><font color=red>Vai alla pagina di <a href=admin.php>ADMIN</a></font><br />";
		echo "Vai alla pagina di <a href=inserisci-film.php>inserimento film</a></div>";
	}

	echo "<div align='right'>Ciao, <b>".$_SESSION['logged'];
	if(!$utente['Voto'])	
	{
		echo "</b><br /><em>Devi ancora votare, cosa aspetti?</em>";
	}
	else
	{
		echo "</b><br /><em>Questa settimana hai gi&agrave votato</em>";
	}
	echo "</div><HR />";
	echo "<p align='right'><a href='logout.php'>Logout</a></p>";

}else
{
	echo "<p align='right'>Effettua il <a href='login.php'>login</a> o <a href='register.php'>registrati</a><HR />";
}
echo "</table>";
if($stato['VotazioniAperte'])
{
	//A che round siamo?
	if($stato['Round']==1)
	{
		echo "Il primo round &egrave gi&agrave finito, vai ai risultati parziali per votare per il secondo round";

	}else if($stato['Round']==2)
	{
		echo "Il secondo round &egrave gi&agrave finito, il film è stato scelto vai ai risultati per scoprire quale film si guarder&agrave";
	}
	echo "<p align='center'>>Visualizza i <a href='risultati.php'>risultati</a> <b>parziali <</b></p>";
}else
{
	echo "<p align='center'>Votazioni terminate! Visualizza i <a href='risultati.php'>risultati</a> <b></b></p>";
}


$visti=$_GET['visti'];
$risoluzione=$_GET['risoluzione'];
echo "<p align='left'>Visualizza i film:";
echo "<select name='visto' onchange=\"window.location.href='index.php?visti='+this.value+'&risoluzione=$risoluzione'\">";

echo "<option value='0' >Non Visti</option>
<option value='1'";
if($visti==1) echo "selected=true";
echo ">Visti</option>
<option value='2'";
if($visti==2) echo "selected=true";
echo ">Tutti</option>
</select>";


echo "<select name='risoluzione' onchange=\"window.location.href='index.php?visti=$visti&risoluzione='+this.value\">";
echo "<option value = '0'>Tutte</option>
<option value = '1'";
if($risoluzione==1) echo " selected=true ";
echo ">1080p</option>
<option value='2'";
if($risoluzione==2) echo " selected=true ";
echo ">720p</option>
</select></p>"; # echo $risoluzione;
if($risoluzione=='1' && $visti=='2')	$query="SELECT * FROM Film WHERE Film.risoluzione=1080";
if($risoluzione=='2' && $visti=='2')	$query="SELECT * FROM Film WHERE Film.risoluzione=720";
if($risoluzione=='1' && $visti=='1')	$query="SELECT * FROM Film WHERE Film.risoluzione=1080 AND Film.visto=1";
if($risoluzione=='2' && $visti=='1')	$query="SELECT * FROM Film WHERE Film.risoluzione=720 AND Film.visto=1";
if($risoluzione=='1' && $visti=='0')	$query="SELECT * FROM Film WHERE Film.risoluzione=1080 AND Film.visto=0";
if($risoluzione=='2' && $visti=='0')	$query="SELECT * FROM Film WHERE Film.risoluzione=720 AND Film.visto=0";
if($risoluzione=='1' && $visti==null)	$query="SELECT * FROM Film WHERE Film.risoluzione=1080 AND Film.visto=0";
if($risoluzione=='2' && $visti==null)	$query="SELECT * FROM Film WHERE Film.risoluzione=720 AND Film.visto=0";
if($visti=='2' && $risoluzione==0)	$query="SELECT * FROM Film";
if($visti=='1' && $risoluzione==0)	$query="SELECT * FROM Film WHERE Film.visto=1";
if($visti=='0' && $risoluzione==0)	$query="SELECT * FROM Film WHERE Film.visto=0";
if($risoluzione=='0' && $visti==0 || $risoluzione==null && $visti==null)	$query="SELECT * FROM Film WHERE Film.visto=0";

# End

$result=mysql_query($query, $conn)
  or die("Query fallita! " . mysql_error());

echo "<TABLE width=\"100%\" border cellpadding=\"5\"><th>Titolo</th><th>Risoluzione</th><th>Lingua</th><th>Durata</th><th>Visto</th><th>Vota</th>";


while($row=mysql_fetch_array($result))
{
	echo "<form action=film.php method=get><tr><td><a href=film.php?titolo=".$row['titolo'].">".$row['titolo']."</a></td><td>".$row['risoluzione']."</td><td>".$row['lingua']."</td><td>".$row['durata']."</td>";
	#echo "$row['visto']";
	if ($row['visto']=="0")
		#<td>".$row['visto']."</td>";
		echo "<td>No</td>";
	else
		echo "<td>Si</td>";
	//Per votare dalla home bisogna, essere loggati, non aver già votato, essere ancora nel primo round
	if(isset($_SESSION['logged']) && !$utente['Voto'] && !$stato['Round'])
		echo "<td><a href='vota.php?id=".$row['id_film']."&round=0'>Vota</a></td>";
	else echo "<td width='8%'><em>Non puoi votare</em></td>";
	echo "</tr>";
}

echo "</table>";
# echo "<p align='center'>Visualizza i <a href='risultati.php'>risultati</a> <b>parziali</b></p>";


echo '</BODY></HEAD></HTML>';

