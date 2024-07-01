<?php
include "../conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['dob'] = $_POST['dob'];
    header("Location: singup_law_pass.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="..//styles1.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <!-- <style>
        .input-groups .form-controls[type="date"] {
            -webkit-appearance: none;
            appearance: none;
            position: relative;
            z-index: 1;
        }
        .input-groups .form-controls[type="date"]::-webkit-calendar-picker-indicator {
            display: none;
        }
        .input-groups .calendar-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 2; /* Ensure it's clickable over the input */
            pointer-events: auto; /* Ensure it can be clicked */
        }
        .input-groups .input-group-text {
            background-color: transparent;
            border: none;
        }
    </style> -->
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5">
                <div class="left-section text-center mb-3">
                    <div class="logo">
                        <a href="../../index.php">Law<span>firm.</span></a>
                    </div>
                    <h1>Create a Lawyer Account</h1>
                    <p>Enter your Contact Information</p>
                    <p class="instructions">Please enter valid contact information. This will be used for account verification and notifications.</p>
                </div>
                <div class="right-section">
                    <form action="" method="POST">
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" id="dob" name="dob" required>
                            <div class="input-group-text">
                                <span class="calendar-icon" onclick="focusDateInput()">
                                    <i class="fa fa-calendar-alt"></i>
                                </span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary me-2" onclick="history.back()">Previous</button>
                        <button type="submit" class="btn btn-primary">Next</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
