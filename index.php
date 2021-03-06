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

$visti=$_GET['visti'];
$risoluzione=$_GET['risoluzione'];
if($risoluzione=='1' && $visti=='2')	$queryF="SELECT * FROM Film WHERE Film.risoluzione=1080";
if($risoluzione=='2' && $visti=='2')	$queryF="SELECT * FROM Film WHERE Film.risoluzione=720";
if($risoluzione=='1' && $visti=='1')	$queryF="SELECT * FROM Film WHERE Film.risoluzione=1080 AND Film.visto=1";
if($risoluzione=='2' && $visti=='1')	$queryF="SELECT * FROM Film WHERE Film.risoluzione=720 AND Film.visto=1";
if($risoluzione=='1' && $visti=='0')	$queryF="SELECT * FROM Film WHERE Film.risoluzione=1080 AND Film.visto=0";
if($risoluzione=='2' && $visti=='0')	$queryF="SELECT * FROM Film WHERE Film.risoluzione=720 AND Film.visto=0";
if($risoluzione=='1' && $visti==null)	$queryF="SELECT * FROM Film WHERE Film.risoluzione=1080 AND Film.visto=0";
if($risoluzione=='2' && $visti==null)	$queryF="SELECT * FROM Film WHERE Film.risoluzione=720 AND Film.visto=0";
if($visti=='2' && $risoluzione==0)	$queryF="SELECT * FROM Film";
if($visti=='1' && $risoluzione==0)	$queryF="SELECT * FROM Film WHERE Film.visto=1";
if($visti=='0' && $risoluzione==0)	$queryF="SELECT * FROM Film WHERE Film.visto=0";
if($risoluzione=='0' && $visti==0 || $risoluzione==null && $visti==null)	$queryF="SELECT * FROM Film WHERE Film.visto=0";

$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die('Connessione al server MySQL fallita!');
mysql_select_db(dbname);

$query2='SELECT * FROM Stato';
$result2=mysql_query($query2, $conn)
  or die('Query fallita!' . mysql_error());
$stato=mysql_fetch_array($result2);

$query="SELECT Voto,amministratore FROM Utenti WHERE Username='".$_SESSION['logged']."'";
$result=mysql_query($query, $conn)
  or die('Query fallita!' . mysql_error());
$utente=mysql_fetch_array($result);


echo '<HTML>
<HEAD><TITLE>Project Lumi&eacute;re</TITLE><script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.watermark.js"></script>
<script type="text/javascript">
 
 
      $(document).ready(function() {

$("#faq_search_input").watermark("Begin Typing to Search");

$("#faq_search_input").keyup(function()
{
var faq_search_input = $(this).val();';
if(isset($_SESSION['logged']))
	echo 'var dataString = \'keyword=\'+ faq_search_input + \'&votazioni=\' + 
'.$stato['VotazioniAperte'].' + \'&round=\' + '.$stato['Round'].' + \'&voto=\' + 
'.$utente['Voto'].' + \'&query=\' + \''.$queryF.'\';';
else
	echo 'var dataString = \'keyword=\'+ faq_search_input + \'&votazioni=\' +
'.$stato['VotazioniAperte'].' + \'&round=\' + '.$stato['Round'].' + \'&voto=\' +
0 + \'&query=\' + \''.$queryF.'\';';
echo 'if(faq_search_input.length>=0)

{
$.ajax({
type: "GET",
url: "ajax-search.php",
data: dataString,
beforeSend:  function() {

$(\'input#faq_search_input\').addClass(\'loading\');

},
success: function(server_response)
{

$(\'#searchresultdata\').html(server_response).show();
$(\'span#faq_category_title\').html(faq_search_input);

if ($(\'input#faq_search_input\').hasClass("loading")) {
 $("input#faq_search_input").removeClass("loading");
        } 

}
});
}return false;
});
});
	  
</script>
</HEAD>
<BODY>
<link rel=\'stylesheet\' type=\'text/css\' href=\'style.css\'>
<div id=\'container\'>
	 <div id=\'header\'>
	 	 <center><img src=\'images/logo.jpg\' border=\'0\'></center>
	 </div><HR />';

if(isset($_SESSION['logged']))
{

	# echo $admin['amministratore'];
	if ($utente['amministratore'])
	{
		echo "<div id='navigation'><div align='left'><font color=red>Vai alla pagina di <a href=admin.php>ADMIN</a></font><br />";
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
	echo "<p align='right'>Effettua il <a href='login.php'>login</a> o <a href='register.php'>registrati</a></div>";
}
if($stato['VotazioniAperte'])
{
	//A che round siamo?
	if($stato['Round']==1)
	{
		echo "<div id='content'>Il primo round &egrave gi&agrave finito, vai ai risultati parziali per votare per il secondo round";

	}else if($stato['Round']==2)
	{
		echo "Il secondo round &egrave gi&agrave finito, il film è stato scelto vai ai risultati per scoprire quale film si guarder&agrave";
	}
	echo "<p align='center'>>Visualizza i <a href='risultati.php'>risultati</a> <b>parziali <</b></p>";
}else if($stato['Round']!=0)
{
	echo "<p align='center'>Votazioni terminate! >Visualizza i <a href='risultati.php'>risultati</a>< <b></b></p>";
}else
{
	echo "<p align='center'>Nessuna votazione in corso!</p>";
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
</select></p>";

# End

$result=mysql_query($queryF, $conn)
  or die("Query fallita! " . mysql_error());

echo 'Cerca: <input  name="query" type="text" id="faq_search_input" />
	<span id="faq_category_title"></span>
        <div id="searchresultdata" class="faq-articles">';//Div che viene svuotato e ri-riempito se si cerca attraverso il form

echo "<TABLE width=\"100%\" border cellpadding=\"5\"><thead><th>Titolo</th><th>Risoluzione</th><th>Lingua</th><th>Durata</th><th>Visto</th><th>Vota</th></thead>";
while($row=mysql_fetch_array($result))
{
	echo "<form action=film.php method=get><tbody><tr><td><b><a href=film.php?id=".$row['id_film'].">".$row['titolo']."</a></b></td><td>".$row['risoluzione']."</td><td>".$row['lingua']."</td><td>".$row['durata']."</td>";
	#echo "$row['visto']";
	if ($row['visto']=="0")
		#<td>".$row['visto']."</td>";
		echo "<td>No</td>";
	else
		echo "<td>Si</td>";
	//Per votare dalla home bisogna, essere loggati, avere le votazioni aperte, non aver già votato, essere ancora nel primo round
	if(isset($_SESSION['logged']) && $stato['VotazioniAperte'] && !$utente['Voto'] && !$stato['Round'])
		echo "<td><a href='vota.php?id=".$row['id_film']."&round=0'>Vota</a></td>";
	else echo "<td width='8%'><em>Non puoi votare</em></td>";
	echo "</tr>";
}

echo "</tbody></table>";


echo '</div></BODY></HEAD></HTML>';

