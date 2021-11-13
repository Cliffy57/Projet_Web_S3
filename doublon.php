<?php
require_once "config.php";
include_once('header.php');
session_start();
$insert_stmt = $objPdo->prepare('INSERT INTO sujet (idredacteur,titresujet,textesujet,datesujet) VALUES(:idredacteur,:titresujet,:textesujet,:datesujet)');
$insert_stmt->bindValue("idredacteur", $_SESSION['id'], PDO::PARAM_STR);
$insert_stmt->bindValue("titresujet", $_SESSION['titresujet'], PDO::PARAM_STR);
$insert_stmt->bindValue("textesujet", $_SESSION['textesujet'], PDO::PARAM_STR);
$insert_stmt->bindValue("datesujet", date('Y-m-d H:i:s'), PDO::PARAM_STR);
$insert_stmt->execute();
header("location: index.php");
exit;
?>