<?php
    include "common.php";
    include "header.php";
    
    if($_SESSION['userlv'] < 8){
        warning("잘못된 접근입니다.",-1);
    }

    //ip주소로 정렬하기---------------------------------------------------------------------------------
    $filter = $_GET['filter'];
    if(!empty($filter)){
        $filter = "where ip='$filter'";
        $back = "<a class='btn btn-primary' href='loglist.php'>back to the list</a>";
                        //리스트 목록 보기
    }


    $data = mysqli_query($conn,"SELECT * FROM log $filter ORDER BY no ASC;");
    $len = mysqli_num_rows($data);
?>
    <a href="admin.php" class='btn btn-primary'>관리자 페이지로 돌아가기</a>
    <h3>Log report</h3>
    <?php echo $back ?>
    <table class="table">
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>IP</th>
            <th>ID</th>
            <th>Issues</th> 
        </tr>
        <?php
            for($i=0; $i<$len; $i++){
                $row = mysqli_fetch_assoc($data); //반드시 반복문 안에서 포장 풀기
                echo "<tr>";
                echo "<td>{$row['date']}</td>";
                echo "<td>{$row['time']}</td>";
                echo "<td><a href='loglist.php?filter={$row['ip']}'> {$row['ip']} </a></td>"; //자기자신을 새로 고침 필터기능 사용하기 위해
                echo "<td>{$row['id']}</td>";
                $action = urldecode($row['action']);
                echo "<td>$action</td>";
                echo "</tr>";
            }
        ?>
    </table>
    <div style="width: 40px; height:80px; position:fixed; right:20px; bottom:60px;
    z-index:1000;">
        <a href="#" style="width:40px; height:40px; text-align:center" class='btn btn-primary'>▲</a>
        <a href="#end" style="width:40px; height:40px; text-align:center" class='btn btn-primary'>▼</a>
    </div>
    <div id='end'></div>
    <?php echo $back ?>
<?php
include "footer.php";
?>