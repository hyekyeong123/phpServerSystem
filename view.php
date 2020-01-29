<?php
    include "common.php";
    include "header.php";

    $botable = $_GET['botable'];
    $no = $_GET['no'];
    if($botable=='' || $no==''){

        //그럼 혹시 post로 보냈나?
        if($_POST['botable'] != ""){
            $botable = $_POST['botable'];
            $no = $_POST['no'];
            $pass = $_POST['pass'];
        }else{       
            warning("잘못된 접근입니다.",-1);
            exit;
        }
    }
    if($_SESSION['userlv'] < ${$botable."_view"}){
        warning("글을 볼 수 있는 권한이 없습니다.",-1);
        exit;
    }
    $data = mysqli_query($conn,"SELECT * FROM $botable WHERE no=$no;");
    $row = mysqli_fetch_assoc($data);

//비밀글 돌려 보내기------------------------------------------------------------------------------------------------------
    //1. 로그인 사용자가 쓴 글을 관리자가 볼때
    //2. 로그인 사용자가 쓴 글을 글쓴이 본인이 볼때
    //3. 로그인 사용자가 쓴 글을 타인이 볼때
    //4. 비 로그인 사용자가 쓴 글 -> 관리자
    //5. 비 로그인 사용자가 쓴 글 -> 글쓴이 본인
    //6. 비 로그인 사용자가 쓴 글 -> 타인
    if($row['sec'] == 1){
        //비밀글이라면
        if($row['writerid'] != ""){
            //작성자아이디가 비어있지 않다면 -> 로그인 한 사람
            if($_SESSION['userlv'] < 8 && $_SESSION['id'] != $row['writerid']){
                //레벨이 8보다 작고 세션 아이디가 있을때// 그리고 비로그인 자라면
                //막아야함
                warning("이 글을 읽을 권한이 없습니다.",-1);
                exit;
            }
        }else{
            if($_SESSION['userlv'] < 8 ){
                //만약 관리자가 아니라면
                if(!password_verify($pass,$row['pass'])){
                    //비밀번호를 틀리면
                    warning("비밀번호가 일치하지 않습니다.",-1);
                    exit;
                }
                // php가 자바스크립트로 데이터 보내는 것은 가능하지만 그 반대의 경우는 안됨
            }
        }
    }
//게시물 조회수 카운트---------------------------------------------------------------------------------------------------------------
//  "아이피 주소.테이블 이름.게시물 번호" 세션이 있는가?
//  (아이피 주소.테이블 이름.게시물 번호)로 세션을 만든다.
//  count를 1 증가시킴
    $session = $_SESSION['ip'].$botable.$row['no'];//ip주소 + 게시판 이름 + 번호
    if(!isset($_SESSION[$session])){
        //없다면
        $_SESSION[$session]=0;
    mysqli_query($conn,"UPDATE $botable SET count=count+1 WHERE no={$row['no']}");
    }
?>
<link rel="stylesheet" href="css/board.css">
<!---------------------------------------------------------------------------------------------------------------------------->
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-8">글쓴이 : 
                <?php
                    $name = id2name($row['writerid']); 
                    if($name != false){
                        //로그인한 사람이라면
                        echo $name;
                    }else{
                        echo $row['writername']; 
                    }
                ?>
            </div>
            <div class="col-4 text-right">
                <?php echo $row['date']; ?>
                &nbsp;&nbsp;&nbsp;
                <img src="images/eye.png" alt="조회수" style="width:20px; height:20px">
                <?php echo $row['count'];?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h3><?php echo $row['title']; ?></h3>
        <?php
            if($row['file'] != ""){
                //파일이 있다면
                $rename = explode("/",$row['file']);
                $rename = $rename[1]; //보여줄 파일명
                echo "<hr>";
                echo "첨부파일 : <a href='/login/upload/{$row['file']}'>".$rename."</a>";
            }
        ?>
        <hr>
        <div>
            <?php
                $cont = nl2br($row['content']);
                echo $cont;
