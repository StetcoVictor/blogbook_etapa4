<?php

  session_start();
  $_SESSION['usermail'] = $_GET['uem'];
  header("Location: home");

?>
