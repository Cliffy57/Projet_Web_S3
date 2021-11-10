<?php
session_start();
require_once "config.php";
include_once('header.php');
if (isset($_GET['id'])) {
  $_POST['idsujet'] = $_GET['id'];
}
// echo ($_GET["id"]);
?>
<html>

<head>
  <meta charset="utf-8">
  <title>Sujet</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  <h1>Voici le sujet:</h1>
  <div class="sujet">
    <dl>
      <dt>Titre :</dt>
      <dd><?php
          // echo ($_GET["id"]);
          $result = $objPdo->query('SELECT *,DATE_FORMAT(datesujet, "%d/%m /%Y") AS datesujetjour,DATE_FORMAT(datesujet, "%h:%i:%s") AS datesujetheure FROM sujet, redacteur WHERE sujet.idredacteur=redacteur.idredacteur AND idsujet=' . $_GET["id"] . '');
          while ($row = $result->fetch()) {
            echo $row['titresujet'] . "</br>";
            // $date = strtotime($row['datesujet']);
            // echo date(':H:i:s', $date);
            echo "Sujet proposé par : " . $row['pseudo'] . ", le : " . $row['datesujetjour'] . " à " . $row['datesujetheure'];
          }
          ?></dd>
      <dt>Sujet :</dt>
      <dd><?php
          $result = $objPdo->query('SELECT textesujet FROM sujet, redacteur WHERE sujet.idredacteur=redacteur.idredacteur AND idsujet=' . $_GET["id"] . '');
          echo $result->fetch()['textesujet'];
          ?></dd>
    </dl>
  </div>
  <h1>Reponse:</h1>
  <div class="reponses">
    <!-- Commenter -->
    <?php
    require_once "config.php";
    if (isset($_SESSION['login'])) {
      if ($_SESSION['login'] == true) {

        $textecom = $textecomErr = "";
        if (isset($_POST['submit'])) {

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty(trim($_POST["textecom"]))) {
              $textecomErr = "Precisez le contenu de votre Commentaire.";
            } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $textecom)) {
              $textecomErr = "Seul les lettres et les espaces sont toleres";
            } else {
              // Set parameters
              $textecom = trim($_POST["textecom"]);
            }
          }


          // Check input errors before inserting in database
          if (empty($textecomErr)) {

            ////////////////////////////////
            $insert_stmt = $objPdo->prepare('INSERT INTO reponse (idsujet,idredacteur,daterep,textereponse) VALUES(:idsujet,:idredacteur,:daterep,:textecom)');
            $insert_stmt->bindValue("idsujet", $_POST["idsujet"], PDO::PARAM_STR);
            $insert_stmt->bindValue("idredacteur", $_SESSION['id'], PDO::PARAM_STR);
            $insert_stmt->bindValue("daterep", date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $insert_stmt->bindValue("textecom", $textecom, PDO::PARAM_STR);
            $insert_stmt->execute();
            // header("Location:index.php");
            ////////////////////////////////
          } else {
            echo "Erreur lors de la creation du commentaire !";
          }
          ////////////////////////////////
        }
      }
      echo ('<form method="post" action="" >

        <div>
                    <label>Texte de votre commentaire:</label>
                    <textarea name="textecom" maxlength="400" value="<?php echo $textecom;?>"></textarea>
                    <span class="erreur"><?php echo $textecomErr ;?></span>
                </div>
              
          <input type="submit" name="submit" value="Commenter">
        </form>');
    }


    ?>
    <!-- Lecture des commentaires -->
    <?php
    require_once('config.php');
    $req = 'select idreponse, count(*) as nb from reponse group by idreponse';
    $result = $objPdo->prepare($req);
    $result->execute();
    $sujets = array();
    foreach ($result as $row) {
      $sujets[$row['idreponse']] = $row['nb'];
    }
    $req = 'select *,DATE_FORMAT(daterep, "%d/%m/%Y à %h:%i:%s") AS daterep from reponse,redacteur where idsujet =' . $_GET["id"] . ' and reponse.idredacteur=redacteur.idredacteur order by daterep DESC ';
    $result = $objPdo->prepare($req);
    $result->execute();
    // $reponseur ='select pseudo from redacteur where redacteur.idredacteur=:idredacteur';
    // $reponseur
    $ch = '<table border="1">';
    $ch .= '<tr><th>Auteur</th><th>Date/Heure</th><th>Commentaire</th><th></th></tr>';
    foreach ($result as $row) {

      $ch .= '<tr>';
      $ch .= '<td>' . $row['pseudo'] . '</td>';
      $ch .= '<td>' . $row['daterep'] . '</td>';
      $ch .= '<td>' . $row['textereponse'] . '</td>';
      if (isset($_SESSION['login'])) {
        if ($_SESSION['login'] == true) {
          if ($_SESSION['id'] == $row['idredacteur']) {
            // $ch .= '<td><a href="supprimerrep.php?a&id=' . urlencode($_GET['id']) . '&idrep=' . urlencode($row['idreponse']) . '">Supprimer</a></td>'; // remplacer par un button
            $ch .= '<form method="post" action="supprimerrep.php?a&id=' . urlencode($_GET['id']) . '&idrep=' . urlencode($row['idreponse']) . '"><td><input type="submit" name="delete" value="Supprimer"></td></form>';

            // if (isset($_POST['delete'])) {
            //   $delete_stmt = $objPdo->prepare('DELETE FROM reponse WHERE reponse.idreponse=:idreponse');
            //   $delete_stmt->bindValue("idreponse",  urlencode($row['idreponse']), PDO::PARAM_STR);
            //   $delete_stmt->execute();
            // }
          }
        }
      }
    }


    $ch .= '</table>';
    unset($result);
    ?>
    <?php echo ($ch); ?>

    <?php


    ?>

    <button type="button" class="btn exit" onclick="document.location.href='index.php'">Retour</button>
  </div>
</body>

</html>