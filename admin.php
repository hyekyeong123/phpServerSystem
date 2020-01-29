<!--관리자 페이지-->
<?php
    include "common.php";
    include "header.php";

    if($_SESSION['userlv'] < 8){
        warning('잘못된 접근입니다.','index.php');
    }
?>

<h2>Administrator Page</h2>
<hr>
<div class='btn-group'>
    <a href="memberlist.php" class='btn btn-primary'>회원관리</a>
    <a href="ipban.php" class='btn btn-warning'>아이피 차단</a>
    <a href="loglist.php" class='btn btn-info'>로그 관리</a>
</div>

<div class='row'>
    <div class='col-6'>
        <!-- //이번주 가입자 수 / 누적 가입자 수 -->
        <!-- 오늘 7일전 -->
        <!-- 1. 오늘날짜 7일전이 언제인가? ->
        2. member table에서 date가 그 날짜보다 큰 데이터들의 수 ->이번주 가입자(date > 그 날짜) -->

        <?php
        $old = date('Y-m-d',strtotime("-7 days"));
        //오늘 날짜 7일전               
        $weekmem = mysqli_query($conn,"SELECT no FROM member WHERE date>'$old';");
            //반드시 ''로 감싸줌
        $weekmem = mysqli_num_rows($weekmem);
        //7일전보다 최근 게시물을 모두 가져옴
        $totalmem = mysqli_query($conn,"SELECT no FROM member;");
        $totalmem = mysqli_num_rows($totalmem);

        echo "이번주 가입자 수 {$weekmem} / 누적 가입자 수{$totalmem}";
        ?>
    </div>
</div>


<div class='row'>
    <div class='col-6'>
        <h3>notice</h3>
        <ul class='list-group'>
            <?php
                $data1 = mysqli_query($conn,'SELECT * FROM notice ORDER BY no DESC LIMIT 5;');
                for($i=0;$i<5;$i++){
                    $row1 = mysqli_fetch_assoc($data1);
                    echo "<li class='list-group-item'><a href='view.php?botable=notice&no={$row1['no']}'>{$row1['title']}</a></li>";
                }
            ?>
        </ul>
    </div>
    <div class='col-6'>
        <h3>guest</h3>
        <ul class='list-group'>
            <?php
            $data2 = mysqli_query($conn,'SELECT * FROM guest ORDER BY no DESC LIMIT 5;');
            for($j=0;$j<5;$j++){
                $row2 = mysqli_fetch_assoc($data2);
                echo "<li class='list-group-item'><a href='view.php?botable=guest&no={$row2['no']}'>{$row2['title']}</a></li>";
            }
        ?>
        </ul>

    </div>
</div>


<?php
    include "footer.php";
    include "log.php";
?>