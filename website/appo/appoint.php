<?php
include('connection.php');

// Fetch lawyer details and availability
$lawyer = [];
$availability_res = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $q = "SELECT * FROM lawyer WHERE id = '$id'";
    $result = mysqli_query($con, $q);

    if ($result && mysqli_num_rows($result) > 0) {
        $lawyer = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Lawyer not found');</script>";
    }

    // Fetch availability for the specific lawyer
    // $availability_sql = "SELECT * FROM availability WHERE lawyer_id = '$id'";
    // $availability_res = mysqli_query($con, $availability_sql);
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $lawyer_name = $_POST['lawyer'];
    $avail = $_POST['avail'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("INSERT INTO appointment (name, email, phone, lawyer, available) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $lawyer_name, $avail);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully');</script>";
    } else {
        echo "<script>alert('Error booking appointment');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Booking Form</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:900|Alice:400,700" rel="stylesheet" type="text/css" />

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />

    <!-- Custom Stylesheet -->
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
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                            </div>
                        </div>
                        <form method="POST" class="form-horizontal">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phone" class="col-sm-2 control-label">Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lawyer" class="col-sm-2 control-label">Lawyer</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="lawyer" name="lawyer" placeholder="Enter Lawyer's Name" required value="<?php echo htmlspecialchars($lawyer['name'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="avail" class="col-sm-2 control-label">Available Day</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="avail" name="avail" required>
                                        <option value="" selected hidden>Choose a Day</option>
                                        <?php
                                        if ($availability_res) {
                                            mysqli_data_seek($availability_res, 0); // Reset pointer
                                            while ($availability = mysqli_fetch_assoc($availability_res)) {
                                                echo '<option value="' . htmlspecialchars($availability['day']) . '">' . htmlspecialchars($availability['day']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="time" class="col-sm-2 control-label">Available Time</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="time" name="time" required>
                                        <option value="" selected hidden>Choose a Time</option>
                                        <?php
                                        if ($availability_res) {
                                            mysqli_data_seek($availability_res, 0); // Reset pointer
                                            while ($availability = mysqli_fetch_assoc($availability_res)) {
                                                echo '<option value="' . htmlspecialchars($availability['time']) . '">' . htmlspecialchars($availability['time']) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="submit">Check availability</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
