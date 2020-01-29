<?php
include "common.php";
if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.",'http://police.go.kr');
}
$mode = $_GET['mode'];
if($mode == 0){
    //삭제
    $no = $_GET['no'];
    mysqli_query($conn,"DELETE FROM ipban WHERE no=$no;");
    warning('해당 아이피가 차단 목록에서 삭제되었습니다.',-1);
}elseif($mode == 1){
    //삽입
    $iptxt = $_GET['iptxt'];
    mysqli_query($conn,"INSERT INTO ipban (ip) VALUES ('$iptxt');");
    warning('해당 아이피가 차단 목록에 등록되었습니다.',-1);
}
include "log.php";
?>
