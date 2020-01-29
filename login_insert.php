<?php
    include "common.php";

    $logid=txtini($_POST['logid']);
    $logpw=$_POST['logpw'];

    //그런 아이디가 있는지 없는지
    //없으면 => '아이디와 비밀번호를 확인해주세요' 보안상
    //있으면 => 비밀번호 뭔지
    //그 비밀번호와 지금 입력한 비밀번호를 비교!

    $findid = mysqli_query($conn,"SELECT userid,userpw,userlv,avail FROM member where userid='$logid';");
    $findidlen = mysqli_num_rows($findid);

    if($findidlen==0){
        echo "<script>";
        echo    "alert('아이디와 비밀번호를 확인해주세요.');";
        echo "location.href='login.php';";
        echo "</script>";
        exit;
    }else{
        $data = mysqli_fetch_array($findid); //배열이 됨
        $hashpw = $data['userpw'];
                        //현재 암호화된 비밀번호임

        if(password_verify($logpw,$hashpw)){
            //만약에 로그인한 아이디와 암호하된 비밀번호가 같은 거라면

            if($data['avail'] == 1){
                $_SESSION['log'] = true; //로그인 한 상태
                $_SESSION['id'] = $data['userid'];
                $_SESSION['userlv'] = $data['userlv'];
                //로그인 한 아이디
               warning("로그인이 되었습니다.",-2);
            }else{
                //강퇴당한 사람이라면 session도 만들어선 안됨
                warning("비활성 계정입니다.",-1);
            }
            if($_SESSION['userlv']>=8){
                //관리자 레벨은 9임
                echo "alert('관리자 아이디로 접속 중 입니다.');";
            }
            echo "history.go(-2);";
            echo "</script>";
        }else{
            warning("아이디와 비밀번호를 확인해주세요.","login.php");
        }
    }
    include "log.php";
?>