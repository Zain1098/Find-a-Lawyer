
Edit
Copy code
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $days = [];
    $time = "";

    foreach ($_POST['availability'] as $day => $enabled) {
        if ($enabled == "1") {
            $days[] = $day;
        }
    }

    if (isset($_POST['start_time']) && isset($_POST['end_time'])) {
        $start_time = date("h:i A", strtotime($_POST['start_time']));
        $end_time = date("h:i A", strtotime($_POST['end_time']));
        $times = $start_time . ' to ' . $end_time;
    }

    $_SESSION['days'] = $days;
    $_SESSION['times'] = $times;
    $_SESSION['fee'] = $_POST['fee'];
    
    header("Location: singup_law_about me.php"); // Redirect to the next step
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="../styles1.css">
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <a href="../../../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a Lawyer Account</h1>
            <p>Enter your Professional Background</p>
            <p class="instructions">Please enter your availability.</p>
        </div>
        <div class="right-section">
            <form action="" method="POST">
                <div class="input-group">
                    <input type="number" id="fee" name="fee" placeholder=" " required>
                    <label for="fee">Fee</label>
                </div>

                <div class="availability-section">
                    <h2>Availability</h2>
                    <?php
                    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                    foreach ($days as $day) {
                        echo '<div class="day-group">';
                        echo '<label>' . $day . '</label>';
                        echo '<input type="checkbox" name="availability[' . $day . ']" value="1">';
                        echo '</div>';
                    }
                    ?>
                    <div class="time-group">
                        <label>Start Time</label>
                        <input type="time" name="start_time" required>
                        <label>End Time</label>
                        <input type="time" name="end_time" required>
                    </div>
                </div>
                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>

</html>