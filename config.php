<?php
$db_config = array();
$db_config['SGBD'] = 'mysql';
$db_config['HOST'] = 'devbdd.iutmetz.univ-lorraine.fr';
$db_config['DB_NAME'] = 'dipaolo6u_phpblog';
$db_config['USER'] = 'dipaolo6u_appli';
$db_config['PASSWORD'] = 'MmVay9SPfJ';
// ==========================
// connection avec PDO
define('DB_SERVER', 'mysql');
define('DB_HOST', 'devbdd.iutmetz.univ-lorraine.fr');
define('DB_NAME', 'dipaolo6u_phpblog');
define('DB_USER', 'dipaolo6u_appli');
define('DB_PASSWORD', 'MmVay9SPfJ');
define('DB_PORT', 0);
try{
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
// echo "link ok<br/>\n";
}
catch( Exception $exception )
{
die($exception->getMessage()) ;
}
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
try
{
$objPdo = new PDO($db_config['SGBD'] .':host='. $db_config['HOST']
.';dbname='. $db_config['DB_NAME'], $db_config['USER'],
$db_config['PASSWORD']) ;
unset($db_config);
// echo "connexion ok<br/>\n";
}
catch( Exception $exception )
{
die($exception->getMessage()) ;
}
$objPdo->query("SET NAMES UTF8");
// ==========================
?>