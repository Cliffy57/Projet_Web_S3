<?php

require_once "config.php";
include_once('visualiserSujet.php');
if (isset($_POST['delete'])) {
  $delete_stmt = $objPdo->query('DELETE FROM reponse WHERE reponse.idreponse=' . $row['idreponse'] . '');
  // $delete_stmt = $objPdo->prepare('DELETE reponse (idreponse,idsujet,idredacteur,daterep,textereponse) VALUES(:idreponse,:idsujet,:idredacteur,:daterep,:textereponse)');
  // $delete_stmt->bindValue("idreponse", $row['idreponse'], PDO::PARAM_STR);
  // $delete_stmt->bindValue("idsujet", $_GET["id"], PDO::PARAM_STR);
  // $delete_stmt->bindValue("idredacteur", $_SESSION['id'], PDO::PARAM_STR);
  // $delete_stmt->bindValue("daterep",$row['daterep'], PDO::PARAM_STR);
  // $delete_stmt->bindValue("textreponse", $row['textereponse'], PDO::PARAM_STR);
  // $delete_stmt->execute();
  // header("Location: index.php");
}
// echo($row['idreponse']);


?>
<?php
$ch .= "<form method='post'><td><input name='delete' type='submit' value='Supprimer' class='delete'></td></form>";

?>

