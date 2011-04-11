<?php
include_once ('configure.php');//Including our DB Connection file
session_start();
$conn=@mysqli_connect(dbhost, dbuser, dbpwd, dbname)
	or die('Connessione al server MySQL fallita!');

$query2='SELECT * FROM Stato';
$result2=mysqli_query($conn,$query2)
  or die('Query fallita!' . mysql_error());
$stato= mysqli_fetch_array($result2,MYSQLI_ASSOC);

$query="SELECT Voto,amministratore FROM Utenti WHERE Username='".$_SESSION['logged']."'";
$result=mysqli_query($conn,$query)
  or die("Query fallita!" . mysql_error());
$utente= mysqli_fetch_array($result,MYSQLI_ASSOC);

if(isset($_GET['keyword'])){//IF the url contains the parameter "keyword"
 $keyword =     trim($_GET['keyword']) ;//Remove any extra  space
$keyword = mysqli_real_escape_string($conn, $keyword);//Some validation

$query=$_GET['query']." AND titolo like '%$keyword%'";


$result = mysqli_query($conn,$query);//Run the Query
if($result){//If query successfull
 if(mysqli_affected_rows($conn)!=0){//and if atleast one record is found - Preparo il contenuto del DIV
  echo "<TABLE width=\"100%\" border cellpadding=\"5\"><thead><th>Titolo</th><th>Risoluzione</th><th>Lingua</th><th>Durata</th><th>Visto</th><th>Vota</th></thead>";
  while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){ //Display the record
 	echo "<form action=film.php method=get><tbody><tr><td><b><a href=film.php?id=".$row['id_film'].">".$row['titolo']."</a></b></td><td>".$row['risoluzione']."</td><td>".$row['lingua']."</td><td>".$row['durata']."</td>";
	#echo "$row['visto']";
	if ($row['visto']=="0")
		#<td>".$row['visto']."</td>";
		echo "<td>No</td>";
	else
		echo "<td>Si</td>";
	//Per votare dalla home bisogna, essere loggati, avere le votazioni aperte, non aver già votato, essere ancora nel primo round
	if(isset($_SESSION['logged']) && $_GET['votazioni'] && !$_GET['Voto'] && !$_GET['Round'])
		echo "<td><a href='vota.php?id=".$row['id_film']."&round=0'>Vota</a></td>";
	else echo "<td width='8%'><em>Non puoi votare</em></td>";
	echo "</tr>";
  }
 echo "</tbody></table>";
 }else {
 echo '<br /><b>No Results for :"'.$_GET['keyword'].'"</b>';//No Match found in the Database
 }

}
}else {
 echo 'Parameter Missing in the URL';//If URL is invalid
}

?>
