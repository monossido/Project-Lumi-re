<?PHP
require("library.php");
require("configure.php");
session_start();
page_start("Movie Manager - Registrati");


if ($_POST['submit']) {
	/* recupera i dati immessi */
	$login=$_POST['username'];
	$password=$_POST['password'];
	$confirm=$_POST['passwordC'];
	$mail=$_POST['mail'];

	if  ($password != $confirm)
	/* verifica se le password e la conferma sono uguali */
	echo "Errore! Password e Conferma sono diverse. ";
	elseif (get_pwd($login))
	echo "Errore! Login gia` in uso. ";
	else {
	/* inserisce il login e password nella BD */


	/* connessione al server */
	$conn=mysql_connect(dbhost, dbuser, dbpwd)
	or die("Connessione al server MySQL fallita!");

	mysql_select_db(dbname);
  
	$query="SELECT * FROM Stato";
	$result=mysql_query($query, $conn)
	  or die("Query fallita!" . mysql_error());
	$stato=mysql_fetch_array($result);
	$query= "INSERT INTO Utenti (username,password,voto) VALUES ('$login', '".SHA1($password)."', '".$voto['Round']."')";

	mysql_query($query,$conn)
		or die("Query fallita" . mysql_error($conn));

 }
}
else echo "Registrazione Effettuata";

?>

