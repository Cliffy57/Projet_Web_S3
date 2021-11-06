<?php
session_start();
require_once "config.php";
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
    <dl>
      <dt>Commenteur :</dt>
      <dd><?php
          $result = $objPdo->query('SELECT DATE_FORMAT(daterep, "%w %e %Y") AS daterep,pseudo FROM redacteur,sujet, reponse WHERE sujet.idsujet=reponse.idreponse AND reponse.idredacteur=redacteur.idredacteur AND idreponse=' . $_GET["id"] . '');
          while ($row = $result->fetch()) {
            echo $row['daterep'] . " __ " . $row['pseudo'];
          }
          ?></dd>
      <dt>Commentaire :</dt>
      <dd><?php
          $result = $objPdo->query('SELECT textereponse FROM sujet, reponse WHERE sujet.idredacteur=reponse.idredacteur AND idreponse=' . $_GET["id"] . '');
          echo $result->fetch()['textereponse'];
          //gerer quand ya pas de com
          ?></dd>
    </dl>
    <?php
    require_once "config.php";
    if (isset($_SESSION['login'])) {
      if ($_SESSION['login'] == true) {

        $textecom=$textecomErr="";
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
            $insert_stmt->bindValue("idsujet",$_GET["id"], PDO::PARAM_STR);
            $insert_stmt->bindValue("idredacteur", $_SESSION['id'], PDO::PARAM_STR);
            $insert_stmt->bindValue("daterep", date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $insert_stmt->bindValue("textecom", $textecom, PDO::PARAM_STR);
            $insert_stmt->execute();
            header("Location:index.php");
            ////////////////////////////////
          } else {
            echo "Erreur lors de la creation du commentaire !";
          }
            ////////////////////////////////
          }
          
        }
        echo('<form method="post" action="'.$_SERVER['PHP_SELF'].'" >

        <div>
                    <label>Texte de votre commentaire:</label>
                    <textarea name="textecom" maxlength="400" value="<?php echo $textecom;?>"></textarea>
                    <span class="erreur"><?php echo $textecomErr ;?></span>
                </div>
          <input type="submit" name="submit" value="Commenter">
        </form>');
      }
    
    
    ?>
    <button type="button" class="exit" onclick="document.location.href='index.php'">Retour</button>
  </div>
</body>

</html>