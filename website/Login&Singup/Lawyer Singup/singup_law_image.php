<?php
session_start();
include '../conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the upload directories exist
    $target_dir_profile = "uploads/";
    $target_dir_cover = "cover_images/";

    if (!is_dir($target_dir_profile)) {
        mkdir($target_dir_profile, 0777, true);
    }

    if (!is_dir($target_dir_cover)) {
        mkdir($target_dir_cover, 0777, true);
    }

    // Handle profile image upload
    $target_file_profile = $target_dir_profile . basename($_FILES["profile-image"]["name"]);
    $imageFileType_profile = strtolower(pathinfo($target_file_profile, PATHINFO_EXTENSION));

    // Validate the profile image
    $check_profile = getimagesize($_FILES["profile-image"]["tmp_name"]);
    if ($check_profile === false) {
        die("Profile image is not valid.");
    }

    // Validate profile image size
    if ($_FILES["profile-image"]["size"] > 5000000) {
        die("Sorry, your profile image is too large.");
    }

    // Upload the profile image
    if (!move_uploaded_file($_FILES["profile-image"]["tmp_name"], $target_file_profile)) {
        die("Sorry, there was an error uploading your profile image.");
    }

    // Handle cover image upload
    $target_file_cover = $target_dir_cover . basename($_FILES["cover-image"]["name"]);
    $imageFileType_cover = strtolower(pathinfo($target_file_cover, PATHINFO_EXTENSION));

    // Validate the cover image
    $check_cover = getimagesize($_FILES["cover-image"]["tmp_name"]);
    if ($check_cover === false) {
        die("Cover image is not valid.");
    }

    // Validate cover image size
    if ($_FILES["cover-image"]["size"] > 10000000) {
        die("Sorry, your cover image is too large.");
    }

    // Upload the cover image
    if (!move_uploaded_file($_FILES["cover-image"]["tmp_name"], $target_file_cover)) {
        die("Sorry, there was an error uploading your cover image.");
    }

    // Set session variables
    $_SESSION['profile-image'] = basename($_FILES["profile-image"]["name"]);
    $_SESSION['cover-image'] = basename($_FILES["cover-image"]["name"]);

    // Ensure required session variables are set
    $required_fields = [
        'first-name', 'last-name', 'email', 'number', 'address', 'dob', 'password',
        'bar_council_number', 'practicing_since', 'specialization', 'description',
        'degrees', 'universities', 'languages_spoken', 'days', 'times', 'fee', 'profile-image', 'cover-image', 'about-me'
    ];

    foreach ($required_fields as $field) {
        if (!isset($_SESSION[$field])) {
            die("Error: Missing required field $field.");
        }
    }

    // Ensure 'days' and 'times' are arrays before imploding
    $days = is_array($_SESSION['days']) ? implode(',', $_SESSION['days']) : $_SESSION['days'];
    $times = is_array($_SESSION['times']) ? implode(',', $_SESSION['times']) : $_SESSION['times'];

    // Retrieve data from session
    $firstName = $_SESSION['first-name'];
    $lastName = $_SESSION['last-name'];
    $email = $_SESSION['email'];
    $number = $_SESSION['number'];
    $address = $_SESSION['address'];
    $dob = $_SESSION['dob'];
    $password = $_SESSION['password'];
    $barCouncil = $_SESSION['bar_council_number'];
    $since = $_SESSION['practicing_since'];
    $specialist = $_SESSION['specialization'];
    $description = $_SESSION['description'];
    $degree = $_SESSION['degrees'];
    $university = $_SESSION['universities'];
    $language = $_SESSION['languages_spoken'];
    $fee = $_SESSION['fee'];
    $aboutMe = $_SESSION['about-me'];
    $profileImage = $_SESSION['profile-image'];
    $coverImage = $_SESSION['cover-image'];
    $_SESSION['days'] = explode(',', $daysString); // Assuming $daysString is a comma-separated string of days
    $_SESSION['times'] = explode(',', $timesString); // Assuming $timesString is a comma-separated string of times


    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO lawyer (`name`, `last name`, `email`, `dob`, `number`, `address`, `password`, `bar council`, `since`, `specialist`, `description`, `degree`, `university`, `language`, `day`, `Time`, `fee`, `image`, `about me`, `cover image`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssssssssssssss", $firstName, $lastName, $email, $dob, $number, $address, $password, $barCouncil, $since, $specialist, $description, $degree, $university, $language, $days, $times, $fee, $profileImage, $aboutMe, $coverImage);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Clear session data
    session_unset();
    session_destroy();

    header("location: ../login_email.php");
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
    <style>
        .file-input-wrapper,
        .cover-image-wrapper {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: border 0.3s ease;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            text-align: center;
        }

        .file-input-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 20px auto;
        }

        .file-input-wrapper img,
        .cover-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            border-radius: inherit;
        }

        .file-input-wrapper input,
        .cover-image-wrapper input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-input-wrapper:hover,
        .cover-image-wrapper:hover {
            border: 2px solid #007bff;
        }

        .file-input-wrapper::before,
        .cover-image-wrapper::before {
            content: attr(data-placeholder);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #999;
            font-size: 16px;
            font-weight: bold;
            pointer-events: none;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.8);
            text-align: center;
            display: block;
        }

        .cover-image-wrapper {
            width: 100%;
            height: 200px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-section">
            <div class="logo">
                <a href="../../../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a Lawyer Account</h1>
            <p>Upload your profile picture and cover image.</p>
        </div>
        <div class="right-section">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="file-input-wrapper" id="profile-wrapper" data-placeholder="Profile Image">
                    <img src="../images/profile-placeholder.png" alt="" id="profile-preview" class="hidden">
                    <input type="file" name="profile-image" accept="image/*" required onchange="loadProfilePreview(event)">
                </div>

                <div class="cover-image-wrapper" id="cover-wrapper" data-placeholder="Cover Image">
                    <img src="" alt="" id="cover-preview" class="hidden">
                    <input type="file" name="cover-image" accept="image/*" required onchange="loadCoverPreview(event)">
                </div>

                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Create Account</button>
            </form>
        </div>
    </div>

    <script>
        function loadProfilePreview(event) {
            var profilePreview = document.getElementById('profile-preview');
            profilePreview.src = URL.createObjectURL(event.target.files[0]);
            profilePreview.classList.remove('hidden');
            profilePreview.parentElement.removeAttribute('data-placeholder');
        }

        function loadCoverPreview(event) {
            var coverPreview = document.getElementById('cover-preview');
            coverPreview.src = URL.createObjectURL(event.target.files[0]);
            coverPreview.classList.remove('hidden');
            coverPreview.parentElement.removeAttribute('data-placeholder');
        }
    </script>
</body>

</html>