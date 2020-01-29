<?php
    include "common.php";


    $userid = txtini($_POST['userid']);
    $userid = sqlfilter($userid);
    $userpw = $_POST['userpw'];
    $userpw=password_hash($userpw,PASSWORD_DEFAULT,['cost' => 10]);


    $username = txtini($_POST['username']);
    $birth_year = $_POST['birth_year'];
    $birth_month = $_POST['birth_month'];
    $birth_date = $_POST['birth_date'];
    
    $phone1 = $_POST['phone1'];
    $phone2 = txtini($_POST['phone2']);
    $phone3 = txtini($_POST['phone3']);

    $bank = $_POST['bank'];

    $acount = txtini($_POST['acount']);

    $date=date("Y-m-d");


    // echo $userid."<br/>";
    // echo $userpw."<br/>";
    // echo $username."<br/>";
    // echo $birth_year."<br/>";
    // echo $birth_month."<br/>";
    // echo $birth_date."<br/>";
    // echo $phone1."<br/>";
    // echo $phone2."<br/>";
    // echo $phone3."<br/>";
    // echo $bank."<br/>";
    // echo $acount."<br/>";
    // echo $date."<br/>";


    // echo "INSERT INTO member 
    // (userid, userpw, username, birth_year, birth_month, birth_date, phone1, phone2, phone3 ,bank, acount, date, userlv, avail, deldate) VALUES 
    // ('$userid', '$userpw', '$username','$birth_year','$birth_month','$birth_date','$phone1','$phone2','$phone3','$bank','$acount', '$date', 1, 1, null);";

    // mysqli_query($conn,"INSERT INTO member (userid, userpw, username, birth_year, birth_month, birth_date, phone1, phone2, phone3, bank, acount, date, userlv) VALUES ('$userid','$userpw','$username',$birth_year,$birth_month,$birth_date,$phone1,$phone2,$phone3,$bank,'$acount','$date',1);") or die("데이터 입력 오류 그만 좀 죽어!!!!!!");
    mysqli_query($conn, "INSERT INTO member (userid, userpw, username, birth_year, birth_month, birth_date, phone1, phone2, phone3 ,bank, acount, date, userlv, avail, deldate) VALUES ('$userid', '$userpw', '$username', '$birth_year', '$birth_month', '$birth_date','$phone1', '$phone2', '$phone3', '$bank', '$acount', '$date', 1, 1, null);") or die("데이터입력 오류:");
    
    // //회원가입 정보 가져오기

    echo "<script>";
    echo    "alert('회원가입이 완료되었습니다. 로그인 해 주세요.');";
    echo    "location.href='login.php';";
    echo "</script>";
    include "log.php";
    ?>