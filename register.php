
<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
<title>Register Account</title>
</head>
<body>  

<?php
// define variables and set to empty values
$nomErr = $prenomErr = $pseudoErr = $mailErr = $mdpErr= "";
$nom = $prenom = $pseudo = $mail = $mdp= "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["nom"])) {
    $nom = "Precisez votre nom";
  } else {
    $nom = test_input($_POST["nom"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$nom)) {
      $nameErr = "Seul les lettres et les espaces sont toleres";
    }
  }
  if (empty($_POST["prenom"])) {
    $prenomErr = "Precisez votre prenom";
  } else {
    $prenom = test_input($_POST["prenom"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$prenom)) {
      $prenomErr = "Seul les lettres et les espaces sont toleres";
    }
  }
  if (empty($_POST["pseudo"])) {
    $pseudoErr = "Precisez votre pseudo";
  } else {
    $pseudo = test_input($_POST["pseudo"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$pseudo)) {
      $pseudoErr = "Seul les lettres et les espaces sont toleres";
    }
  }
  if (empty($_POST["mail"])) {
    $mailErr = "Precisez votre addresse E-Mail";
  } else {
    $mail = test_input($_POST["mail"]);
    // check if e-mail address is well-formed
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      $mailErr = "Mauvais format d'adresse mail";
    }
  }

  if (empty($_POST["mdp"])) {
    $mdpErr = "Un mot de passe est necessaire";
  } else {
    $mdp = test_input($_POST["mdp"]);
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!-- $nomErr = $prenomErr = $pseudoErr = $mailErr = $mdpErr= "";
$nom = $prenom = $pseudo = $mail = $mdp= "";  -->

<h2>Creation d'un compte de redacteur</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Nom: <input type="text" name="nom" value="<?php echo $nom;?>">
  <span class="error">* <?php echo $nomErr;?></span>
  <br><br>
  Prenom: <input type="text" name="prenom" value="<?php echo $prenom;?>">
  <span class="error">* <?php echo $prenomErr;?></span>
  <br><br>
  Pseudo: <input type="text" name="pseudo" value="<?php echo $pseudo;?>">
  <span class="error">* <?php echo $pseudoErr;?></span>
  <br><br>
  E-mail: <input type="text" name="mail" value="<?php echo $mail;?>">
  <span class="error">* <?php echo $mailErr;?></span>
  <br><br>
  Mot de Passe: <input type="text" name="mdp" value="<?php echo $mdp;?>">
  <span class="error">* <?php echo $mdpErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="S'enregistrer">  
</form>

<?php
echo "<h2>Vos informations:</h2>";
echo $nom;
echo "<br>";
echo $prenom;
echo "<br>";
echo $pseudo;
echo "<br>";
echo $mail;
echo "<br>";
echo $mdp;
?>

</body>
</html>