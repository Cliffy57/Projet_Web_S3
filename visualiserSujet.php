<?php
session_start();
require_once "config.php";
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
          ?></dd>
    </dl>
    <?php
    if (isset($_SESSION['login'])) {
      if ($_SESSION['login'] == true) {

        $erreur = array();
        $valeur = array();
        $valeur["texte"] = "";
        $erreur["texte"] = "";
        if (isset($_POST['submit'])) {

          if (!isset($_POST['textecom']) or strlen(trim($_POST['textecom'])) == 0) {
            $erreur['texte'] = 'saisie obligatoire du texte';
          } else {
            $valeur['texte'] = trim($_POST['textecom']);
          }

          // Check input errors before inserting in database
          if (count($erreur) == 0) {

            ////////////////////////////////
            $insert_stmt = $objPdo->prepare('INSERT INTO reponse (idsujet,idredacteur,daterep,textereponse) VALUES(:idsujet,idredacteur,:currentDate,:texte)');
            $insert_stmt->bindValue("idsujet", $_GET['id'], PDO::PARAM_STR);
            $insert_stmt->bindValue("idredacteur", $_SESSION['id'], PDO::PARAM_STR);
            $insert_stmt->bindValue("texte", $_POST['textecom'], PDO::PARAM_STR);
            $insert_stmt->bindValue("currentDate", time(), PDO::PARAM_STR);
            $insert_stmt->execute();
            header("Location: index.php");
            ////////////////////////////////
          }
          
        }
        echo('<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>
        <div>
                    <label>Texte de votre commentaire:</label>
                    <textarea name="textesujet" maxlength="400" value="<?php echo $valeur[\'texte\'];?>"></textarea>
                    <span class="erreur"><?php echo $erreur[\'texte\'] ;?></span>
                </div>
          <input type="submit" name="comment" value="Commenter">
        </form>');
      }
    }
    
    ?>
    <button type="button" class="exit" onclick="document.location.href='index.php'">Retour</button>
  </div>
</body>

</html>