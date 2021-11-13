<?php

require_once "config.php";
// print_r($_GET);
// print_r($_POST);

  // echo($_GET['idrep']);
  $delete_stmt = $objPdo->prepare('DELETE FROM reponse WHERE reponse.idsujet=:idsujet');
  $delete_stmt->bindValue("idsujet",  $_GET['idsuj'], PDO::PARAM_STR);
  $delete_stmt->execute();
  $delete_stmt = $objPdo->prepare('DELETE FROM sujet WHERE sujet.idsujet=:idsujet');
  $delete_stmt->bindValue("idsujet",  $_GET['idsuj'], PDO::PARAM_STR);
  $delete_stmt->execute();
  header("Location: index.php" );



?>