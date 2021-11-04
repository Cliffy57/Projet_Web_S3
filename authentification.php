<?php
session_start();
if (isset($_POST['user']) && isset($_POST['mdp'])) {
  $bSoumis = 1;
  if ($_POST['user'] == 'user1' && $_POST['mdp'] == 'secret') {
    $_SESSION['login'] = 'ok';
    if ($_SESSION['url'] != '')
      header("location: {$_SESSION['url']}");
    else
      header("location: index.php");
  }
} else
  $bSoumis = 0;
?>
<html>

<head>
  <title>Authentification</title>
</head>

<body> <?php
        if ($bSoumis == 1)
          echo '<h3>Désolé, réessayez!</h3>';
        else
          echo 'Pour accèder à cette page il est nécessaire de vous identifier!'; ?>
  <form action="authent.php" method="post">
    Identifiant:<br />
    <input type="text" name="user"><br />
    Mot de passe:<br />
    <input type="password" name="mdp"><br />
    <input type="submit" name="" value="Valider">
  </form>
  <a href="accueil.php">Retour à la page d'accueil</a><br />
</body>

</html>