<!--댓글 삭제-->
<?php
include "common.php";

$no = $_GET['no'];

$date = mysqli_query($conn, "SELECT * FROM comment WHERE no=$no;");
$row = mysqli_fetch_assoc($date);
if($_SESSION['id'] != $row['writerid'] && $_SESSION['userlv'] < 8 ){
    //아이디가 다를때, 관리자가 아닐때 //거짓이어야 넘어감
    warning("잘못된 접근입니다.",-1);
}
mysqli_query($conn, "DELETE FROM comment WHERE no=$no;");
warning("댓글이 삭제되었습니다.",-1);
include "log.php";
?>