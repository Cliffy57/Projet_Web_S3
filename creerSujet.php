<?php
session_start();
require_once "config.php";
include_once('header.php');
// define variables and set to empty values
$textesujetErr = $titresujetErr = "";
$textesujet = $titresujet = "";

// echo ($_SESSION["id"]);
// echo (date("Y-m-d"));

if (isset($_POST['submit'])) {


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["titresujet"]))) {
      $titresujetErr = "Precisez le titre de votre Sujet.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $titresujet)) {
      $titresujetErr = "Seul les lettres et les espaces sont toleres";
    } else {
      // Set parameters
      $titresujet = trim($_POST["titresujet"]);
      $_SESSION["titresujet"] = $titresujet;
    }
  }
  if (empty(trim($_POST["textesujet"]))) {
    $textesujetErr = "Precisez le texte de votre Sujet.";
  } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $textesujet)) {
    $textesujetErr = "Seul les lettres et les espaces sont toleres";
  } else {
    // Set parameters
    $textesujet = trim($_POST["textesujet"]);
    $_SESSION["textesujet"] = $textesujet;
  }


  // Check input errors before inserting in database
  if (empty($titresujetErr) && empty($textesujetErr)) {

    $ifExistStmt = $objPdo->prepare("SELECT * FROM sujet where sujet.titresujet = :titresujet");
    $ifExistStmt->bindValue("titresujet", $titresujet, PDO::PARAM_STR);
    $ifExistStmt->execute();
    $nbRow = $ifExistStmt->rowCount();
    if($nbRow == 0){
      ////////////////////////////////
      $insert_stmt = $objPdo->prepare('INSERT INTO sujet (idredacteur,titresujet,textesujet,datesujet) VALUES(:idredacteur,:titresujet,:textesujet,:datesujet)');
      $insert_stmt->bindValue("idredacteur", $_SESSION['id'], PDO::PARAM_STR);
      $insert_stmt->bindValue("titresujet", $titresujet, PDO::PARAM_STR);
      $insert_stmt->bindValue("textesujet", $textesujet, PDO::PARAM_STR);
      $insert_stmt->bindValue("datesujet", date('Y-m-d H:i:s'), PDO::PARAM_STR);
      $insert_stmt->execute();
      header("Location: index.php");
      ////////////////////////////////
    }

    
    
    
  } else {
    $errorAuthent ='<label style="  
    position: fixed;
    color: red;
    display: block;
    left: 6%;
    top:70%;
    width: 50%; "> Erreur lors de la creation du sujet !</label>';
  }
}

?>

  <!DOCTYPE html>
  <html lang="fr">

  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/mainstyle.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
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
      <p>Remplissez ce formulaire afin de creer votre sujet.</p>
      <form class="creation"method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class=create-sujet>
          <input type="text" name="titresujet" placeholder="Titre du sujet" cols="1" rows="1"value='<?php echo $titresujet; ?>'></input>
         
        </div>
        <span class="erreur"><?php echo $titresujetErr; ?></span>
        <?php echo $textesujet?>
        <div class=create-sujet>
          <textarea class="create-sujet"name="textesujet" maxlength="900" placeholder="Texte de votre sujet" cols="30" rows="5" value='<?php echo $textesujet; ?>'></textarea>
          
        </div>
        <span class="erreur"><?php echo $textesujetErr; ?></span><br><br>

            
            <input type="submit" class="btn" name="submit" value="Ajouter le Sujet">
      
      </form>
    </div>
    <?php 
    if(isset($errorAuthent)){
      echo $errorAuthent;
    }
  ?>
    <button type="button" class="btn exit" onclick="document.location.href='index.php'">Retour</button>
  </body>

  </html>
  <?php 
  if (isset($nbRow) && $nbRow != 0 ){
    echo '<script type="text/JavaScript" src="confirmDoublon.js"></script>';
  }
  ?>