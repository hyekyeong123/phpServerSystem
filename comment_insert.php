<?php
include "common.php";


if(!isset($_SESSION['log'])){
    //로그인 한 사람만 댓글을 입력할 수 있음
    warning("잘못된 접근입니다.",-1);
}
$botable = $_GET['botable']; //테이블
$bono = $_GET['bono'];
$writerid = $_GET['writerid'];
$cont = txtini($_GET['cont']);

mysqli_query($conn,"INSERT INTO comment (cont, writerid, date, botable, bono) VALUES ('$cont','$writerid','$today','$botable',$bono);");
warning("댓글이 작성되었습니다.",-1);
include "log.php";
?>1