<?php
    include "common.php";
    //아이디가 있나 없나 검색

    
    $userid = $_GET['userid'];
    
    if(!empty($userid)){
        //비어 있는 것이 아니라면
        $data = mysqli_query($conn, "SELECT no FROM member WHERE userid='$userid';");
        //입력한 아이디와 같은것을 저장하고
        $len = mysqli_num_rows($data);
        // 그것의 갯수를 세고
        echo $len;

    }else{
        //비어있다면
        echo 'empty';
    }

    
?>