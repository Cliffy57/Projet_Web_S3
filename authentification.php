<?php
session_start();
require_once "config.php";

// define variables and set to empty values
$loginErr = $mdpErr = "";
$login = $mdp = "";
$idredac;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["login"])) {
    $loginErr = "Precisez votre login";
  }

  if (empty($_POST["mdp"])) {
    $mdpErr = "Precisez votre mot de passe";
  }
}

if (isset($_POST['submit'])) {
  if (empty($loginErr) && empty($mdpErr)) {


    $query = "
    SELECT * FROM redacteur
       WHERE ( pseudo = :username OR adressemail = :username) 
       AND motdepasse = :mdp";

    $statement = $objPdo->prepare($query);
    $statement->bindValue(":username", $_POST["login"], PDO::PARAM_STR);
    $statement->bindValue(":mdp", $_POST["mdp"], PDO::PARAM_STR);
    $statement->bindColumn(1, $id);
    $statement->execute();
    // while ($statement->fetch(PDO::FETCH_BOUND)){
    //   print $id;
    // }
    foreach ($statement as $row) {
      $idredac = $id;
      $_SESSION["id"] = $idredac;
    }


    if (isset($idredac)) {
      $_SESSION['login'] = true;
      header("Location: index.php");
    } else {
      print "Error";
    }
    ////////////////////////////////
  }
}
?>
<!DOCTYPE HTML>
<html>

<head>
<link rel="stylesheet" href="css/style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
  <style>
    .error {
      color: #FF0000;
    }
  </style>
  <title>Connect Account</title>
</head>

<body>



  <h2>Connexion a un compte de redacteur</h2>

  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    Pseudo(ou e-mail): <input type="text" name="login" value="<?php echo $login; ?>">
    <span class="error">* <?php echo $loginErr; ?></span>
    <br><br>
    Mot de Passe: <input type="text" name="mdp" value="<?php echo $mdp; ?>">
    <span class="error">* <?php echo $mdpErr; ?></span>
    <br><br>
    <input type="submit" class="btn" name="submit" value="Se Connecter">
  </form>
  <button type="button" class="btn exit" onclick="document.location.href='index.php'">Retour</button>
</body>

</html>