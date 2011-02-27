<?PHP
require("library.php");
session_start();
page_start("Project Lumi&eacute;re - registrati");

$conn=mysql_connect(dbhost, dbuser, dbpwd)
or die("Connessione al server MySQL fallita!");
mysql_select_db(dbname);

$query="SELECT * FROM Stato";
$result=mysql_query($query, $conn)
  or die("Query fallita!" . mysql_error());
$stato=mysql_fetch_array($result);

echo "<p align='center'>";
if(!$_SESSION['logged'])
{
	echo "Sei già loggato, effettua il <a href='logout.php'>logout</a> prima.";
}
else if(!$stato['RegistrazioniAperte'])
{
	echo "Le registrazioni sono chiuse";
}else
{
	echo "<table border=0>";

	echo 	"<form method=POST action=registerPost.php>
		<tr><td>Username:</td><td><input type=text name=username></td><tr />
		<tr><td>Password:</td><td><input type=password name=password></td><tr />
		<tr><td>Conferma Password:</td><td><input type=password name=passwordC></td><tr />
		<tr><td>E-mail:</td><td><input type=text name=mail></td><tr /></table><br>
		<input type=submit name=submit value=registrati></form>";
}
echo "</p>";

?>
