<?php session_start(); ?>
<html>
<head>
<meta charset="utf-8">
<title>Sujet</title>
<link rel="stylesheet" href="style/style.css">
</head>
<body>
<?php
include 'sujet.class.php';
$sujets = unserialize(file_get_contents('data/sujets'));
if (isset($_GET['ref']))
{	$unsujet = $sujets[$_GET['ref']];	}
else
{	header("location:index.php");		}
?>
<h1>Voici le sujet:</h1>
<dl>
  <dt>Référence :</dt>
  <dd><?php echo $_GET['ref'] ?></dd>
  <dt>Designation :</dt>
  <dd><?php echo $unarticle->getDesignation() ?></dd>
  <dt>Prix unitaire :</dt>
  <dd><?php echo $unarticle->getPrix() ?></dd>
</dl>
<form name="achat" action="ajouter.php<?php echo '?ref='.$_GET['ref'] ?>"
	  method="post">
  <label for="commentaire">Commentaire</label>
  <input id="commentaire" name="commentaire" type="text" />
  <input type="submit" value="Ajouter un commentaire" /> 
</form>
<a href="commander.php">Annuler l'achat et retourner à la liste des articles</a>
</body>
</html>