<?php
    include "common.php";
    include "header.php";
//권한확인--------------------------------------------------------------------------------------------------
    //botable /no를 알아내야 하는데 아직 그것이 get으로 올지 post로 올지 모름
    // 만약 post로 온다면 pass도 같이 옴
    // post로 pass값도 같이 왔다면 그건 비회원이 쓴 글이므로 비밀번호 비교할 각오 해야함

    $botable = $_GET['botable'];
    $no = $_GET['no']; //몇번 게시물을 지울건데?

    if($no == ""){
        //get으로부터 가져온 것
        $botable = $_POST['delbotable'];
        $no = $_POST['delno'];
        $pass = $_POST['delpass'];
        if($no == ""){
            //post로부터 가져온 것
            warning("잘못된 접근입니다.",-1);
        }
    }
// ?????????????????????????????????????????????????뭐더라?



    $data = mysqli_query($conn,"SELECT * FROM $botable where no=$no;");
    $row = mysqli_fetch_assoc($data);

    if($_SESSION['userlv'] <8){
        //관리자가 아니라면
        //1. 아이디 대조
        //2. 비밀번호 대조
        if($row['pass'] == ""){
            //비밀번호가 없으면 //회원이라면
            //아이디 대조(회원이 쓴 글을 삭제할 때)
            if($_SESSION['id'] != $row['writerid']){
                warning("글을 수정할 권한이 없습니다.",-1);
            }
        }else{
            //비밀번호 대조(비회원이 쓴 글을 삭제할때)
            if(!password_verify($pass, $row['pass'])){
                warning("글을 수정할 권한이 없습니다.",-1);
            }
        }   
    }
?>
<script type="text/javascript" src="se/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<!-- //게시물 수정하기------------------------------------------------------------------------------------------------------>
<link href="css/board.css" rel="stylesheet">
<form id="modifyform" action="modify_insert.php" method="post" enctype="multipart/form-data">
    <!-- //파일첨부할때 -->
    <input type="text" name="botable" value="<?php echo $botable; ?>" hidden />
    <!-- //write_insert.php는 post방식이기때문에 주소를 모름 따라서 정보를 넣어줌-->
    
    <input type="text" name="no" value="<?php echo $no; ?>" hidden />
    <!-- //기존 게시물을 수정하는 것이기 때문에 no를 알고 있어야 함 -->
    
    <div class="alert border-primary">
        <div class="row">
            <div class="col-3">제목</div>
            <div class="col-9">
                <input name="title" id="title" type="text" class="form-control" value="<?php echo $row['title']; ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-3">작성자</div>
            <div class="col-9">
                <!------------------------------------------------------------------------------------------------------------------------------->
                <!-- //아이디는 고정 -->
                <input id="writername" name="writername" type="text" value="<?php echo $row['writername']; ?>" <?php echo $readonly; ?> class="form-control" />
            </div>
        </div>
<!--------------------------------------------------------------------------------------------->
        <!-- //비밀번호 바꿀 필요 없음 -->
        <!---------------------------------------------------------------------------------------------------->
        <div class="row">
            <div class="col-3">비밀글</div>
            <div class="col-9">
                <?php
                    if($row['sec'] == 1){
                        //비밀글이라면
                        $sec = "checked";
                    }else{
                        $sec="";
                    }
                ?>
                <input <?php echo $sec; ?> name="sec" type="checkbox" value="on" class="form-control" />
                <!-- //on이면 비공개로 아니면 공개 -->
            </div>
        </div>
<!--  -->
        <div class="row">
            <div class="col-3">내용</div>
            <div class="col-9">
                <textarea id="content" class="form-control" name="content" style="min-width:260px";><?php echo $row['content']; ?></textarea>
                <script type="text/javascript">
                var oEditors = [];
                nhn.husky.EZCreator.createInIFrame({
                    oAppRef: oEditors,
                    elPlaceHolder: "content",
                    sSkinURI: "se/SmartEditor2Skin.html",
                    fCreator: "createSEditor2"
                });
                </script>
            </div>
        </div>
<!-- 
    //1. 없음 -> 새로 업로드
    //2.                        (첨부파일 없음 -> 없음)
    //3. 있음 -> 다른 것 업로드
    //4.                        (있음 -> 그대로)
    //5. 있음 -> 지울거임
 -->

        <div class="row">
            <div class="col-3">첨부파일</div>
            <div class="col-9">
                <input name="file" type="file" class="form-control">
                
                <?php
                    if($row['file'] != ""){
                        //기존첨부파일이 있다면
                        $oldfile = $row['file'];
                        $oldfilecut = explode("/",$row['file']);
                        //폴더와 파일을 분리
                        $oldfilecut = $oldfilecut[1];
                        echo "<span id='badge' class='badge badge-primary'>$oldfilecut <span id='filedelbtn' class='badge badge-light' style='cursur-pointer';>&times;</span></span>";
                    }
                ?>
                <input type="text" id="filedel" name="filedel" value="0" hidden>
                <script>
                    $("#filedelbtn").click(function(){
                        $("#filedel").attr("value","1");
                        $("#badge").text("해당 파일이 지워집니다.");
                    });
                </script>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <button id="write" type="button" class="btn btn-info">수정하기</button>
                <button type="reset" class="btn btn-secondary">초기화</button>
            </div>
        </div>

    </div>


</form>
<script>
    $("#write").click(function() {
        oEditors.getById["content"].exec("UPDATE_CONTENTS_FIELD", []);

        //#title, #writername, $pass, $content 내용이 없으면 보내지 않음
        var title = $("#title").val().length;

        var content = $("#content").val().length;

        if (title * content == 0) {
            alert("필수항목을 모두 입력해 주세요");
        } else {
            $("#modifyform").submit();
        }
    });
    $(window).resize(function(){
            oEditors.getById["content"].exec("SE_FIT_IFRAME",[]);
        });
</script>
<?php
    include "footer.php";
    include "log.php";
?>
<!--------------------------------------------------------------------------------------------------------------------------->