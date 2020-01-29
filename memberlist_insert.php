<?php
    include "common.php";
    if($_SESSION['userlv'] < 8){
        warning("잘못된 접근입니다.","index.php");
    }
    $memno = $_POST['memno'];
    $memlv = $_POST['memlv'];

    mysqli_query($conn,"UPDATE member SET userlv=$memlv WHERE no=$memno;");
    warning("회원정보가 수정되었습니다.",-1);
    include "log.php";
?>