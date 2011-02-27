<?php
require("configure.php");
require("library.php");
session_start();
page_start("Movie Manager - Logout");

if(isset($_SESSION['logged']))
{
	echo "Logout effettuato, torna in <a href='index.php'>home</a>.";
	session_destroy();
}else
{
	echo "Prima effettuare il <a href='login.php'>login</a>.";
}



?>
