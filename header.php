<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="public/css/style.css">
  <?php
  require_once('config.php');

  if (isset($_SESSION['login'])) {
    if ($_SESSION['login'] == true) {
      // echo ($_SESSION['id'].' is user: ');
      // echo($display_stmt->execute()); result of query (boolean ture or false (0 or 1))
      $result = $objPdo->query('SELECT pseudo FROM redacteur WHERE idredacteur='.$_SESSION['id'].'');
          
    }
  }
  ?>
  <p>Bienvenue, <?php if (isset($_SESSION['login'])) {
    if ($_SESSION['login'] == true) {echo $result->fetch()['pseudo'];}}?></p>
</head>

<body>