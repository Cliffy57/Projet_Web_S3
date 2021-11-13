<?php
\session_start();
require_once "config.php";
include_once('header.php');

// define variables and set to empty values
$nomErr = $prenomErr = $pseudoErr = $mailErr = $mdpErr = "";
$nom = $prenom = $pseudo = $mail = $mdp = "";

if (isset($_SESSION["login"])) {
  if ($_SESSION["login"] == true) {
    $query = "SELECT * FROM redacteur
       WHERE idredacteur = :idredacteur";

    $statement = $objPdo->prepare($query);
    $statement->bindValue(":idredacteur", $_SESSION["id"], PDO::PARAM_INT);
    $statement->bindColumn(2, $nom);
    $statement->bindColumn(3, $prenom);
    $statement->bindColumn(4, $mail);
    $statement->bindColumn(5, $mdp);
    $statement->bindColumn(6, $pseudo);
    $statement->execute();
    foreach ($statement as $row) {
    }
    // print_r($_SESSION);
  }
}





if (isset($_POST['submit'])) {

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["nom"]))) {
      $nomErr = "Precisez votre nom.";
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $nom)) {
      $nomErr = "Seul les lettres et les espaces sont toleres";
    } else {
      // Set parameters
      $nom = trim($_POST["nom"]);
    }
  }
  // Validate prenom
  if (empty(trim($_POST["prenom"]))) {
    $prenomErr = "Precisez votre prenom.";
  } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $prenom)) {
    $prenomErr = "Seul les lettres et les espaces sont toleres";
  } else {
    // Set parameters
    $prenom = trim($_POST["prenom"]);
  }
  // Validate pseudo
  if (empty(trim($_POST["pseudo"]))) {
    $pseudoErr = "Precisez votre pseudo.";
  } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["pseudo"]))) {
    $pseudoErr = "Le pseudo ne peut contenir que des lettres, des chiffres et des underscores.";
  } else {
    $pseudo = trim($_POST["pseudo"]);
  }

  // Validate email
  if (empty(trim($_POST["mail"]))) {
    $mailErr = "Precisez votre mail.";
  } elseif (!filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
    // check if e-mail address is well-formed
    $mailErr = "Mauvais format d'adresse mail";
  } else {
    $mail = trim($_POST["mail"]);
  }
  // Validate password
  if (empty(trim($_POST["mdp"]))) {
    $mdpErr = "Entrer un mot de passe.";
  } elseif (strlen(trim($_POST["mdp"])) < 6) {
    $mdpErr = "Le mot de passe doit comporter au moins 6 caractères.";
  } else {
    $mdp = trim($_POST["mdp"]);
  }
  $ifExistStmt = $objPdo->prepare("SELECT * FROM redacteur where redacteur.adressemail = :adressemail OR redacteur.pseudo = :pseudo");
  $ifExistStmt->bindValue("adressemail", $mail, PDO::PARAM_STR);
  $ifExistStmt->bindValue("pseudo", $pseudo, PDO::PARAM_STR);
  $ifExistStmt->execute();
  $nbRow = $ifExistStmt->rowCount();

    // Check input errors before inserting in database
    if (isset($_SESSION['login'])) {
      if ($_SESSION['login'] == true) {
        $update_stmt = $objPdo->prepare("UPDATE redacteur SET nom = :nom ,prenom = :prenom,motdepasse = :mdp WHERE redacteur.idredacteur=" . $_SESSION['id'] . "");
        $update_stmt->bindValue("nom", $nom, PDO::PARAM_STR);
        $update_stmt->bindValue("prenom", $prenom, PDO::PARAM_STR);
        $update_stmt->bindValue("mdp", $mdp, PDO::PARAM_STR);
        $update_stmt->execute();
        header("Location:index.php");
      }
    }
      if ($nbRow == 0){
        if (empty($nomErr) && empty($prenomErr) && empty($pseudoErr) && empty($mailErr) && empty($mdpErr)) {

        ////////////////////////////////
        $insert_stmt = $objPdo->prepare("INSERT INTO redacteur(nom, prenom, adressemail, motdepasse, pseudo) VALUES (:nom, :prenom, :adressemail, :mdp, :pseudo)");
        $insert_stmt->bindValue("nom", $nom, PDO::PARAM_STR);
        $insert_stmt->bindValue("prenom", $prenom, PDO::PARAM_STR);
        $insert_stmt->bindValue("adressemail", $mail, PDO::PARAM_STR);
        $insert_stmt->bindValue("mdp", $mdp, PDO::PARAM_STR);
        $insert_stmt->bindValue("pseudo", $pseudo, PDO::PARAM_STR);
        $insert_stmt->execute();

        header("Location: index.php");
        ////////////////////////////////
      }
  }
  else{
    echo '<script type="text/JavaScript"> 
      alert("pseudo ou email déjà utilisé, veuillez vérifier vos données !")
    </script>';
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

  <title>S'enregister</title>
  <style>
    .error {
      color: #FF0000;
    }

    .wrapper {
      width: 360px;
      padding: 20px;
    }
  </style>
</head>

<body>

  <div class="wrapper">
    <?php
    if (isset($_SESSION['login'])) {
      if ($_SESSION['login'] == true) {
        echo ('<h2>Modification dun compte de redacteur</h2></br><p>Remplissez ce formulaire afin de modifier votre compte.</p>');
        if ($_SESSION['id'] == $row['idredacteur']) {
          echo '<form method="post" action="supprimercompte.php?a&id=' . urlencode($_SESSION['id']) . '"><td><input type="submit" class="btn" id="deleteaccount"name="delete" value="Supprimer le compte"onclick="confirm(\'Etes vous certains de vouloir supprimer votre compte ? Cela engendrera la suppression definitive des Sujets/Reponses provenant de votre compte.\')"></td></form>';
        }
      }
    } else {
      echo ('<h2>Creation dun compte de redacteur</h2></br><p>Remplissez ce formulaire afin de creer votre compte.</p>');
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <label class="modif-label">Nom:</label> <input class="modif-input" type="text" name="nom" value="<?php echo $nom; ?>">
      <span class="error">* <?php echo $nomErr; ?></span>
      <br><br>
      <label class="modif-label"> Prenom:</label> <input class="modif-input" type="text" name="prenom" value="<?php echo $prenom; ?>">
      <span class="error">* <?php echo $prenomErr; ?></span>
      <br><br>
      <?php 
      if(!isset($_SESSION['login']))
      {
        echo
        '<label class="modif-label">Pseudo:</label> <input class="modif-input" type="text" name="pseudo" value="'.$pseudo.'">
        <span class="error">* '.$pseudoErr.'</span>
        <br><br>
        <label class="modif-label">E-mail:</label>  <input class="modif-input" type="text" name="mail" value="'.$mail.'">
        <span class="error">* '.$mailErr.'</span>
        <br><br>';
      }?>
      <br><br>
      <label class="modif-label">Mot de Passe: </label><input class="modif-input" type="text" name="mdp" value="<?php echo $mdp; ?>">
      <span class="error">* <?php echo $mdpErr; ?></span>
      <br><br>
      <div class="create-btn">
      <input type="submit" class="btn" name="submit" value="<?php if (isset($_SESSION["login"])) {
                                                              echo "Mettre a jour";
                                                            } else {
                                                              echo "S'enregistrer";
                                                            } ?>">
      <button type="button" class="btn retour" onclick="document.location.href='index.php'">Retour</button>
      </div>
    </form>
  </div>
</body>

</html>