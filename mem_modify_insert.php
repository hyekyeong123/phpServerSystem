<?php
    include "common.php";

    $userid = $_POST['userid'];
    if($userid != $_SESSION['id'])
    {
        warning("누구십니까?","http://police.go.kr");
    }

    $userid = txtini($_POST['userid']);
    $userid = sqlfilter($userid);

    $userpw = $_POST['userpw'];
   
    $username = txtini($_POST['username']);
    $birth_year = $_POST['birth_year'];
    $birth_month = $_POST['birth_month'];
    $birth_date = $_POST['birth_date'];
    
    $phone1 = $_POST['phone1'];
    $phone2 = txtini($_POST['phone2']);
    $phone3 = txtini($_POST['phone3']);

    $bank = $_POST['bank'];
    $acount = txtini($_POST['acount']);

    if(!empty($userpw)){
        //사용자가 비밀번호를 변경하였을 때
        $userpw=password_hash($userpw,PASSWORD_DEFAULT,['cost' => 10]);
        //암호화
        mysqli_query($conn,"UPDATE member SET userpw='$userpw' WHERE userid='$userid' ;") or die("데이터 입력 오류")  ;
    }else{
        //새 비밀번호가 없을 때 
    }
    
    //나머지 데이터 업데이트
    mysqli_query($conn,"UPDATE member SET bitrh_year='$bitrh_year',birth_month='$birth_month',birth_date='$birth_date',phone1='$phone1',phone2='$phone2',phone3='$phone3',bank='$bank',acount='$acount' WHERE userid ='$userid';");

    warning("회원정보 수정이 완료되었습니다.","index.php");
    include "log.php";
?>