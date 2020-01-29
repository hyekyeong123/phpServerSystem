<?php
include "common.php";

//0-1. 어느 테이블에 글을 쓸 것인지 확인
$botable = $_POST['botable']; // ex)botable=notice
$no = $_POST['no'];
//modify는 무조건 post임


//1. write.php로부터 데이터 받아서 => 데이터들을 가공해서 =>
$title=txtini($_POST['title']);
$content=$_POST['content'];

//파일과 관련된 모든 것들을 관장
$data = mysqli_query($conn, "SELECT file FROM $botable WHERE no=$no");
$row = mysqli_fetch_assoc($data);
//데이터베이스에서 다시 데이터 베이스 받아오기(기존 파일)
$oldfile=$row['file'];

$filedel = $_POST['filedel'];

$filename = txtini($_FILES['file']['name']); //파일의 이름
$filetype = $_FILES['file']['type']; //파일의 종류
$filesize = $_FILES['file']['size']; //파일의 용량
$filetemp = $_FILES['file']['tmp_name']; //파일의 임시 위치
$date = date("Y-m-d");
//---------------------------------------------------------------------------------------------------------------------------------------------
// if(기존 파일이 있는가){
//     if(del이 1인가 || 새 파일이 있는가){
//         기존 파일 삭제하기
//         데이터베이스에서도 파일명 삭제
//     }
//     if(새 파일이 있는가){
//         새 파일 올려주기
//         데이터베이스에 추가
//     }
// }else{
//     if(새 파일이 있는가){
//         새 파일 올려주기
//         데이터 베이스에 추가
//     }
// }
//2-3 허용할 수 있는 파일 종류 필터링------------------------------------------------------------------------------
if($filename != ""){
    //파일이 있으면
    $allowfile=["image/jpeg","image/gif","image/png","image/bmp","application/x-hwp","application/pdf"];
    $filetypeok = false;
    for($i=0; $i<count($allowfile); $i++){
        if($filetype == $allowfile[$i]){
            $filetypeok = true;
        }
    }
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
//-----------------------------------------------------------
if($oldfile != ""){//기존 파일이 존재한다면?
    if($filedel == "1" || $filename != ""){
        //modify/php에서 기존 파일을 지우겠다고 했거나 또는 새 파일이 있을때여도 기존 파일은 삭제해야하니까 

          //23213213123/agb.jpg
    // 데이터 베이스 안에 파일이 있다면
    unlink("upload/".$oldfile);
    //파일 지우는 명령어

    $dir = explode("/",$oldfile);
    $dir = $dir[0];
    // 파일이름

    rmdir("upload/".$dir);
    //폴더 지우는 명령어

    mysqli_query($conn,"UPDATE $botable SET file='' WHERE no=$no;");

    }
//-------------------------- 
    if($filename != ""){
        // 새로운 파일을 올렸다면
        $time = time();
        //유일한 값
        $dir="upload/".$time;
        // $dir = upload/12123213213
        mkdir($dir);
        // 업로드 안에 12123213213이라는 폴더 만들어라
        $file = $time."/".$filename;
        //데이터 베이스에 넣을 값

        move_uploaded_file($filetemp, $upurl.$time."/".$filename);
                                             //12123213213이라는 폴더에 원래 파일의 이름을 넣어라
        mysqli_query($conn,"UPDATE $botable SET file='$file' WHERE no=$no;");
    }
}else{ 
    //기존 파일이 애초에 없다면
    if($filename != ""){
        //새로운 파일을 올렸다면    
       
        $time = time(); //유일한 값
       
        $dir="upload/".$time;// $dir = upload/12123213213
        
        mkdir($dir);// 업로드 안에 12123213213이라는 폴더 만들어라
        
        $file = $time."/".$filename;//데이터 베이스에 넣을 값
        
        move_uploaded_file($filetemp, $upurl.$time."/".$filename);//12123213213이라는 폴더에 원래 파일의 이름을 넣어라
        mysqli_query($conn,"UPDATE $botable SET file='$file' WHERE no=$no;");
    }
}
//-------------------------------------------------------------------------------------------------------------------------------------- 
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
//--------------------------------------------------------------------------------------------------------------------------- 
$result= mysqli_query($conn,"UPDATE $botable SET title='$title', sec=$sec, content='$content',date='$date' WHERE no=$no;");                                        

    echo "<script>";
    echo "alert('게시물을 수정하였습니다.');";
    echo "location.href='list.php?botable=$botable';";
    echo "</script>";
    include "log.php";
?>