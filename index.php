<?php
require("configure.php");
require("library.php");
session_start();

echo '<HTML>
<HEAD><TITLE>Project Lumi&eacute;re 0.1</TITLE></HEAD>
<BODY>
<HR />
<center><img src="images/Logo1.0A2.jpg" border="0"></center>';


if ($_POST['login']) {
  /* recupera i dati immessi */
  $login=$_POST['username'];
  $password=$_POST['password'];
if (($login) && (SHA1($password) == get_pwd($login)))
{
	$_SESSION['logged']=$login;
}
}

  $conn=mysql_connect(dbhost, dbuser, dbpwd)
    or die("Connessione al server MySQL fallita!");
  mysql_select_db(dbname);

if(isset($_SESSION['logged']))
{

	$query="SELECT Voto FROM Utenti WHERE Username='".$_SESSION['logged']."'";

	# Solo per vedere se l'utente è anche admin

	$query2="SELECT amministratore FROM Utenti WHERE Username='".$_SESSION['logged']."'";
	$result2=mysql_query($query2, $conn)
	  or die("Query fallita!" . mysql_error());
	$admin=mysql_fetch_array($result2);
	# echo $admin['amministratore'];
	if ($admin['amministratore']==1)
		echo "<p align='right'><font color=red>Vai alla pagina di <a href=admin.php>ADMIN</a></font></p>";
	# Fine
	# Se è bau
		if ($login=='bau')	echo "<p align=right>Vai alla pagina di <a href=inserisci-film.php>inserimento film</a></p>";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$voto=mysql_fetch_array($result);
	echo "<p align='right'>Ciao, <b>".$_SESSION['logged'];
	if($voto['Voto']=="0")	
	{
		echo "</b><br /><em>Devi ancora votare, cosa aspetti?</em>";
	}
	else
	{
		echo "</b><br /><em>Questa settimana hai gi&agrave votato</em>";
	}
	echo "</p><HR />";
	echo "<p align='right'><a href='logout.php'>Logout</a></p>";

}else
{
	echo "<p align='right'>Effettua il <a href='login.php'>login</a> o <a href='register.php'>registrati</a><HR />";
}

echo "</table>";
echo "<p align='center'>> Visualizza i <a href='risultati.php'>risultati</a> <b>parziali <</b></p>";

$visti=$_GET['visti'];
$risoluzione=$_GET['risoluzione'];
echo "<p align='left'>Visualizza i film:";
if($risoluzione==1)
{
	echo "<select name='visto' onchange=\"window.location.href='index.php?visti='+this.value+'&risoluzione=1'\">";
}else if ($risoluzione==2)
{
	echo "<select name='visto' onchange=\"window.location.href='index.php?visti='+this.value+'&risoluzione=2'\">";
}else
{
	echo "<select name='visto' onchange=\"window.location.href='index.php?visti='+this.value+'&risoluzione=0'\">";
}
echo "<option value = '0' >Non Visti</option>
<option value = '1'";
if($visti) echo "selected=true";
echo ">Tutti</option>
</select>";


# Prova

if($visti)
{
	echo "<select name='risoluzione' onchange=\"window.location.href='index.php?visti=1&risoluzione='+this.value\">";
}else
{
	echo "<select name='risoluzione' onchange=\"window.location.href='index.php?visti=0&risoluzione='+this.value\">";
}
echo "<option value = '0'>Tutte</option>
<option value = '1'";
if($risoluzione==1) echo " selected=true ";
echo ">1080p</option>
<option value='2'";
if($risoluzione==2) echo " selected=true ";
echo ">720p</option>
</select></p>"; # echo $risoluzione;
if($risoluzione=='1' && $visti=='1')	$query="SELECT * FROM Film WHERE Film.risoluzione=1080";
if($risoluzione=='2' && $visti=='1')	$query="SELECT * FROM Film WHERE Film.risoluzione=720";
if($risoluzione=='1' && $visti=='0')	$query="SELECT * FROM Film WHERE Film.risoluzione=1080 AND Film.visto=0";
if($risoluzione=='2' && $visti=='0')	$query="SELECT * FROM Film WHERE Film.risoluzione=720 AND Film.visto=0";
if($risoluzione=='1' && $visti==null)	$query="SELECT * FROM Film WHERE Film.risoluzione=1080 AND Film.visto=0";
if($risoluzione=='2' && $visti==null)	$query="SELECT * FROM Film WHERE Film.risoluzione=720 AND Film.visto=0";
if($visti=='1' && $risoluzione==0)	$query="SELECT * FROM Film";
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
	if(isset($_SESSION['logged']) && $voto['Voto']=="0")
		echo "<td><a href='vota.php?id=".$row['id_film']."'>Vota</a></td>";
	else echo "<td width='8%'><em>Non puoi votare</em></td>";
	echo "</tr>";
}

echo "</table>";
# echo "<p align='center'>Visualizza i <a href='risultati.php'>risultati</a> <b>parziali</b></p>";


echo '</HEAD></HTML>';

