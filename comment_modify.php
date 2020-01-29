<?php
//커멘트 수정 파일

include "common.php";
include "header.php";

$no = $_GET['no'];

$date = mysqli_query($conn, "SELECT * FROM comment WHERE no=$no;");
$row = mysqli_fetch_assoc($date);
if($_SESSION['id'] != $row['writerid'] && $_SESSION['userlv'] < 8 ){
    //아이디가 다를때, 관리자가 아닐때 //거짓이어야 넘어감
    warning("잘못된 접근입니다.",-1);
}
?>
<form id='modform' class='alert border-primary' action="comment_modify_insert.php" method="GET">
    <input type="text" name='comtxt' id='comtxt' class='form-control' value="<?php echo $row['cont']?>">
    <input type="text" name='no' hidden value="<?php echo $no; ?>">
    <!-- //text는 get이어도 상관없지만 no는 히든으로 -->
    <button type='button' id='combtn' class='btn btn-primary' style="cursor: pointer">Modify</button>
</form>
<script>
    $("#combtn").click(function(){
        var ttt = $("#comtxt").val().length;
        if(ttt == 0){
            alert("수정사항을 입력하세요.");
        }else{
            $("#modform").submit();
        }
    });
</script>

<?php
include "footer.php";
include "log.php";
?>