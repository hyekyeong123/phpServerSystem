<!-- //관리자 페이지 -->

<?php
    include "common.php";
    include "header.php";
    
    //비관리자 쫒아내기
    if($_SESSION['userlv'] < 8){
        warning("잘못된 접근입니다.","index.html");
    }
    //보고 싶은(보고 있는)페이지 번호
    $page = $_GET['page'];
    if($page == ""){
        $page=0;
    }

    $data = mysqli_query($conn,"SELECT no FROM member ORDER by no desc;");
    $len = mysqli_num_rows($data);
    //total posts의 갯수

    //전페 페이지 수
    $pagelen = ceil($len/$postlen);
    //         =130/10 13개

    //전체 블록수
    $blocklen = ceil($pagelen/$pbtnlen);

    $firstpostno = $postlen * $page;

    $blockno = floor($page / $pbtnlen);

    $firstbtnno = $blockno * $pbtnlen;

?>
<a href="admin.php" class='btn btn-primary'>관리자 페이지로 돌아가기</a>
<table class="table table-hover">
    <thead class="table table-warning">
        <tr>
            <th>NO</th>
            <th>ID</th>
            <th>NAME</th>
            <th>BIRTH</th>
            <th>PHONE</th>
            <th>BANK</th>
            <th>ACOUNT</th>
            <th>JOIN DATE</th>
            <th>USERLV</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $data2= mysqli_query($conn,"SELECT * FROM member ORDER BY no DESC LIMIT $firstpostno, $postlen;");
        
        //php는 전역변수밖에 없어서 변수 재활용해선 안됨
            
        for($i=0;$i<$postlen; $i++){
                $row= mysqli_fetch_array($data2);
                if($row['no'] != ""){
                    if($row['avail'] == 1){
                        echo "<tr>";
                        //그냥 흰색
                    }else{
                        echo "<tr class='alert-secondary'>";
                        //줄 하나를 회색으로
                    }

                    echo "<td>{$row['no']}</td>";
                    if($row['avail'] == 1){
                        echo "<td><button type='button' class='userdel badge badge-danger' data='{$row['no']}'>&times;</button> {$row['userid']}</td>";
                    }else{
                        echo "<td><button type='button' class='userres badge badge-success' data='{$row['no']}'>&cudarrl;</button> {$row['userid']}</td>";
                    }
                                    //여러개 만들거라 id가 아닌 class필요                                                    //넘버를 데이터로  받고
                    echo "<td>{$row['username']}</td>";
                    
                    
                    if($row['birth_month'] != null){
                        $mm = (intval($row['birth_month']))+1;
                    }
                    echo "<td>{$row['birth_year']}-$mm-{$row['birth_date']}</td>";
                    echo "<td>{$row['phone1']}-{$row['phone2']}-{$row['phone3']}</td>";
                    
                    $bank="";
                    if($row['bank']==1){$bank="국민";}
                    if($row['bank']==2){$bank="농협";}
                    if($row['bank']==3){$bank="신한";}
                    echo "<td>$bank</td>";
                    
                    echo "<td>{$row['acount']}</td>";
                    echo "<td>{$row['date']}</td>";
                    
                    
                    echo "<td>";
                    if($row['avail'] == 1){
                        echo "<select class='lvsel' data='{$row['userlv']}'>";
                        // 눌렀다가 취소했을때를 위해서 원래의 정보 저장
                        for($k=0; $k<9; $k++){
                            $num = $k + 1;
                            if($row['userlv'] == $num){
                                echo "<option selected disabled value='$num'>$num</option>";
                                //자동으로 유저레벨의 등급을 선택해놓음 하지만 같은 것은 선택 못하게
                            }else{
                                echo "<option value='$num'>$num</option>";
                                //따로 value안쓰면 그 안에 쓴 값이 value가 됨
                            }
                        }
                        echo "</select>";
                    }else{
                        echo"<select disablde><option>{$row['userlv']}</option></select>";
                        //셀렉트 선택 불가능, 단지 유저의 원래 레벨만 보여주는 기능
                    }

                    echo "</td>";
                    echo "</tr>";
                }   
            }
        ?>
        <tr>
            <td colspan="9">
                <nav aria-label="...">
                    <ul class="pagination justify-content-center">
                        <?php
                            //1. 이전 블록이 존재하는가? =>이번블록은 몇번인가? => 이전블록은 = 현재블록-1 => 만약 이전블록번호가 마이너스가 되면 이전블록은 없는것 
                            //2. 이전 블록의 마지막페이지 번호가 무엇인가? $firstbtnno-1;
                            //3. 이번 블록의 마지막 페이지로 가도록 링크 주소 만들어주기
                            $prevdisabled = "";
                            $prevurl="";
                            if($blockno-1 < 0){
                                $prevdisabled = "disabled";
                            }else{
                                $prevno = $firstbtnno-1;
                                //이전페이지 마지막 번호
                                $prevurl = "?page=".$prevno;
                                            //현재 보고 있는 페이지의 쿼리문 뒤에다
                            }
                            $nextdisabled = "";
                            $nexturl=""; 
                            if($blockno >= $blocklen - 1){
                                $nextdisabled = "disabled";
                            }else{
                                $nextno = $firstbtnno+$pbtnlen;
                                
                                $nexturl = "?page=".$nextno;
                                            //현재 보고 있는 페이지의 쿼리문 뒤에다
                            }
                        
                        ?>
                        <li class="page-item <?php echo $prevdisabled; ?>">
                            <!-- //class disabled -->
                            <a class="page-link" href="<?php echo $prevurl; ?>">&laquo;</a>
                        </li>
                        <!--  -->



                        
                        <?php
                        //첫번째 시작하는 버튼 번호부터 시작해서 1씩 늘어나면서 버튼을 만들되
                        //pbtnlen개만 만든다.
                        //만약 버튼 안에 써야할 그 숫자가 페이지 갯수보다 크면 만들지 않음
                        //그런데 마침 버튼에 써야하는 숫자가 지금 보고 있는 (페이지 번호+1)와 같다면
                        //그 버튼에 클래스(.active)를 추가 
                            for($j=0; $j<$pbtnlen; $j++){
                                $btnno=$firstbtnno+$j+1;
                                if($btnno <= $pagelen){
                                    
                                            //우린 0부터 시작했으니까
                    
                                    $active="";
                                    if($page+1 == $btnno){
                                        $active = "active";
                                    }
                                    $pageurl ="?page=".($btnno-1);
                                    echo "<li class='page-item $active'><a class='page-link' href='$pageurl'>$btnno</a></li>";
                                }
                            }
                        ?>


                        <!-- <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li> -->
                        <!--  -->
                        <li class="page-item <?php echo $nextdisabled; ?>">
                            <a class="page-link" href="<?php echo $nexturl; ?>">&raquo;</a>
                        </li>

                    </ul>
                </nav>
            </td>
        </tr>
    </tbody>
