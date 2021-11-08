<?php

require_once "config.php";
include_once('visualiserSujet.php');
print_r($_GET);
print_r($_POST);

if (isset($_POST['delete'])) {
  echo('mouton');
  echo($_GET['idrep']);
  $delete_stmt = $objPdo->prepare('DELETE FROM reponse WHERE reponse.idreponse=:idreponse');
  $delete_stmt->bindValue("idreponse",  $_GET['idrep'], PDO::PARAM_STR);
  $delete_stmt->execute();
  header("Location: visualiserSujet.php?id=" . $_POST['idsujet'] );

}

?>
