
<?php

session_start();
$_SESSION['url'] ='index.php';
require_once('config.php');
// si l'application utilise exclusivement les requêtes préparées,
// alors aucune injection SQL n'est possible
// donc toutes les $_POST, $_GET, etc insérées dans execute() ou bindValue/bindPrepare
// après avoir fait une requete préparée avec prepare() : aucun risque d'injection.
$req = 'select idsujet, count(*) as nb from sujet group by idsujet';
$result = $objPdo->prepare($req);
$result->execute();
$sujets=array();
foreach($result as $row)
{ $sujets[$row['idsujet']]=$row['nb']; }
$req = 'select * from sujet';
$result = $objPdo->prepare($req);
$result->execute();
$ch = '<table border="1">';
$ch .='<tr><th>Titre</th><th>Date</th><th>Redacteur</th><th>Sujet</th></tr>';
foreach($result as $row)
{
$ch .='<tr>';
$ch .='<td>' .$row['titresujet'] . '</td>';
$ch .='<td>' .$row['datesujet'] . '</td>';
$ch .='<td>' .$row['idredacteur'] . '</td>';
$ch .='<td>' .$row['textesujet'] . '</td>';
if (array_key_exists($row['idsujet'],$sujets))
{
$lib = 'Visualiser';
if ($sujets[$row['idsujet']] > 1)
{ $lib .= 's'; }
$ch .='<td><a href="visualiserSujet.php?id=' .urlencode($row['idsujet']) .'">' .$lib .'</a></td>';
}
else
{
$ch .='<td><a href="editSujet.php?a&id=' .urlencode($row['idsujet']) .'">Ajouter Sujet</a></td>';
}
}
$ch .= '</table>';
unset($result);
?>
<html>
<head>
<meta charset="utf-8">
<title>Site de Blog</title>
<link rel="stylesheet" href="style/style.css">
</head>
<body>
<h1>Page d'accueil</h1>
<h2>Liste des sujets</h2>
<button type="button"class="authent" onclick="document.location.href='connect.php'">S'authentifier</button>
<button type="button"class="register"onclick="document.location.href='register.php'">S'inscrire</button>
<?php echo($ch); ?>
</body>
</html>