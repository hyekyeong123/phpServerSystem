<?php
$loglimit = 30; //로그 최대 기록횟수


//언제 누가 무엇을--------------------------------------------------------------------------------------------- 
//예를 들어 
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   login                      login_insert.php
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   logout                     logout.php
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   pageview(file/table/no)    view.php
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   adminacc(notice/30)        admin.php
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   write(notice/30/file)      write_insert.php
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   modify(notice/30/file)     comment_insert.php
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   delete(notice/30)          comment_modify.php
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   replyins(notice/30)        reply
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   replymod(notice/30)        
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   replydel(notice/30)        
//2019-12-20  11:16:02   192.168.0.13(아이피)   userid   memmodify(pw/addr/age)     ???
//----------------------------------------------------------------------------------------------------------------------------

//검색된 내용('날짜, 시간, ip, id, 행위')을 log테이블에 기록하는 함수---------------------------------------------------------
function logrec($action){
    $conn = $GLOBALS['conn'];
    $date = date('Y-m-d');
    $time = date('H:i:s');
                                    //테이블이름 다음에 띄어쓰기좀 해 멍청아!!!!!
    mysqli_query($conn,"INSERT INTO log (date,time,ip,id,action) VALUES ('$date','$time','{$_SESSION['ip']}','{$_SESSION['id']}','$action');");
}//--------------------------------------------------------------------------------------------------------------------------

$curpage = basename($_SERVER['PHP_SELF']);  //현재 페이지 파일명 -> list.php
$curpage = str_replace(".php","",$curpage);
            //패턴, 바꿀내용, 원본

$curquery = basename($_SERVER['QUERY_STRING']); //현재 페이지 파일명 -?lbotable=notice&no=30
if(empty($curquery)){//--------------------------------------------
    if(isset($botable)){
        $logbot = $botable;
    }
    if(isset($no)){
        $logno = $no;
    }
    if(isset($bono)){
        $logno = $bono;
    }
}//포스트 일때(쿼리문 없을 때) ->해당 변수들의 값을 바로 가져와야함------------------------------------------------------
//write_insert인 경우 아직 데이터가 들어가기 직전이라 no가 몇번인지 알수 없음
//왜냐하면 데이터 베이스에 값들이 들어가기 시작할때 부여받기 때문
if($curpage == "write_insert"){
    $logno = $furureno;//따라서 white_insert에서 mysqli_insert_id를 이용해서 미리 값을 가져온다음 여기에서 보여주기
}
//--------------------------------------------------------------------------------------------------------------------------
$curquery = explode("&",$curquery); //배열이 됨 ['batable=notice','no=30']
$curlen = count($curquery);

$finalaction="";
for($z=0; $z<$curlen;$z++){
   $eachq = explode('=',$curquery[$z]); //[[botable,'notice'],['no','30']]
   if($z != $curlen-1){
       $finalaction =$finalaction.$eachq[1].'/';
   }else{
    $finalaction =$finalaction.$eachq[1]; //notice, 30
   }
}
if(empty($finalaction)){
    $finalaction = $logbot."/".$logno;
}
$finalaction = $curpage."(".$finalaction.")";


//white.   
logrec($finalaction);
//로그데이터 자동으로 삭제하는 배치 프로그램 갯수 몇개 이상이면 옛날꺼부터 몇개 지우기-----------------------------------------------
//항상 log 테이블의 전체 레코드 수를 세보고 기준 숫자보다 넘치게 되면 가장 첫번째 데이터부터 하나씩 삭제
//no기준으로 오름차순 정렬 후에 limit 1을 삭제
$logdata = mysqli_query($conn,"SELECT no FROM log ORDER BY no ASC;");
$loglen = mysqli_num_rows($logdata);

if($loglen > $loglimit){
    $logfirst = mysqli_query($conn,"DELETE FROM log ORDER BY no ASC LIMIT 1");
}
?>