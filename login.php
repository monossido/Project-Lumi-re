<?PHP
require("library.php");
session_start();
page_start("Project Lumi&eacute;re - loggati");

if(isset($_SESSION['logged']))
	echo "Devi prima fare il logout";
else
{
echo "<center>";
echo "<table border=0>";
	echo 	"<form method=POST action=index.php>
	<tr><td>Username:</td><td><input type=text name=username></td><tr />
	<tr><td>Password:</td><td><input type=password name=password></td><tr /></table><br>
	<input type=submit name=login value=login></form>";
}

?>