</table>
<!--멤버의 등급 전환-->
<form id="levelform" action="memberlist_insert.php" method="post">
    <input type="text" id='memno' name='memno' hidden> 
    <input type="text" id='memlv' name='memlv' hidden> 
</form>
<script>
    $(".lvsel").change(function(){
        if(confirm("정말로 수정하시겠습니까?")){
            var memno = $(this).parent().parent().children().eq(0).text();
            //번호 
            var memlv = $(this).val(); //레벨

            $("#memno").attr("value",memno);
            $("#memlv").attr("value",memlv);
            $("#levelform").submit();
        }else{
            var origin = $(this).attr("data");
            //수정하기 전의 원래의 데이터
            $(this).children("option").removeAttr("selected");
                                        //그전에 선택한것들을 일단 모두 철회
            $(this).children("option").eq(origin-1).attr("selected","true");
                                                            //selected를 다시 선택하게
        }
    });//--------------------------------------------------------------------------
    //.userdel 버튼을 누르면 "정말로 계정을 삭제하시겠습니까?"라고 물어보고
    //어떤 번호의 계정을 지울지?(방금 누른 버튼의 data속성의 값);
    //해당 번호로 계정을 지우기 위해 그 번호를 memberlist_del.php로 보낸다. 
    $(".userdel").click(function(){
        if(confirm("정말로 계정을 비활성화하시겠습니까?")){
            var delno = $(this).attr('data');
            location.href = 'memberlist_del.php?mode=0&no='+delno;
                                                //mode=0;
        }
    });
    $(".userres").click(function(){
        if(confirm("정말로 계정을 활성화하시겠습니까?")){
            var delno = $(this).attr('data');
            location.href = 'memberlist_del.php?mode=1&no='+delno;
                                                //mode=1;
        }
    });
</script>
<!-- //총 130개-------------------------------------------------------------------------------------------------------------
//한 페이지에서 보여줄 첫번째 게시물 번호
// $firstpostno = $postlen*$page;

//현재 보고 있는 페이지가 몇 번째 블록에 있는가 블록 번호
// $blockno = floor($page/$pbtnlen);

//현재 보고 있는 블록에서 첫번째 페이지 버튼에 들어갈 버튼번호
// $firstbtnno = $blockno*$pbtnlen;

//1. 전체 게시물 수 측정하기($len)
//2. 한 페이지당 보여줄 게시물 수 정하기 ($postlen = 10);
//3. 한 블록당 보여줄 버튼 수 정하기($pbtnlen = 5);
//4. 현재 우리가 보고 있는 페이지 번호 알아내기($page);
//------------------------------------------------------------
//5. 전체 페이지수 구하기 ($pagelen = 올림($len.$postlen));
                                        //(전체 게시물 수 / 한페이지당 보여줄 게시물 수)
//6.  전체 블록 수 구하기 ($blocklen = 올림($pagelen / $pbtnlen))
                                        //전체 페이지 수 / 한 블록당 보여줄 버튼 수
//7. 한 페이지에서 보여줄 첫번째 게시물 번호 구하기($firstpostno = $postlen*$page)
                                        //(페이지 번호 * 한 페이지당 보여줄 페이지 갯수)        
//8. 현재 보고 있는 페이지가 몇 번째 블록에 있는가 블록 번호($blockno = floor($page/$pbtnlen));

//9. 현재 보고 있는 블록에서 첫번째 페이지 버튼에 들어갈 버튼번호($firstbtnno = $blockno*$pbtnlen); -->
<!-- //------------------------------------------------------------------------------------------------------------------------------ -->
                             



























































































<?php
    include "footer.php";
    include "log.php";
?>