<?php
include('header.php'); // Assuming you have a header file for consistency
include('connection.php'); // Database connection file

// Check if 'id' parameter is set in the URL
if(isset($_GET['id'])) {
    // Fetch details for a specific lawyer based on 'id'
    $id = $_GET['id'];
    $sql = "SELECT *
            FROM lawyer
            JOIN categorie ON categorie.id = lawyer.specialist
            WHERE lawyer.id = '$id';";
    $res = mysqli_query($con, $sql);
    if(mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['name']; ?> - Lawyer Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img src="<?php echo $data['image']; ?>" class="card-img-top" alt="Lawyer Photo">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $data['name']; ?> <?php echo $data['last_name']; ?></h5>
                    <p class="card-text"><?php echo $data['since']; ?> years Experience</p>
                    <p class="card-text"><?php echo $data['address']; ?></p>
                    <a href="#" class="btn btn-primary btn-block">Appointment</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bio</h5>
                    <p class="card-text"><?php echo $data['about_me']; ?></p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Services</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?php echo $data['description']; ?></li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Available Timings</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><?php echo $data['available']; ?></li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Education</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Law Degree from <?php echo $data['university']; ?></li>
                        <li class="list-group-item"><b>Degree:</b> <?php echo $data['degree']; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    } else {
        echo "<p>No lawyer found with ID: $id</p>";
    }
} else {
    echo "<p>Lawyer ID not specified.</p>";
}
include('footer.php'); // Assuming you have a footer file for consistency
?>

</body>
</html>
