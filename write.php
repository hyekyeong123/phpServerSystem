<?php
include "common.php";
include "header.php";

$botable = $_GET['botable'];
//어느 테이블에 쓸 것인지

if ($botable == "") {
    //비어있으면 잘못 접근 한거니까
    echo "<script>";
    echo "alert('잘못된 접급입니다.');";
    echo "history.back();";
    echo "</script>";
}
//0. 글쓰기 권한이 있는지 확인
if ($_SESSION['userlv'] < ${$botable."_write"}) {
    //만약에 유저의 레벨이 $notice_write(ex)5)보다 크거나 같으면 권한이 있는 것
    echo "<script>";
    echo "alert('잘못된 접근입니다.');";
    echo "history.back();";
    echo "</script>";
    exit;
}
?>
<script type="text/javascript" src="se/js/service/HuskyEZCreator.js" charset="utf-8"></script>
<!-- //스마트 보드 -->
<link href="css/board.css" rel="stylesheet">
<!------------------------------------------------------------------------------------->
<script>
$(document).ready(function() {
    $("#writeform").trigger("reset");
});
</script>
<!--------------------------------------------------------------------------------------------------------------------------->
<form id="writerform" action="write_insert.php" method="post" enctype="multipart/form-data">
    <!-- //파일첨부할는 반드시 post-->
    <input type="text" name="botable" value="<?php echo $botable; ?>" hidden />
    <!-- //write_insert.php는 post방식이기때문에 주소를 모름 따라서 정보를 넣어줌-->

    <div class="alert border-primary">
        <div class="row">
            <div class="col-3">제목</div>
            <div class="col-9">
                <input name="title" id="title" type="text" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-3">작성자</div>
            <div class="col-9">
                <!-------------------------------------------------------------------------------------------------------->
                <?php
                $writerid = ""; //글쓴이 아이디
                $writername = "";    //글쓴이 이름? 닉네임?
                $readonly = "";

                if ($_SESSION['log']) {
                    //로그인 사용자
                    $writerid = $_SESSION['id'];
                    $data = mysqli_query($conn, "SELECT username FROM member where userid='$writerid';");

                    $writername = mysqli_fetch_array($data);
                    $writername = $writername['username'];
                    $readonly = "readonly";
                }
                ?>
                <!------------------------------------------------------------------------------------------------------------------------------->
                <input name="writerid" type="text" value="<?php echo $writerid; ?>" hidden class="form-control">
                <input id="writername" name="writername" type="text" value="<?php echo $writername; ?>"
                    <?php echo $readonly; ?> class="form-control" />
            </div>
        </div>
        <!--------------------------------------------------------------------------------------------->
        <?php
        if (!$_SESSION['log']) {
            //비로그인 사용자 일때만 있음
            echo "<div class='row'>";
            echo "<div class='col-3'>비밀번호</div>";
            echo "<div class='col-9'>";
            echo "<input id='pass' type='password' name='pass' class='form-control'>";
            echo "</div>";
            echo "</div>";
        }
        ?>
        <!---------------------------------------------------------------------------------------------------->
        <div class="row">
            <div class="col-3">비밀글</div>
            <div class="col-9">
                <input name="sec" type="checkbox" value="on" class="form-control" />
                <!-- //on이면 비공개로 아니면 공개 -->
            </div>
        </div>

        <div class="row">
            <div class="col-3">내용</div>
            <div class="col-9">
                <textarea id="content" class="form-control" name="content" style="min-width:260px";></textarea>
                <script type="text/javascript">
                var oEditors = [];
                nhn.husky.EZCreator.createInIFrame({
                    oAppRef: oEditors,
                    elPlaceHolder: "content",
                    sSkinURI: "se/SmartEditor2Skin.html",
                    fCreator: "createSEditor2"
                });
                </script>

                <!-- //안에 글자를 입력하면 value값이 됨 -->
            </div>
        </div>

        <div class="row">
            <div class="col-3">첨부파일</div>
            <div class="col-9">
                <input name="myfile" type="file" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <button id="write" type="button" class="btn btn-info">글쓰기</button>
                <button type="reset" class="btn btn-secondary">초기화</button>
            </div>
        </div>

    </div>


</form>
<script>
        $("#write").click(function() {
            oEditors.getById["content"].exec("UPDATE_CONTENTS_FIELD", []);
            //텍스트 에디터 기능 가져오기
            
            //#title, #writername, $pass, $content 내용이 없으면 보내지 않음
            var title = $("#title").val().length;
            var writername = $("#writername").val().length;
            var pass;
            if ($("#pass").length != 0) {
                //pass가 있다면
                pass = $("#pass").val().length;
            } else {
                pass = 1;
                //곱하기에 영향 없게
            }
            
            var content = $("#content").val().length;

            if (title * writername * pass * content == 0) {
                alert("필수항목을 모두 입력해 주세요");
            } else {
                $("#writerform").submit();
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