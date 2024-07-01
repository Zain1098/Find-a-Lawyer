<?php
include ('header.php');
include ('connection.php');
$query = "Select * from lawyer join categorie on lawyer.specialist=categorie.id";
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
                                        <th>Image</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>About</th>
                                        <th>Description</th>
                                        <th>Language</th>
                                        <th>Specialist</th>
                                        <th>Bar Council</th>
                                        <th>Practise Since</th>
                                        <th>University</th>
                                        <th>Degree</th>
                                        <th>Available</th>
                                        <th>Fee</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q = "select * from lawyer";
                                    $result = mysqli_query($con, $q);
                                    if ($result) {
                                        while ($data = mysqli_fetch_assoc($rows)) { ?>
                                            <tr>
                                                <td><?php echo $data['id'] ?></td>
                                                <td><img src="<?php echo $data['image'] ?>" alt=""
                                                        style="width: 50px;height:40px;border-radius:50%;"></td>
                                                <td><?php echo $data['name'] ?></td>
                                                <td><?php echo $data['last name'] ?></td>
                                                <td><?php echo $data['email'] ?></td>
                                                <td><?php echo $data['password'] ?></td>
                                                <td><?php echo $data['number'] ?></td>
                                                <td><?php echo $data['address'] ?></td>
                                                <td><?php echo $data['about me'] ?></td>
                                                <td><?php echo $data['description'] ?></td>
                                                <td><?php echo $data['language'] ?></td>
                                                <td><?php echo $data['cat_name'] ?></td>
                                                <td><?php echo $data['bar council'] ?></td>
                                                <td><?php echo $data['since'] ?></td>
                                                <td><?php echo $data['university'] ?></td>
                                                <td><?php echo $data['degree'] ?></td>
                                                <td><?php echo $data['available'] ?></td>
                                                <td><?php echo $data['fee'] ?></td>
                                                <td><a href="role_edit.php?id=<?php echo $data['id'] ?>"
                                                        class="btn btn-success text-dark"><i class="fa-solid fa-pen"></i>
                                                        Edit</a></td>
                                                <td><a href="role_delete.php?id=<?php echo $data['id'] ?>"
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