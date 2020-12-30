<?php

    session_start();
    $_SESSION['usermail'] = "";
    unset($_SESSION['usermail']);
    session_unset();
    session_destroy();
    header("Location: login");

?>
