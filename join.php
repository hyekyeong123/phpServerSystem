<?php
include "common.php";
include "header.php";
?>
<h1>join</h1>
<small class="text-muted mb-3">
    join our site or die
</small>
<!--  -->
<style>
    form label {
        width: 100%;
        max-width: 700px;
        margin: auto;
        display: block;
        margin: auto;
        margin-top: 1em;
    }
</style>


<!--  -->
<div class="card border-info mb-3" style="max-width: 80vw;">
    <div class="card-header">Basic informaion</div>
    <div class="card-body text-info">
        <form id="form1" action="join_insert.php" method="post">
            <label>
                ID : 
                <span id='chkresult' class='badge badge-danger'></span>
                <input name="userid" id="userid" type="text" placeholder="input your id." class="form-control">
            </label>
            <label>
                Password : <input name="userpw" id="userpw" type="password" class="form-control">
            </label>
            <label>
                Confirm Password : <input id="cpw" type="password" name="cpw" class="form-control">
            </label>
            <label>
                Full name : <input name="username" id="username" type="text" class="form-control">
            </label>
            <label>
                Birth :
                <div class="row">
                    <div class="col-4">
                        <select name="birth_year" id="birth_year" class="form-control">
                            <option value="">select year</option>
                            <option value="1990">1990</option>
                            <option value="1989">1989</option>
                            <option value="1988">1988</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="birth_month" id="birth_month" class="form-control">
                            <option value="">select year</option>
                            <option value="0">1</option>
                            <option value="1">2</option>
                            <option value="2">3</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <select name="birth_date" id="birth_date" class="form-control">
                            <option value="">select date</option>
                            <option value="0">1</option>
                            <option value="1">2</option>
                            <option value="2">3</option>
                        </select>
                    </div>
                </div>
            </label>
            <label>
                <div class="row">
                    <div class="col-4">
                        <select name="phone1" id="" class="form-control">
                            <option value="">select</option>
                            <option value="010">010</option>
                            <option value="02">02</option>
                            <option value="042">042</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <input type="text" name="phone2" class="form-control">
                    </div>
                    <div class="col-4">
                        <input type="text" name="phone3" class="form-control">
                    </div>
                </div>
            </label>
            <label>
                Bank acount
                <div class="row">
                    <div class="col-3">
                        <select name="bank" id="" class="form-control">
                            <option value="">Select bank</option>
                            <option value="01">국민은행</option>
                            <option value="02">농협</option>
                            <option value="03">하나은행</option>
                        </select>
                    </div>
                    <div class="col-9">
                        <input type="text" name="acount"  class="form-control">
                    </div>
                </div>
            </label>
            <hr>
            <div class="text-center">
                <button class="btn btn-info" type="button" id="submit1">
                    Submit
                </button>
                <button class="btn btn-secondary" type="reset">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>
<script>//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function(){
    var chk = false;
    // 
    $("#submit1").click(function(){
        var a1 = $("#userid").val().length;
        var a2 = $("#userpw").val().length;
        var a3 = $("#cpw").val().length;
        var a4 = $("#username").val().length;
        var result=a1*a2*a3*a4;
        
        if(result==0){
            alert("필수 입력칸을 채워주세요");
        }else{
            if($("#userpw").val()==$("#cpw").val()){
                if(chk){
                    //비밀번호 중복 확인이 통과하면
                    $("#form1").submit();
                }else{
                    alert('아이디를 체크해주세요.');
                }
            }
            else{
                alert("비밀번호를 확인해 주세요");
            }
        }
    });
// --------------------------------------------------------------------------------------------------------------------------
    $("#userid").keyup(function(){
    var key = $(this).val();
    $.ajax({
        method:'get', //http 요청 방식(get or post)
        url:'chkid.php', //클라이언트 요청을 보낼 서버의 url 주소
        data:'userid='+key, //http요청과 함께 서버로 보낼 데이터 주소
        dataType:'html', //서버에서 보내줄 데이터의 타입

    // HTTP 요청이 성공하면 요청한 데이터가 success() 메소드로 전달됨.
        success:function(result){
            if(result == 0){
                //중복된 아이디의 갯수
                $("#chkresult").text("사용할 수 있는 아이디");
                $("#chkresult").removeClass('badge-danger');
                $("#chkresult").addClass('badge-success')
                chk = true;
            }else if(result == 1){
                $('#chkresult').text('사용할 수 없는 아이디');
                $("#chkresult").removeClass('badge-success');
                $("#chkresult").addClass('badge-danger');
                chk = false;
            }else if(result == 'empty'){
                $('#chkresult').text('아이디를 입력하세요');
                $("#chkresult").removeClass('badge-success');
                $("#chkresult").addClass('badge-danger');
                chk='false';
            }
        }
    });
    });
});
</script>




<?php
include "footer.php";
include "log.php";
?>