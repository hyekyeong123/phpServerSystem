<?php
include "common.php";

    // session_destroy();
    // //ip만 빼고

    unset($_SESSION['log']);
    unset($_SESSION['id']);
    unset($_SESSION['userlv']);

    echo "<script>history.go(-2);</script>";









    include "log.php";
?>