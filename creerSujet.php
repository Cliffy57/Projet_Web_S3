<?php
require_once "config.php";
// define variables and set to empty values
$erreur=array();
$valeur=array();
$valeur["titre"]=""; $erreur["titre"]="";
$valeur["texte"]=""; $erreur["texte"]="";
if (isset($_POST['submit'])) {


if (!isset($_POST['titresujet']) or strlen(trim($_POST['titresujet']))==0)
{ $erreur['titre'] = 'saisie obligatoire du titre'; }
else { $valeur['titre'] = trim($_POST['titresujet']); }
if (!isset($_POST['textesujet']) or strlen(trim($_POST['textesujet']))==0)
{ $erreur['texte'] = 'saisie obligatoire du texte'; }
else { $valeur['texte'] = trim($_POST['textesujet']); }

  // Check input errors before inserting in database
  if (count($erreur)==0){

    ////////////////////////////////
    $insert_stmt = $objPdo->prepare('INSERT INTO sujet (idredacteur,titresujet,textesujet,datesujet) VALUES(:idredacteur,:titre,:texte,:date)');
    $insert_stmt->bindValue("idredacteur", $_SESSION['id'], PDO::PARAM_STR);
    $insert_stmt->bindValue("titre", $titresujet, PDO::PARAM_STR);
    $insert_stmt->bindValue("texte", $textesujet, PDO::PARAM_STR);
    $insert_stmt->bindValue("currentDate",date("Y/m/d"), PDO::PARAM_STR);
    $insert_stmt->execute();
    header("Location: index.php");
    ////////////////////////////////
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
                <input type="text" name="titresujet" value='<?php echo $valeur["titre"];?>'></input>
                <span class="erreur"><?php echo $erreur['titre'] ;?></span>
            </div>
            <div>
                <label>Texte de votre sujet:</label>
                <textarea name="textesujet" maxlength="900" value="<?php echo $valeur['texte'];?>"></textarea>
                <span class="erreur"><?php echo $erreur['texte'] ;?></span>
            </div>
      <input type="submit" name="submit" value="Ajouter le Sujet">
    </form>
  </div>
  <button type="button" class="exit" onclick="document.location.href='index.php'">Retour</button>
</body>

</html>