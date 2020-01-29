<?php
include "common.php";
include "header.php";

$botable = $_GET['botable'];
if ($botable == "") {
    //비어있으면 잘못 접근 한거니까
    echo "<script>";
    echo "alert('잘못된 접근입니다.');";
    echo "history.back();";
    echo "</script>";
}
//0. 글쓰기 권한이 있는지 확인
if ($_SESSION['userlv'] < ${$botable."_list"}) {
    //만약에 유저의 레벨이 $notice_list(ex)5)보다 크거나 같으면 권한이 있는 것
    echo "<script>";
    echo "alert('잘못된 접근입니다.');";
    echo "history.back();";
    echo "</script>";
    exit;
}
//1. 현재 페이지 번호를 알아낸 후
$page = $_GET['page'];
if($page == ""){
    $page=0;
}

//2. 전체 개시물 수 세기
$data = mysqli_query($conn,"SELECT no FROM $botable;");
$len =mysqli_num_rows($data);
        //$data의 길이
if($len == 0){
    warning("검색 결과가 없습니다.",-1);
}


//3. 전체 페이지 수 세기
$pagelen = ceil($len / $postlen);
                        //한 페이지당 보여줄 포스트이 갯수 갯수

//4. 전체 블록 수 세기
$blocklen = ceil($pagelen / $pbtnlen);
                            //한 페이지당 보여줄 버튼의 갯수
                            // == 한 블록당 보여줄 페이지 수

//5. 현재 페이지에서 보여줄 첫번째 게시물의 번호
$firstpostno = $page*$postlen;
                //현재 보고 있는 페이지 번호

//현재 보고 있는 페이지는 몇번째 블록에 있는가?
$blockno =floor($page/$pbtnlen);

//현재 보고 있는 블록의 첫번째 버튼 번호는?
$firstbtnno = $blockno*$pbtnlen;
?>
<!------------------------------------------------------------------------>
<link href="css/board.css" rel="stylesheet">
<div style="float:left">
    <?php
        $aaa = $page+1;
        echo "총 $len 건 | 현재 페이지 $aaa/$pagelen";
    ?>
</div>
<!--search box--------------------------------------------------------------------------------------------------------------------------------->
<form id="search" action="search.php" method="GET" class="input-group" style="max-width:400px; float:right; margin-bottom:30px;">
    <input type="text" name="botable" value="<?php echo $botable ?>" hidden>
    <div class="input-group-prepend">
        <select name="searchtype" id="searchtype" class="form-control">
            <option value="0">제목 + 내용</option>
            <option value="1">작성자</option>
        </select>
    </div>
    <input type="text" class="form-control" list="top5" name="searchtxt" id="searchtxt"  placeholder="검색어를 입력하세요.">
    <datalist id="top5">
        <?php 
        $top5 = mysqli_query($conn,"SELECT keyword FROM top ORDER BY count  DESC LIMIT 5");

            for($j=0; $j<5; $j++){
                $know = mysqli_fetch_assoc($top5);
                echo "<option value='{$know['keyword']}'>";
            }
        ?>
    </datalist>

    <div class="input-group-append">
        <button id="searchbtn" class="btn btn-outline-secondary" type="button">Search</button>
    </div>
</form>
<script>
    $("#searchbtn").click(function(){
        var slen = $("#searchtxt").val().length;
        if(slen == 0){
            alert("검색어를 입력하세요.");
        }else if(slen < 2){
            alert("검색어는 두 글자 이상입니다.");
        }else{
            $("#search").submit();
        }
    });
//------------------------------------
    $("#searchtxt").keydown(function(e){
        var code = e.keyCode;
        if(code == 13){
            $("#searchbtn").trigger("click");
        }
    });
</script>

<!---------------------------------------------------------------------------------------------------------------------------------------------->
<table class="table" style="clear:both">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Writer</th>
            <th>Date</th>
            <th>Count</th>
        </tr>
    </thead>
    
    <tbody>
        <?php
            //데이터 뽑아오기
            $data2 = mysqli_query($conn,"SELECT * from $botable ORDER BY no DESC LIMIT $firstpostno, $postlen;");
            for($i=0;$i<$postlen;$i++){
                $row = mysqli_fetch_assoc($data2);
                if($row['no']!=""){

                    echo "<tr>";
                    echo "<td>{$row['no']}</td>";
                    
//비밀글 여부 체크----------------------------------------------------------------------------------------
                    $sec ="";
                    if($row['sec'] == 1){
                        $lock = "<img src='images/lock.png' alt='lock' style='width:20px; height:20px; margin-right:15px;'/>"; 
                        if($row['writerid'] == ""){
                            $sec = "sec";
                            // 비밀글이라면 sec라는 글자를 할당해줌
                        }
                    
                    }else{
                        $lock="";
                    }
//첨부파일 존재 여부 체크------------------------------------------------------------------------------------
                    if($row['file'] != ""){
                        $clip = "<img src='images/clip.png' alt='lock'  style='width:20px; height:20px; margin-left:15px;'/>";

                    }else{
                        $clip="";
                    }
//-----------------몇개의 댓글이 있는지 보여주기 
                    $comment = mysqli_query($conn,"SELECT no FROM comment WHERE botable='$botable' AND bono={$row['no']};");
                    $comlen = mysqli_num_rows($comment);
                    if($comlen == 0){
                        //댓글이 없다면
                        $com = "";
                    }else{
                        $com="<span class='badge badge-light'>($comlen)</span>";
                    }  
//최근게시물에 new 스티커 붙이기---------------------------------------------------------------------                         
                    $exp =date("Y-m-d",strtotime($row['date']."+1 days"));
                                                //문자열로 바꿔서 1을 더한다음에 다시 데이트 형식으로
                    if($today <= $exp){
                        // 만 24시 안이라면
                        $new = "<span class='badge badge-success'>NEW</span>";
                    }else{
                        $new='';
                    }
//조회수 5이상 게시물에 hot 스티커 붙이기
                    if($row['count'] >= 1){
                        $hot = "<span class='badge badge-danger'>hot</span>";
                    }else{
                        $hot = '';
                    }

//비회원이 쓴 비밀글이라면 sec라는 클래스를 준다.
                    echo "<td><a class='$sec' href='view.php?botable=$botable&no={$row['no']}'>$lock {$row['title']}$com $clip $new $hot</a></td>";
                    //ex -> view.php?botable=notice $ no=52     //title왼쪽에 자물쇠 오른쪽에 클립
                    $name = id2name($row['writerid']);
                    if($name != ''){
                        echo "<td>$name</td>";
                    }else{
                        echo "<td>{$row['writername']}</td>";
                    }
                    echo "<td>{$row['date']}</td>";
                    echo "<td>{$row['count']}</td>";
                    echo "</tr>";
                }
            }
        ?>        
    </tbody>