// 질문하기????????????????????????????????????????????????????????
                if($row['file'] != ""){
                    //첨부파일이 있다면
                    $ext = explode(".",$row['file']); 
                    $ext = $ext[1]; //뒤에 확장명만 가져와서?
                    $picture = ["jpg","jpeg","png","gif"];
                    $pic = false; //그림이 아님
                    for($i=0;$i<count($picture); $i++){
                        if($ext == $picture[$i]){
                            $pic=true;
                        }
                    }
                    if($pic){
                        echo "<img src='upload/{$row['file']}' alt='첨부그림' style='display:block; max-width:100%;'/>";
                    }
                }
            ?>
        </div>
        <!--------------------------------------------------------------------------------------------------------------------------->
        <?php 
            $prevpost = mysqli_query($conn,"SELECT * FROM $botable WHERE no<{$row['no']} ORDER BY no DESC LIMIT 1");
            $prevrow = mysqli_fetch_assoc($prevpost);
            
            $prevsec="";
            if($prevrow['sec'] == 1 && $prevrow['writerid'] == ""){
                //비회원이 쓴 비밀글이라면
                $prevsec="sec";
            }


            $nextpost = mysqli_query($conn,"SELECT * FROM $botable WHERE no>{$row['no']} ORDER BY no ASC LIMIT 1");
            $nextrow = mysqli_fetch_assoc($nextpost); //번호 하나씩 가져온 것
            
            $nextsec="";
            if($nextrow['sec'] == 1 && $nextrow['writerid'] == ""){
                //비회원이 쓴 비밀글이라면
                $nextsec="sec";
            }
            
            echo "<div class='row'>";
                if($prevrow['no'] != ""){
                    //이전 글이 있음
                    echo "<div class='col-sm-6'>";
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>{$prevrow['title']}</h5>";
                    echo "<a href='view.php?botable=$botable&no={$prevrow['no']}' class='btn btn-primary $prevsec'>prev post</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }else{
                    echo "<div class='col-sm-6'>";
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>&nbsp;</h5>";

                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                
                if($nextrow['no'] != ""){
                    echo "<div class='col-sm-6'>";
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>{$nextrow['title']}</h5>";
                    echo "<a href='view.php?botable=$botable&no={$nextrow['no']}' class='btn btn-primary $nextsec'>next post</a>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                else{
                    echo "<div class='col-sm-6'>";
                    echo "<div class='card'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>&nbsp;</h5>";

                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            ?>
        <!--------------------------------------------------------------------------------------------------------------------------->
    </div>
</div>
<!--카드(view) 끝나는 곳-->
<form id="secpass" action="view.php" method="post">
    <input type="text" name="botable" value="<?php echo $botable; ?>" hidden />
    <input id="no" type="text" name="no" hidden />
    <input id="pass" type="text" name="pass" hidden />
</form>
<!-- 비밀번호를 받아오기 위한 post형식의 form--------------------------------------->
<script>
$(".sec").click(function(e) {
    if ( <?php echo $_SESSION['userlv']; ?> < 8) {
        e.preventDefault();
        var pass = prompt("비밀번호를 입력해주세요.");
        if (pass != "" && pass != null) {
            //공백도 아니고 널값도 아니어야만 함
            var url = $(this).attr("href");
            //var "view".php?botabl=notice&no=321"
            url = url.split("no=");
            ur1 = url[1];
            url = parseInt(url);
            $("#no").val(url);
            $("#pass").val(pass);
            //비밀번호가 있으면
            $("#secpass").submit();
        } else {
            alert("비밀번호를 입력해주세요");
        }
    }
});
</script>
<!------------댓글보기------------------------------------------------------------------------------------------------------------>
<?php
    $comment = mysqli_query($conn,"SELECT * from comment WHERE botable = '$botable' and bono=$no ORDER BY date ASC;");
                                                                    //테이블과 넘버 가져오기
    $comlen = mysqli_num_rows($comment);
    if($comlen != 0){
        echo "<div id='comment' class='alert alert-info'>";
        echo "<ul >";
        for($j=0;$j<$comlen; $j++){
            $comrow = mysqli_fetch_assoc($comment);
            $findname = id2name($comrow['writerid']); // 유저아이디와 같은 유저 네임(회원만)
            //수정,삭제--------------------------
            $combtn = "";
            if($_SESSION['id'] == $comrow['writerid'] || $_SESSION['userlv'] >= 8){
                $combtn = "<a href='comment_modify.php?no={$comrow['no']}' id='commod' class='badge badge-info'>Modify</a><a href='comment_delete.php?no={$comrow['no']}' id='comdel' class='badge badge-info'>Delete</a>";
            }
            echo "<li>";  
            echo "<span class='comleft'>{$comrow['cont']}</span>";
                                        //내용 / 수정 삭제 버튼
            echo "<span class='comright'><span class='badge badge-light'><br>$combtn {$comrow['date']}  </span> <span class='badge badge-info'>$findname</span></span>";
                                    //날짜 / 글쓴이
            echo "</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
?>
<!--댓글 쓰기--------------------------------------------------------------------------------------------------->
<?php
if($_SESSION['userlv'] > 0){
echo "<form id='comform' action='comment_insert.php' method='get'>";
    echo "<input type='text' name='botable' value='$botable' hidden>";
    echo "<input type='text' name='bono' value='$no' hidden>";
    echo "<input type='text' name='writerid' value='{$_SESSION['id']}' hidden>";
    echo "<div id='comment_write' class='alert alert-primary'>";
        echo "<div class='row'>";
            echo "<div class='col-9'>";
                echo "<input type='text' id='cominput' name='cont' class='form-control'>";
            echo "</div>";
            echo "<div class='col-3'>";
                echo "<button id='combtn' class='btn btn-primary btn-block' type='button'>Comment</button>";
            echo "</div>";
        echo "</div>";
    echo "</div>";
echo "</form>";
}
?>
<script>
    $("#combtn").click(function(){
        var ttt = $("#cominput").val().length;
        if(ttt == 0){
            alert("댓글을 입력하세요.");
        }else{
            $("#comform").submit();
        }
    });

</script>





<!-----------리스트버튼------------------------------------------------------------------------------------------->
<div class="row">
    <div class="col-6">
        <?php
        //지금 보고 있는 이 게시물이 몇번째 게시물인가?
            $nth = mysqli_query($conn,"SELECT no FROM $botable WHERE no >{$row['no']};");
            $nth = mysqli_num_rows($nth);
            $newpage="list.php?botable=$botable&page=".floor($nth / $postlen);
        ?>
        <a href="<?php echo $newpage; ?>" class="btn btn-info">List</a>
    </div>
    <div class="col-6 text-right">
        <?php
            //관리자이거나 글쓴이 인경우에만 permit이 true가 됨
            if($_SESSION['userlv'] >= 8 || $_SESSION['id']== $row['writerid']){
                echo "<a id='modbtn' href='modify.php?botable=$botable&no={$row['no']}' class='btn btn-info'>Modify</a>";
                echo "<a id='delbtn' href='delete.php?botable=$botable&no={$row['no']}' class='btn btn-info'>Delete</a>";
            }
        ?>
        <!-- //삭제시         -->
        <form id="delform" action="delete.php" method="POST">
            <input type="text" id="delbotable" name="delbotable" value="<?php echo $botable; ?>" type="text" hidden>
            <input type="text" id="delno" name="delno" value="<?php echo $no; ?>" type="text" hidden>
            <input type="password" id="delpass" name="delpass" value="" hidden>
        </form>
        <script>
        // 
        $("#delbtn, #modbtn").click(function(e){
            e.preventDefault();

            var who;
            var txt;

            if ($(this).attr("id") == "delbtn") {
                who = "delete";
                txt = "삭제";
            } else {
                who = "modify";
                txt = "수정";
            }
            $("#delform").attr("action", who + ".php");

            if (confirm("정말로 게시물을 " + txt + "하시겠습니까?")) {
                if ( <?php echo $_SESSION['userlv']; ?> < 8) {
                    if ("<?php echo $row['writerid']; ?>" == "") {
                        //따옴표 주의 //아이디가 없다면
                        var delpass = prompt("비밀번호를 입력해주세요.");
                        if (delpass == null || delpass == "") {
                            alert("비밀번호를 확인해주세요.");
                        } else {
                            $("#delpass").val(delpass);
                            $("#delform").submit();
                        }
                    } else {
                        location.href = $(this).attr("href");
                    }
                } else {
                    location.href = $(this).attr("href"); //관리자는 바로 이동
                }
            };
        });
        </script>
    </div>
</div>
<?php
    include "footer.php";
    include "log.php";
?>