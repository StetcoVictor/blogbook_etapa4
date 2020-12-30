<?php


  include "config.php";
  include "functions.php";

  $uem = $_POST['uem'];

  if(sizeof(getItemWhere("users", "username", $uem)) > 0) {
    echo 1;
  } else {
    echo 2;
  }

?>
