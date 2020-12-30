<?php


  include "config.php";
  include "functions.php";

  $uem = $_POST['uem'];
  $ups = $_POST['ups'];

  if(sizeof(getItemWhere("users", "username", $uem)) > 0 && getItemWhere("users", "username", $uem)['password'] == $ups) {
    echo 1;
  } else {
    echo 0;
  }

?>
