<!-- $result rq  -->
while($row=$result->fetch()){
  $idreactcom=$row['idredacteur'];
  idcom=$row['idreponse'];
  echo '<div class="commentairecomplet">';
  echo '<div class="auteurcomplet">';
  echo '<div class="textecommentaire">';
  echo $row['textereponse'];
}