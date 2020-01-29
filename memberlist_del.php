<?php
    include "common.php";

    if($_SESSION['userlv'] < 8){
        warning("잘못된 접근입니다.","index.php");
    }
    //관리자가 아닌 사용자는 돌려 보내놓고
    
    $no = $_GET['no'];
    $mode= $_GET['mode'];

    if($mode == 0){
        mysqli_query($conn,"UPDATE member SET avail=0, deldate='$today' WHERE no=$no;");
        //전단계에서 no라는 이름으로 보내온 지울 계정의 번호 갈무리
        //member테이블에서 번호가 그 번호인 계정의 avail값을 0으로 만들고 deldate값을 $today로 변경한다.
        
        warning("사용자 계정이 비활성화되었습니다.\\n30일 이후에 계정이 자동 삭제됩니다.",-1);
        //"사용자 계정이 비활성화되었습니다.\n30일 이후에 계정이 자동 삭제됩니다." 
        //뒤로가기
    }elseif($mode == 1){
        mysqli_query($conn,"UPDATE member SET avail=1, deldate=NULL WHERE no=$no;");
        warning("사용자 계정이 활성화 되었습니다.",-1);
        
    }
    include "log.php";
?>