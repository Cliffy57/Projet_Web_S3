<?php
$db_config = array();
$db_config['SGBD'] = 'mysql';
$db_config['HOST'] = 'devbdd.iutmetz.univ-lorraine.fr';
$db_config['DB_NAME'] = 'dipaolo6u_phpblog';
$db_config['USER'] = 'dipaolo6u_appli';
$db_config['PASSWORD'] = 'MmVay9SPfJ';
// ==========================
// connection avec PDO
try
{
$objPdo = new PDO($db_config['SGBD'] .':host='. $db_config['HOST']
.';dbname='. $db_config['DB_NAME'], $db_config['USER'],
$db_config['PASSWORD']) ;
unset($db_config);
echo "connexion ok<br/>\n";
}
catch( Exception $exception )
{
die($exception->getMessage()) ;
}
// ==========================
?>