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
$req = 'select * from sujet,redacteur where sujet.idredacteur=redacteur.idredacteur order by datesujet DESC ';
$result = $objPdo->prepare($req);
$result->execute();
$ch = '<table border="1">';
$ch .= '<tr><th>Titre</th><th>Date</th><th>Redacteur</th><th>Sujet</th></tr>';
foreach ($result as $row) {
  $ch .= '<tr>';
  $ch .= '<td>' . $row['titresujet'] . '</td>';
  $ch .= '<td>' . $row['datesujet'] . '</td>';
  $ch .= '<td>' . $row['pseudo'] . '</td>';
  $ch .= '<td>' . $row['textesujet'] . '</td>';
  if (array_key_exists($row['idsujet'], $sujets)) {
    $lib = 'Visualiser';
    if ($sujets[$row['idsujet']] > 1) {
      $lib .= 's';
    }
    $ch .= '<td><a href="visualiserSujet.php?id=' . urlencode($row['idsujet']) . '">' . $lib . '</a></td>';
  } else {
    $ch .= '<td><a href="editSujet.php?a&id=' . urlencode($row['idsujet']) . '">Ajouter Sujet</a></td>';
  }
}
$ch .= '</table>';
unset($result);
?>
<html>

<head>
  <meta charset="utf-8">
  <title>Site de Blog</title>
  <link rel="stylesheet" href="style/style.css">
</head>

<body>
  <h1>Page d'accueil</h1>
  <h2>Liste des sujets</h2>
  <?php
  $btnClass;
  $btnHref;
  $btnTxt;
  if (isset($_SESSION['login'])) {
    if ($_SESSION['login'] == true) {
      
      echo ('<button type="button" class="create" onclick="document.location.href=\'creerSujet.php\'">Créer un sujet</button>');
      echo ('<button type="button" class="disconnect" onclick="document.location.href=\'disconnect.php\'">Deconnexion</button>');
      echo ('<button type="button" class="register" onclick="document.location.href=\'register.php\'">Modifier Compte</button>');
    }
  } else {
    echo ('<button type="button" class="register" onclick="document.location.href=\'register.php\'">S"inscrire</button>');
    echo ('<button type="button" class="authent" onclick="document.location.href=\'authentification.php\'">Connexion</button>');
  }
  ?>
  <!-- <input type="button"class="authent" onclick="document.location.href='connect.php'">S'authentifier</input> -->

  <?php echo ($ch); ?>
</body>

</html>