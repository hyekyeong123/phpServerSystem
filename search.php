<?php
include "common.php";
include "header.php";

$botable = $_GET['botable'];
$searchtxt = txtini($_GET['searchtxt']);
$searchtype = $_GET['searchtype'];
//searchtype=0 -> 제목, 내용
//searchtype=1 -> 작성자


if ($botable == "" || $searchtxt == "" || $searchtype == "") {
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
//인기 검색어 추가---------------------------------------------------------------------------------------------------------
//본 검색어가 처음 등장이면 하나 새로 만들어주고 검색어가 이미 있으면 count를 1 증가시킴
$keyword = mysqli_query($conn, "SELECT no FROM top WHERE keyword='$searchtxt';");

$keylen = mysqli_num_rows($keyword);
if($keylen != 0){
    //기존 키워트 -> count 증가
    mysqli_query($conn,"UPDATE top SET count=count+1 WHERE keyword='$searchtxt'");
}else{
    //새 키워드 하나 새로 만들어주고 count 0
    mysqli_query($conn,"INSERT INTO top (keyword,count) VALUES ('$searchtxt',1)");
}


//2. 전체 검색결과의 개시물 수 세기------------------------------------------------------------------------------------------
if($searchtype == 0){
    // 제목과 내용으로 검색
    $data = mysqli_query($conn,"SELECT no FROM $botable WHERE title LIKE '%$searchtxt%' OR content LIKE '%$searchtxt%';");
}else if($searchtype == 1){
    // 아이디와 글쓴이 이름으로 검색
    $data = mysqli_query($conn,"SELECT no FROM $botable WHERE writerid LIKE '%$searchtxt%' OR writername LIKE '%$searchtxt%';");
                                                                         //사용자가 입력한 값
}
$len =mysqli_num_rows($data);
        //$data의 길이

//3. 전체 페이지 수 세기
$pagelen = ceil($len / $postlen);
                        //한 페이지당 보여줄 포스트의 갯수

//4. 전체 블록 수 세기
$blocklen = ceil($pagelen / $pbtnlen);
                            //한 페이지당 보여줄 버튼의 갯수

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

<!--search box -->
<form id="search" action="search.php" method="GET" class="input-group" style="max-width:400px; float:right; margin-bottom:30px;">
    <input type="text" name="botable" value="<?php echo $botable; ?>" hidden>
    <!--  -->
    <div class="input-group-prepend">
        <select name="searchtype" id="searchtype" class="form-control">
            <?php 
                if($searchtype == 0){
                    $type1 ="selected";
                    $type2 ="";
                }else if($searchtype == 1){
                    $type1 ="";
                    $type2 ="selected";
                }
            ?>
            <option value="0">제목 + 내용</option>
            <option value="1">작성자</option>
        </select>
    </div>
    <input id="searchtxt" list="top5" type="text" class="form-control" name="searchtxt" placeholder="검색어를 입력하세요." value="<?php echo $searchtxt; ?>">
    <datalist id="top5">
        <?php 
        $top5 = mysqli_query($conn,"SELECT keyword FROM top ORDER BY count DESC LIMIT 5");

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
        }else if(slen <2){
            alert("검색어는 두 글자 이상입니다.");
        }else{
            $("#search").submit();
        }
    });
//----------------------------------------- 
    $("#searchtxt").keydown(function(e){
        var code = e.keyCode;
        if(code == 13){
            $("#searchbtn").trigger("click");
        }
    });
</script>
<!----------------------------------------------------------->
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
            //뽑아오고 정렬하기
            if($searchtype == 0){
                $data2 = mysqli_query($conn,"SELECT * from $botable WHERE title LIKE '%$searchtxt%' OR content LIKE '%$searchtxt%' ORDER BY no DESC LIMIT $firstpostno, $postlen;");
            }else if($searchtype == 1){
                $data2 = mysqli_query($conn,"SELECT * from $botable WHERE title LIKE '%$searchtxt%' OR content LIKE '%$searchtxt%' ORDER BY no DESC LIMIT $firstpostno, $postlen;");
            }
            for($i=0;$i<$postlen;$i++){
                $row = mysqli_fetch_assoc($data2);
                if($row['no'] != ""){

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
                                        //비회원이 쓴 비밀글이라면 sec라는 클래스를 준다.
                    echo "<td><a class='$sec' href='view.php?botable=$botable&no={$row['no']}'>$lock {$row['title']} $clip</a></td>";
                    //ex -> view.php?botable=notice $ no=52     //title왼쪽에 자물쇠 오른쪽에 클립
                    $name= id2name($row['writerid']);
                    if($name != false){
                        //회원이라면
                        echo "<td>$name</td>";
                    }
                    echo "<td>{$row['writername']}</td>";
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
    $prevurl = "search.php?botable=$botable&searchtxt=$searchtxt&searchtype=$searchtype&page=$prevurl";
// 
    if($blockno  >= $blocklen-1){
        //현재 보고있는 블록번호에 있는지
        $nextdisabled = "disabled";
    }else{
        $nextdisabled="";
    }
    $nexturl = $firstbtnno+$pbtnlen;
                    // 이전 블록의 마지막 번호
    $nexturl = "search.php?botable=$botable&searchtxt=$searchtxt&searchtype=$searchtype&page=$nexturl";
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
                $bno=$pno+1;

                if($bno <= $pagelen){
                    $active = "";
                    if($pno == $page){
                        $active = "active";
                    }
                    echo "<li class='page-item $active'><a class='page-link' href='search.php?botable=$botable&searchtxt=$searchtxt&searchtype=$searchtype&page=$nexturl'>$bno</a></li>";
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
<?php
    include "footer.php";
    include "log.php";
?>