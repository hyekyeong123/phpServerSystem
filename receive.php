<?php
    include "common.php";
    include "header.php";
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js'></script>
<!-- //차트.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">


<button id='btn' class='btn btn-success'>데이터 받기</button>
<select id='sel'>
    <option value="0">1번</option>
    <option value="1">2번</option>
    <option value="2">3번</option>
</select>
<div id='test' class='alert-success'></div>
<canvas id="myChart" width="400" height="400"></canvas>
<script>

</script>
<!------------------------------------------------------------->
<script>
//데이터 요청해서 받아보기(데이터 하나)
$("#sel").change(function() {
    // 에이젝스는 반드시 이벤트 안에
    $.ajax({
        url: "send.php", //a=abc&b=123과 같음
        method: "GET",
        data: {
            "no": $("#sel").val()
        },
        dataType: "json", //->123.456.789.321
        //받는 데이터의 타입
        success: function(result) {
            $("#test").html(
                "no : " + result.no + "<br/>" +
                "성명 : " + result.name + "<br/>" +
                "나이 : " + result.age + "<br/>"

            );
            setTimeout(function(){

                var ctx = document.getElementById('myChart');
                var myChart = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels: ['국어', '영어', '수학', '사회', '과학'],
                        datasets: [{
                            label: '과목별 성적',
                            data: [
                                result.score.kor,
                                result.score.eng,
                                result.score.math,
                                result.score.soc,
                                result.score.sci
                            ],
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            });
        }
    });
});
</script>


<?php
include "footer.php";
?>