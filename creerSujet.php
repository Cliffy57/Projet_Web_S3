<?php
session_start();
require_once "config.php";
// define variables and set to empty values
$textesujetErr = $titresujetErr = "";
$textesujet = $titresujet = "";

echo ($_SESSION["id"]);
echo (date("Y-m-d"));
if (isset($_POST['submit'])) {


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["titresujet"]))) {
      $titresujetErr = "Precisez le titre de votre Sujet.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $titresujet)) {
      $titresujetErr = "Seul les lettres et les espaces sont toleres";
    } else {
      // Set parameters
      $titresujet = trim($_POST["titresujet"]);
    }
  }
  if (empty(trim($_POST["textesujet"]))) {
    $textesujetErr = "Precisez le texte de votre Sujet.";
  } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $textesujet)) {
    $textesujetErr = "Seul les lettres et les espaces sont toleres";
  } else {
    // Set parameters
    $textesujet = trim($_POST["textesujet"]);
  }


  // Check input errors before inserting in database
  if (empty($titresujetErr) && empty($textesujetErr)) {

    ////////////////////////////////
    $insert_stmt = $objPdo->prepare('INSERT INTO sujet (idredacteur,titresujet,textesujet,datesujet) VALUES(:idredacteur,:titresujet,:textesujet,:datesujet)');
    $insert_stmt->bindValue("idredacteur", $_SESSION['id'], PDO::PARAM_STR);
    $insert_stmt->bindValue("titresujet", $titresujet, PDO::PARAM_STR);
    $insert_stmt->bindValue("textesujet", $textesujet, PDO::PARAM_STR);
    $insert_stmt->bindValue("datesujet", date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $insert_stmt->execute();
    header("Location: index.php");
    ////////////////////////////////
  } else {
    echo "Erreur lors de la creation du sujet !";
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Creation d'un Sujet</title>
  <style>
    .error {
      color: #FF0000;
    }

    body {
      font: 14px sans-serif;
    }

    .wrapper {
      width: 360px;
      padding: 20px;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <h2>Creation d'un Sujet</h2>
    <p>Remplissez ce formulaire afin de creer votre compte.</p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div>
        <label>Titre du sujet:</label>
        <input type="text" name="titresujet" value='<?php echo $titresujet; ?>'></input>
        <span class="erreur"><?php echo $erreur['titre']; ?></span>
      </div>
      <div>
        <label>Texte de votre sujet:</label>
        <textarea name="textesujet" maxlength="900" value="<?php echo $textesujet; ?>"></textarea>
        <span class="erreur"><?php echo $erreur['texte']; ?></span>
      </div>
      <input type="submit" name="submit" value="Ajouter le Sujet">
    </form>
  </div>
  <button type="button" class="exit" onclick="document.location.href='index.php'">Retour</button>
</body>

</html>