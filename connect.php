<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
<title>Connect Account</title>
</head>
<body>  

<?php
// define variables and set to empty values
$loginErr = $mdpErr= "";
$login = $mdp= "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["login"])) {
    $loginErr = "Precisez votre login";
  }
  }
  if (empty($_POST["mdp"])) {
    $mdpErr = "Precisez votre mot de passe";
  }

if (isset($_POST['submit'])) {
  if (empty($loginErr) && empty($mdpErr)) {

    $login=$_REQUEST['login'];
    $query = "
    SET @username = :username
    SELECT * FROM redacteur
       WHERE ( username = @username OR email = @username) 
       AND mdp = :mdp
";

    $statement = $pdoObject->prepare($query);
    $statement->bindValue(":username", $login, PDO::PARAM_STR);
    $statement->bindValue(":mdp", $mdp, PDO::PARAM_STR);
    $statement->execute();
    
    header("Location: index.php");
    ////////////////////////////////
  }
}
?>

<h2>Connexion a un compte de redacteur</h2>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Pseudo(ou e-mail): <input type="text" name="lgin" value="<?php echo $login;?>">
  <span class="error">* <?php echo $loginErr;?></span>
  <br><br>
  Mot de Passe: <input type="text" name="mdp" value="<?php echo $mdp;?>">
  <span class="error">* <?php echo $mdpErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Se Connecter">  
</form>
</body>
</html>
<!--  -->

