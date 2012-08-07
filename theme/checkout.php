<?php 

  $email = strval($_POST['email']);
  $phone = strval($_POST['phone']);

  echo checkout($email, $phone);
?>
