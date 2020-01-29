<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/common.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

    <body>
    <header class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">777</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto ">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list.php?botable=notice">Notice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list.php?botable=guest">Guest</a>
                    </li>

                   
                    <?php
                        if($_SESSION['log']){
                            echo "<a id='logout' class='nav-link btn btn-info' href='#'>Logout({$_SESSION['id']})</a>";
                            echo "<a class='btn btn-primary' href='mem_modify.php'>Modify Info.</a>";
                        }else{
                            echo "<a class='nav-link btn btn-info' href='login.php'>Login</a>";
                            echo "<a class='nav-link btn btn-info' href='join.php'>join</a>";
                        }
                        if($_SESSION['userlv'] > 8){
                            echo "<li class='nav-item'>";
                            echo "<a class='nav-link btn btn-warning' href='admin.php'>관리자페이지</a>";
                            echo "</li>";
                        }   
                    ?>
                    <script>
                        $("#logout").click(function(){
                            if(confirm("정말로 로그아웃 하시겠습니까?")){
                                location.href = "logout.php";
                            }else{

                            }
                        });
                    </script>
                    </li>
                    <li class="nav-item">
                        <?php
                            $todaylen = mysqli_query($conn,"SELECT no FROM counter WHERE date='$today';"); //오늘 접속자
                                                                                            //오늘의 날짜와 같은 
                            $todaylen = mysqli_num_rows($todaylen);
                           
                            $totallen = mysqli_query($conn,"SELECT count FROM counter WHERE no=1");
                            $totallen = mysqli_fetch_assoc($totallen);
                            $totallen =$totallen['count'];
                            echo "오늘 접속자 : $todaylen / 누적 접속자 : $totallen";
                        ?>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <section class="container">
        <!--  -->