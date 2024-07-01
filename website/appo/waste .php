<?php
include('connection.php');

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $law = $_POST['law'];
    $avail = $_POST['avail'];

    // Prepare and bind
    $stmt = $con->prepare("INSERT INTO appointment (name, email, phone, lawyer, available) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $law, $avail);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: appoint.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$con->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Form HTML Template</title>
    <link href="http://fonts.googleapis.com/css?family=Playfair+Display:900" rel="stylesheet" type="text/css" />
    <link href="http://fonts.googleapis.com/css?family=Alice:400,700" rel="stylesheet" type="text/css" />
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="css/style.css" />
</head>

<body>
    <div id="booking" class="section">
        <div class="section-center">
            <div class="container">
                <div class="row">
                    <div class="booking-form">
                        <div class="booking-bg">
                            <div class="form-header">
                                <h2>Make your reservation</h2>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate laboriosam numquam at</p>
                            </div>
                        </div>

                        <form method="POST" action="appointment.php">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Name</span>
                                    <input class="form-control" type="text" placeholder="Enter your Name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <span class="form-label">Phone Number</span>
                                    <input class="form-control" type="tel" placeholder="Enter Phone Number" name="phone" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span class="form-label">Email</span>
                                        <input class="form-control" type="email" placeholder="Enter Email" name="email" required>
                                    </div>
                                </div>

                                <?php
                                include('connection.php');
                                $q = "SELECT * FROM lawyer";
                                $result = mysqli_query($con, $q);
                                ?>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <span class="form-label">Lawyer</span>
                                        <select class="form-control" name="law" required>
                                            <option value="" selected hidden>Choose A Lawyer</option>
                                            <?php
                                            while ($data = mysqli_fetch_assoc($result)) {
                                                echo '<option value="' . $data['id'] . '">' . $data['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <span class="select-arrow"></span>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $qa="select id , available from lawyer";
                            $rows=mysqli_query($con,$qa);
                            ?>

                            <div class="form-group">
                                <span class="form-label">Availability</span>
                                <select class="form-control" name="avail" required>
                                    <option value="" selected hidden>Choose Availability</option>
                                    <?php
                                    while ($data = mysqli_fetch_assoc($rows)) {
                                        echo '<option value="' . $data['available'] . '">' . $data['available'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <span class="select-arrow"></span>
                            </div>
                            <div class="form-btn">
                                <button class="submit-btn" type="submit" name="submit">Check availability</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>