</table>
<!--view에서 확인------------------------------------------------------------------------->
<form id="secpass" action="view.php" method="post">
    <input type="text" name="botable" value="<?php echo $botable; ?>" hidden/>
    <input id="no" type="text" name="no" hidden/>
    <input id="pass" type="password" name="pass" hidden/>
</form>

<!-- 비밀번호를 받아오기 위한 post형식의 form--------------------------------------->
<script>
    $(".sec").click(function(e){
        if(<?php echo $_SESSION['userlv'];?> < 8){
            //관리자가 아니면
            e.preventDefault();
            var pass = prompt("비밀번호를 입력해주세요.");
            if(pass != "" && pass != null){
                //공백도 아니고 널값도 아니어야만 함
                var url = $(this).attr("href");
                //var "view.php?botabl=notice&no= 321"
                url = url.split("no=");
                url = url[1];
                //-> 321
                url = parseInt(url);
                $("#no").val(url);
                $("#pass").val(pass);
                //비밀번호가 있으면
                $("#secpass").submit();
            }else{
                alert("비밀번호를 입력해주세요");
            }
        }
    });
</script>
<!--------------------------------------------------------------------------------------------------------------------->
<?php
    //1. 이전 블록이 존재하는가 존재하지 않는다면 저 li에게 disabled라는 클래스를 추가
    //존재한다면 저 li안에 있는 a의 href값
    // $firstbtnno-2가 이전블록의 마지막 번호 왜냐하면 번호는 1부터 시작하니까 -1이 더 붙음
    // href='list.php?botable=$botable&page=$prevurl'
    if($blockno <= 0){
        //현재 보고있는 블록번호에 있는지
        $prevdisabled = "disabled";
    }else{
        $prevdisabled="";
    }
    $prevurl = $firstbtnno -1;
                    // 이전 블록의 마지막 번호
    $prevurl = "list.php?botable=$botable&page=$prevurl";
// 
    if($blockno  >= $blocklen-1){
        //현재 보고있는 블록번호에 있는지
        $nextdisabled = "disabled";
    }else{
        $nextdisabled="";
    }
    $nexturl = $firstbtnno+$pbtnlen;
                    // 이전 블록의 마지막 번호
    $nexturl = "list.php?botable=$botable&page=$nexturl";
?>
<!---------------------------------------------------------------------------------------------->
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">

        <li class="page-item <?php echo $prevdisabled; ?>">
            <a class="page-link" href="<?php echo $prevurl; ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php
            for($j=0;$j<$pbtnlen; $j++){
                $pno = $firstbtnno+$j;
                        //첫번째 번호+ ()
                
                $bno=$pno+1; //버튼은 1부터 시작하니까

                if($bno <= $pagelen){
                    $active = "";
                    if($pno == $page){
                        $active = "active";
                    }
                    echo "<li class='page-item $active'><a class='page-link' href='list.php?botable=$botable&page=$pno'>$bno</a></li>";
                    // 첫번째에서 1을빼줘야 진짜 버튼 넘버 //그 후 $j를 더해줘야 함(0,1,2,3,4)
                }
            }
        ?>
<!----------------------------------------------------------------------------------------------------------------------------->
        <li class="page-item <?php echo $nextdisabled; ?>">
            <a class="page-link" href="<?php echo $nexturl; ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>
<!--------------------------------------------------------------------------------------------------------------------->
<div class="row text-right">
    <div class="col-12">
        <?php
                    //5
            if($_SESSION['userlv'] >= ${$botable."_write"}){
                echo "<a href='write.php?botable=$botable' class='btn btn-info'>Writer</a>";
            }
        ?>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------------------->
<?php
    include "footer.php";
    include "log.php";
?>

 <!-- 
    
  -->