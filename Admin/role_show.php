<?php
include ('header.php');
include ('connection.php');
$sql = "select * from role";
$result = mysqli_query($con, $sql);
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

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Basic</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-sm text-dark">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($data = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?php echo $data['role_id'] ?></td>
                                            <td><?php echo $data['role_name'] ?></td>
                                            <td><a href="role_edit.php?id=<?php echo $data['role_id'] ?>"
                                                    class="btn btn-success text-dark"><i class="fa-solid fa-pen"></i>
                                                    Edit</a></td>
                                            <td><a href="role_delete.php?id=<?php echo $data['role_id'] ?>"
                                                    class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include ('footer.php');
            ?>