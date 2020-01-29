<?php
include "common.php";
include "header.php";
?>
<div class="alert border-info" style="max-width:600px; width:100%; margin:70px 10px ; padding:10px;">
    <form id="logform" action="login_insert.php" method="POST">
        <div class="row">
            <div class="col-3">
                ID
            </div>
            <div class="col-9">
                <input id="logid" name="logid" type="text" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                Password
            </div>
            <div class="col-9">
                <input id="logpw" name="logpw" type="password" class="form-control">
            </div>
        </div>
        <div class="text-center" style="margin-top:50px;">
            <button id="logsubmit" type="button" class="btn btn-info">
                go login
            </button>
        </div>
    </form>
</div>

<script>
    $("#logsubmit").click(function(){
        var a1 = $("#logid").val().length;
        var a2 = $("#logpw").val().length;
        if(a1*a2!=0){
            $("#logform").submit();
        }else{
            alert("다시 한번 확인해주세요");
        }
    });
        $("#logpw").keydown(function(e){
            var key=e.keyCode;
            if(key==13){
                $("#logsubmit").trigger("click");
            }
        });

  
</script>


<?php
include "footer.php";
include "log.php";
?>