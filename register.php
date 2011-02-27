<?PHP
require("library.php");
session_start();
page_start("Movie Manager - registrati");

echo "<center>";
echo "<table border=0>";

echo 	"<form method=POST action=registerPost.php>
	<tr><td>Username:</td><td><input type=text name=username></td><tr />
	<tr><td>Password:</td><td><input type=password name=password></td><tr />
	<tr><td>Conferma Password:</td><td><input type=password name=passwordC></td><tr />
	<tr><td>E-mail:</td><td><input type=text name=mail></td><tr /></table><br>
	<input type=submit name=submit value=registrati></form>";
echo "</center>";
?>
