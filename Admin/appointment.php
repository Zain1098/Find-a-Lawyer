<?php
include ('header.php');
include ('connection.php');
$query = "Select * from lawyer join categorie on lawyer.specialist=categorie.cat_id";
$rows = mysqli_query($con, $query);
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
                        <h4 class="card-title">Lawyers</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-sm text-dark">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone_Num</th>
                                        <th>Lawyer</th>
                                        <!-- <th>Password</th> -->
                                        <th>Available</th>
                                        <!-- <th>Address</th>
                                        <th>About</th>
                                        <th>Description</th>
                                        <th>Language</th>
                                        <th>Specialist</th>
                                        <th>Bar Council</th>
                                        <th>Practise Since</th>
                                        <th>University</th>
                                        <th>Degree</th>
                                        <th>Available</th>
                                        <th>Fee</th> -->
                                        <!-- <th>Edit</th> -->
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q = "select * from appointment";
                                    $result2 = mysqli_query($con, $q);
                                    if ($result2) {
                                        while ($data2 = mysqli_fetch_assoc($result2)) { ?>
                                            <tr>
                                                <td><?php echo $data2['id'] ?></td>
                                                <!-- <td><img src="../website/Login&Singup/Lawyer Singup/uploads/<?php
                                                //  echo $data['image']; 
                                                 ?>" alt="Image" style="width:50px; height:50px; border-radius:50%;"></td> -->
                                                <td><?php echo $data2['name'] ?></td>
                                                
                                                <td><?php echo $data2['email'] ?></td>
                                              
                                                <td><?php echo $data2['phone'] ?></td>
                                                <td><?php echo $data2['lawyer'] ?></td>
                                                <td><?php echo $data2['available'] ?></td>
                                               

                                                <td><a href="law_delete.php?id=<?php echo $data['id'] ?>"
                                                        class="btn btn-danger"><i class="fa-solid fa-trash"></i> Delete</a></td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            include ('footer.php');
            ?>