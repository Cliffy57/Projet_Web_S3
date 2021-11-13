<?php

session_start();
require_once('config.php');
include_once('header.php');
// si l'application utilise exclusivement les requêtes préparées,
// alors aucune injection SQL n'est possible
// donc toutes les $_POST, $_GET, etc insérées dans execute() ou bindValue/bindPrepare
// après avoir fait une requete préparée avec prepare() : aucun risque d'injection.
$req = 'select idsujet, count(*) as nb from sujet group by idsujet';
$result = $objPdo->prepare($req);
$result->execute();
$sujets = array();
foreach ($result as $row) {
  $sujets[$row['idsujet']] = $row['nb'];
}
$req = 'select *,DATE_FORMAT(datesujet, "%d/%m/%Y") AS datesujet from sujet,redacteur where sujet.idredacteur=redacteur.idredacteur order by datesujet DESC ';
$result = $objPdo->prepare($req);
$result->execute();
$ch = '<table border="1">';
$ch .= '<tr><th>Titre :</th><th>écrit le :</th><th>par :</th><th></th><th></th></tr>';
// <th>Sujet</th>
foreach ($result as $row) {

  $ch .= '<tr>';
  $ch .= '<td>' . $row['titresujet'] . '</td>';
  $ch .= '<td>' . $row['datesujet'] . '</td>';
  $ch .= '<td>' . $row['pseudo'] . '</td>';
  // $ch .= '<td>' . $row['textesujet'] . '</td>';
  if (array_key_exists($row['idsujet'], $sujets)) {
    $lib = 'Visualiser';
    if ($sujets[$row['idsujet']] > 1) {
      $lib .= 's';
    }
    $ch.='<td><a class="btn" href="visualiserSujet.php?id=' . urlencode($row['idsujet']) . '">' . $lib . '</a></td>';
    if(isset($_SESSION["id"])){
      if($_SESSION["id"] == $row["idredacteur"]){
      $ch.='<td><a class="btn" href="supprimersujet.php?idsuj='.urlencode($row['idsujet']).'">Supprimer</a></td>';
    }
    else{
      $ch.='<td></td>';
    }
  }

  } else {
    $ch .= '<td><a class="btn" href="creerSujet.php?a&id=' . urlencode($row['idsujet']) . '">Ajouter Sujet</a></td>';
  }
}
$ch .= '</table>';
unset($result);
?>
<html>

<head>
  <meta charset="utf-8">
  <title>Site de Blog</title>
  <link rel="stylesheet" href="css/mainstyle.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  <h1 class="page-header">Page d'accueil</h1>
  <h2 class="page-header--sec">Liste des sujets</h2>
  <div class="button-nav">
  <?php
  $btnClass;
  $btnHref;
  $btnTxt;
  if (isset($_SESSION['login'])) {
    if ($_SESSION['login'] == true) {

      echo ('<button type="button" class="btn create" onclick="document.location.href=\'creerSujet.php\'">Créer un sujet</button>');
      echo ('<button type="button" class="btn register" onclick="document.location.href=\'register.php\'">Modifier Compte</button>');
      echo ('<button type="button" class="btn" id="disconnect">Deconnexion</button>');
    }
  } else {
    echo ('<button type="button" class="btn register" onclick="document.location.href=\'register.php\'">S\'inscrire</button>');
    echo ('<button type="button" class="btn authent" onclick="document.location.href=\'authentification.php\'">Connexion</button>');
  }
  ?>
  </div>
  <!-- <input type="button"class="authent" onclick="document.location.href='connect.php'">S'authentifier</input> -->
  <div class="table">
      <?php echo ($ch); ?>
  </div>

</html>
<script type="text/javascript" src="disconnect.js"></script>