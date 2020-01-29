<!--커멘트 수정 파일 insert-->
<?php

include "common.php";

$no = $_GET['no'];
$comtxt= txtini($_GET['comtxt']);

$data = mysqli_query($conn, "SELECT * FROM comment WHERE no=$no;");
$row = mysqli_fetch_assoc($data);

if($_SESSION['id'] != $row['writerid'] && $_SESSION['userlv'] < 8 ){
    //아이디가 다를때, 관리자가 아닐때 //거짓이어야 넘어감
    warning("잘못된 접근입니다.", -1);
}
mysqli_query($conn,"UPDATE comment SET cont='$comtxt' WHERE no=$no;");
warning("댓글이 수정되었습니다.", -2);
include "log.php";
?>