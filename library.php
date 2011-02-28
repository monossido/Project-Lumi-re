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
<p align='right'><a href='index.php'><b>Home</b></a></p><HR />
";
};

function page_end() {
  echo "
</BODY>
</HTML>";
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

function get_film() {
	/* connessione al server */
	$conn=mysql_connect(dbhost, dbuser, dbpwd)
		or die("Connessione al server MySQL fallita!");
	mysql_select_db(dbname);

	$query="SELECT * FROM Film WHERE voti>=1 ORDER BY voti DESC";
	$result=mysql_query($query, $conn)
		or die("Query fallita!" . mysql_error());
	$film=mysql_fetch_array($result);
	$i=3;
	$voti=0;
	for(;$film[$i]['voti']==$film[$i-1]['voti'];$i++){}
	for($z=0;$z<=$i;$z++)
	{
		$voti=$voti+$film[$z]['voti'];
	}
	if($film[0]['voti']>$voti/2)
		return 1;
	else
		return $i+1;

};


?>
