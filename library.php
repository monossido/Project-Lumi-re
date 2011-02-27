<?PHP

require("configure.php");


/*inizio pagina*/
function page_start($title) {
  echo "
<HTML>
<HEAD><TITLE>$title</TITLE></HEAD>
<BODY>
<HR />
<H2>$title</H2>
<p align='right'><a href='index.php'><b>Home</b></a><HR />
";
};


function get_pwd($login) {
  $query= "SELECT * FROM Utenti WHERE Username='".$login."'";

  /* connessione al server */
  $conn=mysql_connect(dbhost, dbuser, dbpwd)
    or die("Connessione al server MySQL fallita!");

  mysql_select_db(dbname);

  $result=mysql_query($query,$conn)
    or die("Query fallita" . mysql_error($conn));

  $output=mysql_fetch_assoc($result);

  if ($output)
    return $output['Password'];
  else 
    return FALSE;
};

?>
