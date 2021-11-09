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

  // Check input errors before inserting in database
  if (isset($_SESSION['login'])) {
    if ($_SESSION['login'] == true) {
      $update_stmt = $objPdo->prepare("UPDATE redacteur SET nom = :nom ,prenom = :prenom,adressemail = :adressemail,motdepasse = :mdp,pseudo = :pseudo WHERE redacteur.idredacteur=" . $_SESSION['id'] . "");
      $update_stmt->bindValue("nom", $nom, PDO::PARAM_STR);
      $update_stmt->bindValue("prenom", $prenom, PDO::PARAM_STR);
      $update_stmt->bindValue("adressemail", $mail, PDO::PARAM_STR);
      $update_stmt->bindValue("mdp", $mdp, PDO::PARAM_STR);
      $update_stmt->bindValue("pseudo", $pseudo, PDO::PARAM_STR);
      $update_stmt->execute();
      header("Location:index.php");
    }
  } else if (empty($nomErr) && empty($prenomErr) && empty($pseudoErr) && empty($mailErr) && empty($mdpErr)) {

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

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>S'enregister</title>
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
    <?php
    if (isset($_SESSION['login'])) {
      if ($_SESSION['login'] == true) {
        echo ('<h2>Modification dun compte de redacteur</h2></br><p>Remplissez ce formulaire afin de modifier votre compte.</p>');
      }
    } else {
      echo ('<h2>Creation dun compte de redacteur</h2></br><p>Remplissez ce formulaire afin de creer votre compte.</p>');
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      Nom: <input type="text" name="nom" value="<?php echo $nom; ?>">
      <span class="error">* <?php echo $nomErr; ?></span>
      <br><br>
      Prenom: <input type="text" name="prenom" value="<?php echo $prenom; ?>">
      <span class="error">* <?php echo $prenomErr; ?></span>
      <br><br>
      Pseudo: <input type="text" name="pseudo" value="<?php echo $pseudo; ?>">
      <span class="error">* <?php echo $pseudoErr; ?></span>
      <br><br>
      E-mail: <input type="text" name="mail" value="<?php echo $mail; ?>">
      <span class="error">* <?php echo $mailErr; ?></span>
      <br><br>
      Mot de Passe: <input type="text" name="mdp" value="<?php echo $mdp; ?>">
      <span class="error">* <?php echo $mdpErr; ?></span>
      <br><br>
      <input type="submit" name="submit" value="<?php if (isset($_SESSION["login"])) {
                                                  echo "Mettre a jour";
                                                } else {
                                                  echo "S'enregistrer";
                                                } ?>">
      <button type="button" class="create" onclick="document.location.href='index.php'">Retour</button>
    </form>
  </div>
</body>

</html>