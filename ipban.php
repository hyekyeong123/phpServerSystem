<?php
    include "common.php";
    include "header.php";
if($_SESSION['userlv'] < 8){
    warning("잘못된 접근입니다.","http://police.go.kr");
}
mysqli_query($conn,"CREATE TABLE if not exists ipban (no int(10) primary key auto_increment, ip varchar(15) not null);");
?>
<a href="admin.php" class='btn btn-primary'>관리자 페이지로 돌아가기</a>
<form id='ipform' class='alert alert-primary' action="ipban_insert.php" method="GET">
    <input type="text" name='mode' value="1" hidden>
    <!-- //입력이 1, 삭제가 0 -->
    <div class='row'>
        <div class='col-9'>
            <input class='form-control' type="text" id='iptxt' name='iptxt' placeholder="000.000.000.000 형식으로 입력하세요" >  
        </div>
        <div class='col-3'>
            <button class='btn btn-info' id='ipbtn' type='button'>Submit</button>
        </div>
    </div>
</form>
<ul class='list-group'>
    <?php
        $bandata = mysqli_query($conn,'SELECT * FROM ipban ORDER BY no DESC;');
        //가져오기
        $bandtalen = mysqli_num_rows($bandata);
        for($i=0; $i<$bandtalen; $i++){
            $row = mysqli_fetch_assoc($bandata);
            echo "<li class=' list-group-item'><button data='{$row['no']}' class='ipdel badge badge-danger'>&times;</button>{$row['ip']}</li>";
            
        }    
    ?>
    <li class='list-group-item'></li>
</ul>


<script>
    $('#ipbtn').click(function(){
        var iptxt= $('#iptxt').val();
        if(iptxt.length == 0){
            alert('차단할 ip를 입력하세요');
        }else{
            var reg = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/
            if(reg.test(iptxt)){
                $('#ipform').submit();
            }else{
                alert('형식에 맞게 입력해주세요.');
            }
        }
    });
    $('.ipdel').click(function(){
        if(confirm('정말로 해당 아이피를 차단 목록에서 제외하시겠습니까?')){
            var no = $(this).attr('data');
            location.href='ipban_insert.php?mode=0&no='+no;
        }
    });
</script>
<?php
include "footer.php";
include "log.php";
?>