var buttonDisconnect = document.getElementById('disconnect');
buttonDisconnect.addEventListener('click',(e) => {
  if (confirm('Etes vous certains de vouloir vous deconnecter ?')){
    document.location.href="disconnect.php";

  }
});