<?php
include "common.php";

$dtime = date("H:i:s",strtotime("-60 minutes"));
//1분전
$data = mysqli_query($conn,"SELECT DISTINCT ip,id FROM log WHERE time>'$dtime' AND date='$today';");
                                    //중복제거  ip, id가져오기
$len = mysqli_num_rows($data);
$result;
for($i=0;$i<$len;$i++){
    $row = mysqli_fetch_array($data);
    $name=id2name("{$row['id']}");
    // echo var_dump($name);
    ${"r".$i} = array(
        "ip" => "{$row['ip']}",
        "id" => "{$row['id']}",
        "name" => "$name"
        );  
    array_push($result,${"r".$i});
}
echo $result[0]['ip'];
//모든 사람들의 아이피와 아이디 네임을 뽑아내는 방법---------------

// for($i=0;$i<$len;$i++){
//     $row = mysqli_fetch_array($data);

// }
?>