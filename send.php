<?php
$no = $_GET['no'];

$people = array(
    array(
        "no" => "0",
        "name" => "지호",
        "age" => "20",
        "score" => array(
            "kor" => 100,
            "eng" => 99,
            "math" =>98,
            "soc" =>80,
            "sci" =>100
        )
    ),
    array(
        "no" => "1",
        "name" => "태희",
        "age" => "22",
        "score" => array(
            "kor" => 32,
            "eng" => 15,
            "math" =>25,
            "soc" =>70,
            "sci" =>80
        )
    ),
    array(
        "no" => "2",
        "name" => "동건",
        "age" => "26",
        "score" => array(
            "kor" => 0,
            "eng" => 1,
            "math" =>2,
            "soc" =>50,
            "sci" =>60
        )
    )
);
$result = $people[$no];
$final = json_encode($result);
echo $final;
?>