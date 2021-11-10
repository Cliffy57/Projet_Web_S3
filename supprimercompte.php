<?php

require_once "config.php";
include_once('register.php');
// print_r($_GET);
// print_r($_POST);

if (isset($_POST['delete'])) {

  // echo($_GET['idrep']);
  $secdelete_stmt = $objPdo->prepare('DELETE FROM sujet WHERE sujet.idredacteur=:idredacteur');
  $secdelete_stmt->bindValue("idredacteur",  $_GET['id'], PDO::PARAM_STR);
  $secdelete_stmt->execute();
  $delete_stmt = $objPdo->prepare('DELETE FROM reponse WHERE reponse.idredacteur=:idredacteur');
  $delete_stmt->bindValue("idredacteur",  $_GET['id'], PDO::PARAM_STR);
  $delete_stmt->execute();
  $delete_stmt = $objPdo->prepare('DELETE FROM redacteur WHERE redacteur.idredacteur=:idredacteur');
  $delete_stmt->bindValue("idredacteur",  $_GET['id'], PDO::PARAM_STR);
  $delete_stmt->execute();
 
  
  
  header("Location: disconnect.php");

}

?>