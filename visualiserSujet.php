<?php
 session_start();require_once "config.php";
 ?>
<html>
<head>
<meta charset="utf-8">
<title>Sujet</title>
<link rel="stylesheet" href="style/style.css">
</head>
<body>
<h1>Voici le sujet:</h1>
<div class="sujet">
<dl>
  <dt>Titre :</dt>
  <dd><?php 
  $result = $objPdo->query('SELECT * FROM sujet, redacteur WHERE sujet.idredacteur=redacteur.idredacteur AND idsujet='.$_GET["id"].'');
  while ($row=$result->fetch()){
    echo $row['datesujet']." __ ".$row['pseudo']." __ ".$row['titresujet'];
  }
   ?></dd>
  <dt>Sujet :</dt>
  <dd><?php
   $result = $objPdo->query('SELECT textesujet FROM sujet, redacteur WHERE sujet.idredacteur=redacteur.idredacteur AND idsujet='.$_GET["id"].'');
   echo $result->fetch()['textesujet'];
   ?></dd>
</dl>
</div>
<h1>Reponse:</h1>
<div class="reponses">
<dl>
  <dt>Commenteur :</dt>
  <dd><?php 
  $result = $objPdo->query('SELECT DATE_FORMAT(daterep, "%w %e %Y") AS daterep,pseudo FROM redacteur,sujet, reponse WHERE sujet.idsujet=reponse.idreponse AND reponse.idredacteur=redacteur.idredacteur AND idreponse='.$_GET["id"].'');
  while ($row=$result->fetch()){
    echo $row['daterep']." __ ".$row['pseudo'];
  }
   ?></dd>
  <dt>Commentaire :</dt>
  <dd><?php
   $result = $objPdo->query('SELECT textereponse FROM sujet, reponse WHERE sujet.idredacteur=reponse.idredacteur AND idreponse='.$_GET["id"].'');
   echo $result->fetch()['textereponse'];
   ?></dd>
</dl>
</div>
</body>
</html>