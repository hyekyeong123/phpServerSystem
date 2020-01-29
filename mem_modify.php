<?php
include "common.php";
include "header.php";

if(!isset($_SESSION['log'])){
    warning("로그인이 필요합니다.","login.php");
}
$memdata = mysqli_query($conn,"SELECT * FROM member WHERE userid='{$_SESSION['id']}';");
$memrow = mysqli_fetch_assoc($memdata);
// echo "<script>alert('{$memrow['username']}')</script>";

?>

<h1>my information</h1>
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
<!------------------------------------------------------------------------------------------------------------------------------------------->
<div class="card border-info mb-3" style="max-width: 80vw;">
    <div class="card-header">Basic informaion</div>
    <div class="card-body text-info">
        <form id="form1" action="mem_modify_insert.php" method="post">
            <label>
                ID : 
                <input type="text" name='userid' readonly class="form-control" value="<?php echo $memrow['userid']; ?>">
                                    <!-- //부를수 있게 할려고 disonly는 아예 데이터를 보내지 않음                     //아이디 보여주기 -->
            </label>
            <label>
                Password : <input name="userpw" id="userpw" type="password" class="form-control">
            </label>
            <label>
                Confirm Password : <input id="cpw" type="password" name="cpw" class="form-control">
            </label>
            <label>
                Full name : <input type="text" class="form-control" value="<?php echo $memrow['username']; ?>" disabled>
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
                        <select name="phone1" id="phone1" class="form-control">
                            <option value="">select</option>
                            <option value="010">010</option>
                            <option value="02">02</option>
                            <option value="042">042</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <input type="text" name="phone2" value="<?php echo $memrow['phone2'] ?>" class="form-control">
                    </div>
                    <div class="col-4">
                        <input type="text" name="phone3" value="<?php echo $memrow['phone3'] ?>" class="form-control">
                    </div>
                </div>
            </label>
            <label>
                Bank acount
                <div class="row">
                    <div class="col-3">
                        <select name="bank" id="bank" class="form-control">
                            <option value="">Select bank</option>
                            <option value="01">국민은행</option>
                            <option value="02">농협</option>
                            <option value="03">하나은행</option>
                        </select>
                    </div>
                    <div class="col-9">
                        <input type="text" name="acount"  class="form-control" value="<?php echo $memrow['acount'] ?>">
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
<script>
//--------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function(){
    
    $("#birth_year>option[value=<?php echo $memrow['birth_year']; ?>]").attr("Selected","true");
                //안에 있는 option에다가 
    $("#birth_month>option[value=<?php echo $memrow['birth_month']; ?>]").attr("Selected","true");
    $("#birth_date>option[value=<?php echo $memrow['birth_date']; ?>]").attr("Selected","true");
    $("#birth_phone1>option[value=<?php echo $memrow['phone1']; ?>]").attr("Selected","true");
    $("#birth_bank>option[value=<?php echo $memrow['bank']; ?>]").attr("Selected","true");
                            
    $("#submit1").click(function(){
        if($("#userpw").val()==$("#cpw").val()){
            $("#form1").submit();
        }else{
            alert("비밀번호를 확인해 주세요");
        }
    });
});
// --------------------------------------------------------------------------------------------------------------------------
</script>




<?php
include "footer.php";
include "log.php";
?>