<?php
try 
{ 
    $objPdo = new PDO 
    (’mysql:host=devbdd.iutmetz.univ-lorraine.fr;port=3306;dbname=toto3u_db’ , dipaolo6u_appli, MmVay9SPfJ );  
    echo "connexion ok<br/>\n"; 
}  
    catch( Exception $exception ) 
{  
    die($exception->getMessage()); 
}
?>