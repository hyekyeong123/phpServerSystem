<?php
    include "common.php";
    include "header.php";
?>
<div class="jumbotron">
    <h1 class="display-4">처음하는 부트스트랩</h1>
    <p class="lead">
        백엔드라니??
    </p>
    <hr class="my-4">
    <p>
        마진은 어케주는건가요 mx 어쩌구저쩌구같은뎅
    </p>
    <a class="btn btn-info btn-lg" href="#" role="button">Learn more</a>
</div>
<!-- ------------------------------------------------------------------------------------------------------------------------->
<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">데이터베이스</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-info">Go Database</a>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">PHP</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-info">Go PHP</a>
            </div>
        </div>
    </div>
</div>
<!-- --------------------------------------------------------------------------------------------------------------------- -->
<div class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?php
    include "footer.php";
    include "log.php";
?>