<?php
    include "common.php";
//권한확인--------------------------------------------------------------------------------------------------
    //botable /no를 알아내야 하는데 아직 그것이 get으로 올지 post로 올지 모름
    // 만약 post로 온다면 pass도 같이 옴(비회원)
    // post로 pass값도 같이 왔다면 그건 비회원이 쓴 글이므로 비밀번호 비교할 각오 해야함

        //get으로부터 가져오기
    $botable = $_GET['botable'];
    $no = $_GET['no']; //몇번 게시물을 지울건데?

    if($no == ""){
        //get으로부터 가져온 것이 없다면
        $botable = $_POST['delbotable'];
        $no = $_POST['delno'];
        $pass = $_POST['delpass'];
        if($no == ""){
            //post로부터 가져온 것이 없다면
            warning("잘못된 접근입니다.",-1);
        }
    }
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
                warning("아이디가 다릅니다.",-1);
            }
        }else{
            //비밀번호 대조(비회원이 쓴 글을 삭제할때)
            if(!password_verify($pass, $row['pass'])){
                warning("비밀번호가 다릅니다.",-1);
            }
        }   
    }
//게시물 지우기---------------------------------------------------------------------------------------------------------------------------
//첨부파일이 있는지 없는지? 있다면
//파일을 먼저 지우고(윈도우가 아닌 다른 운영체제에선 파일이 비워져있어야 지울 수 있으니까) 그 후 폴더 지우고
//데이터 베이스의 글을 삭제

if($row['file'] != ""){
    //23213213123/agb.jpg
    // 데이터 베이스 안에 파일이 있다면
    unlink("upload/".$row['file']);
    //파일 지우는 명령어

    $dir = explode("/",$row['file']);
    $dir = $dir[0];
    // 파일이름
    rmdir("upload/".$dir);
    //폴더 지우는 명령어
}
//연관되어 있는 댓글도 함께 지우기-----------------------------------------------------------------------
    mysqli_query($conn,"DELETE FROM comment WHERE botable='$botable' AND bono='$no';");
//게시물 자체를 지우기-----------------------------------------------------------------------------------
mysqli_query($conn,"DELETE FROM $botable WHERE no=$no");
warning("게시물이 삭제되었습니다.","list.php?botable=$botable");
include "log.php";
?>
<!--------------------------------------------------------------------------------------------------------------------------->