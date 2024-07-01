<?php
include('header.php');
include('connection.php');

$id=$_GET['id'];
$query="select * from role where role_id='$id'";
$res=mysqli_query($con,$query);
$data=mysqli_fetch_assoc($res);

if(isset($_POST['update'])){
    $name=$_POST['name'];
    $sql="update role set role_name='$name' where role_id='$id'";
    $result=mysqli_query($con,$sql);
    if($result){
        echo "<script>alert('Role Edited'); window.location.href='role_show.php'</script>";
    }
}
?>
<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Hi, welcome back!</h4>
                            <span class="ml-1">Element</span>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0)">Form</a></li>
                            <li class="breadcrumb-item active"><a href="javascript:void(0)">Element</a></li>
                        </ol>
                    </div>
                </div>
                <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add Role</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form method="POST">

                                        <div class="form-row">
                                            <div class="form-group col-md-6 text-dark">
                                                <label>Role Name</label>
                                                <input type="text" name="name" value="<?php echo $data['role_name']?>" class="form-control" placeholder="Enter Role...">
                                            </div>
                                            </div>
                                        <button type="submit" name="update" class="btn btn-primary">Update Role</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>

<?php
include('footer.php');
?>