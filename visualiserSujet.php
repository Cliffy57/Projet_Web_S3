<?php
session_start();
require_once "config.php";
include_once('header.php');
// echo ($_GET["id"]);
?>
<html>

<head>
  <meta charset="utf-8">
  <title>Sujet</title>
  <link rel="stylesheet" href="style/style.css">
</head>

<body>
  <h1>Voici le sujet:</h1>
  <div class="sujet">
    <dl>
      <dt>Titre :</dt>
      <dd><?php
          $result = $objPdo->query('SELECT * FROM sujet, redacteur WHERE sujet.idredacteur=redacteur.idredacteur AND idsujet=' . $_GET["id"] . '');
          while ($row = $result->fetch()) {
            echo $row['datesujet'] . " __ " . $row['pseudo'] . " __ " . $row['titresujet'];
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
            $insert_stmt->bindValue("idsujet", $_GET["id"], PDO::PARAM_STR);
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
    $req = 'select * from reponse,redacteur where idsujet =' . $_GET["id"] . ' and reponse.idredacteur=redacteur.idredacteur order by daterep DESC ';
    $result = $objPdo->prepare($req);
    $result->execute();
    // $reponseur ='select pseudo from redacteur where redacteur.idredacteur=:idredacteur';
    // $reponseur
    $ch = '<table border="1">';
    $ch .= '<tr><th>Auteur</th><th>Date</th><th>Commentaire</th><th></th></tr>';
    foreach ($result as $row) {
    
      $ch .= '<tr>';
      $ch .= '<td>' . $row['pseudo'] . '</td>';
      $ch .= '<td>' . $row['daterep'] . '</td>';
      $ch .= '<td>' . $row['textereponse'] . '</td>';
      if (isset($_SESSION['login'])) {
        if ($_SESSION['login'] == true) {
          if($_SESSION['id']==$row['idredacteur']){
            include('supprimerrep.php');
            
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

    <button type="button" class="exit" onclick="document.location.href='index.php'">Retour</button>
  </div>
</body>

</html>