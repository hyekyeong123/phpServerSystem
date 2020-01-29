<?php
include "common.php";


//0-1. 어느 테이블에 글을 쓸 것인지 확인
$botable = $_POST['botable']; // ex)botable=notice

//0. 글쓰기 권한이 있는지 확인
if($_SESSION['userlv'] < ${$botable."_write"}){
    //만약에 유저의 레벨이 $notice_write(ex)5)보다 크거나 같으면 권한이 있는 것
    echo "<script>";
    echo "alert('잘못된 접근입니다.');";
    echo "history.back();";
    echo "</script>";
    exit;
}
//write에서도 마찬가지로 권한을 확인해야함


//1. write.php로부터 데이터 받아서 => 데이터들을 가공해서 =>
$title = txtini($_POST['title']);
$writerid=$_POST['writerid']; //null o
$writername=txtini($_POST['writername']); //null o
$content=$_POST['content'];

//파일과 관련된 모든 것들을 관장
$filename = txtini($_FILES['myfile']['name']); //파일의 이름
$filetype = $_FILES['myfile']['type']; //파일의 종류
$filesize = $_FILES['myfile']['size']; //파 일의 용량   
$filetemp = $_FILES['myfile']['tmp_name']; //파일의 임시 위치
$date = date("Y-m-d");

//2-1. 비밀번호는 해쉬화해서 저장하기
$pass=$_POST['pass']; //null o
if($pass != ""){
    //공백이 아니라면, 값이 있다면
    $pass = password_hash($pass,PASSWORD_DEFAULT,['cost' => 10]);  
}
//2-2. 비밀글 여부가 "on" => 0또는 1의 숫자로 만들어서 저장
$sec = $_POST['sec'];
if($sec == "on"){
    $sec = 1; //비밀글로 하겠음
}else{
    $sec= 0;
}


//2-3 허용할 수 있는 파일 종류 필터링
if($filename != ""){
    //파일이 있으면
    $allowfile=["image/jpeg","image/gif","image/png","image/bmp","application/x-hwp","application/pdf"];
    $filetypeok = false;
    for($i=0; $i<count($allowfile); $i++){
        if($filetype == $allowfile[$i]){
            $filetypeok = true;
        }
    };
    if(!$filetypeok){
        echo "<script>";
        echo "alert('업로드 할 수 없는 파일입니다.');";
        echo "history.back();";
        echo "</script>";
        exit;
    }

    //허용할 수 있는 용량 필터링(3MB)
    if($filesize > 3145728){
        echo "<script>";
        echo "alert('3MB 이하만 업로드 하실 수 있습니다.');";
        echo "history.back();";
        echo "</script>";
    }
}
//확장자 떼어내기 => 파일명 안 겹치게 바꿔주기 => 원래 확장자 다시 붙여주기----------------------------------------------------------------------
//임시폴더로부터 실제로 upload폴더에 파일 옮겨주기
//최종적으로 저장하게 된 경로와 파일명을 데이터베이스에 기록!
// $filename //ex-abc.jpg
if($filename != ""){
    $time = time();
    //유일한 값
    $dir="upload/".$time;
    // $dir = upload/12123213213
    mkdir($dir);
    // 업로드 안에 12123213213이라는 폴더 만들어라
    move_uploaded_file($filetemp, $upurl.$time."/".$filename);
                                                //12123213213이라는 폴더에 원래 파일의 이름을 넣어라
    $file = $time."/".$filename;
    //데이터 베이스에 넣을 값
}else{
    $file="";
}
//--------------------------------------------------------------------------------------------------------------------------- 
$result = mysqli_query($conn,"INSERT INTO $botable (title, writerid, writername, content, pass, sec, date, count, file) values ('$title','$writerid','$writername','$content','$pass',$sec,'$date',0,'$file');");
                                        //현재는 notice
    $furureno = mysqli_insert_id($conn);

    echo "<script>";
    echo "alert('게시물을 등록하였습니다.');";
    echo "location.href='list.php?botable=$botable';";
    echo "</script>";

//2-4. 날짜, 조회수 등등
// $date = date("Y-m-d");


//3. 데이터베이스로 보내기
include "log.php";
?>