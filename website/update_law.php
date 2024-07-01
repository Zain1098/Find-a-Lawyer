<?php
session_start();
include 'connection.php';

$Id = $_GET['id'];
$query = "SELECT * FROM lawyer WHERE id='$Id'";
$res = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($res);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir_profile = "uploads/";
    $target_dir_cover = "cover_images/";

    if (!is_dir($target_dir_profile)) {
        mkdir($target_dir_profile, 0777, true);
    }

    if (!is_dir($target_dir_cover)) {
        mkdir($target_dir_cover, 0777, true);
    }

    $profileImage = $user['image'];
    if (!empty($_FILES["profile_image"]["name"])) {
        $target_file_profile = $target_dir_profile . basename($_FILES["profile_image"]["name"]);
        $imageFileType_profile = strtolower(pathinfo($target_file_profile, PATHINFO_EXTENSION));

        $check_profile = getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check_profile !== false && $_FILES["profile_image"]["size"] <= 5000000) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file_profile)) {
                $profileImage = basename($_FILES["profile_image"]["name"]);
            }
        }
    }

    $coverImage = $user['cover_image'];
    if (!empty($_FILES["cover_image"]["name"])) {
        $target_file_cover = $target_dir_cover . basename($_FILES["cover_image"]["name"]);
        $imageFileType_cover = strtolower(pathinfo($target_file_cover, PATHINFO_EXTENSION));

        $check_cover = getimagesize($_FILES["cover_image"]["tmp_name"]);
        if ($check_cover !== false && $_FILES["cover_image"]["size"] <= 10000000) {
            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file_cover)) {
                $coverImage = basename($_FILES["cover_image"]["name"]);
            }
        }
    }

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $barCouncil = $_POST['bar_council'];
    $since = $_POST['since'];
    $specialist = $_POST['specialist'];
    $description = $_POST['description'];
    $degree = $_POST['degree'];
    $university = $_POST['university'];
    $language = $_POST['language'];
    $days = $_POST['days'];
    $times = $_POST['times'];
    $fee = $_POST['fee'];
    $aboutMe = $_POST['about_me'];

    $stmt = $conn->prepare("UPDATE lawyer SET `first_name`=?, `last_name`=?, `email`=?, `number`=?, `address`=?, `bar_council`=?, `since`=?, `specialist`=?, `description`=?, `degree`=?, `university`=?, `language`=?, `days`=?, `times`=?, `fee`=?, `image`=?, `about_me`=?, `cover_image`=? WHERE id=?");
    $stmt->bind_param("ssssssssssssssssssi", $firstName, $lastName, $email, $number, $address, $barCouncil, $since, $specialist, $description, $degree, $university, $language, $days, $times, $fee, $profileImage, $aboutMe, $coverImage, $Id);

    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Lawyer Details</title>
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <style>
        body {
            background-color: #f4f4f9;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 20px;
        }

        .left-section, .right-section {
            width: 50%;
        }

        .left-section img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .right-section {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-submit {
            display: flex;
            justify-content: space-between;
        }

        .form-submit input[type="submit"],
        .form-submit input[type="reset"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .form-submit input[type="submit"]:hover,
        .form-submit input[type="reset"]:hover {
            background-color: #0056b3;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="images/blojdhswvfjnw-258981062.jpg" alt="Profile Image">
        </div>
        <div class="right-section">
            <form method="POST" class="register-form" id="register-form" enctype="multipart/form-data">
                <h2>Lawyer Update Form</h2>
                <div class="form-group">
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo $user['name']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo $user['last name']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="number">Phone:</label>
                    <input type="text" name="number" id="number" value="<?php echo $user['number']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" value="<?php echo $user['address']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="bar_council">Bar Council:</label>
                    <input type="text" name="bar_council" id="bar_council" value="<?php echo $user['bar council']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="since">Practicing Since:</label>
                    <input type="text" name="since" id="since" value="<?php echo $user['since']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="specialist">Specialist:</label>
                    <input type="text" name="specialist" id="specialist" value="<?php echo $user['specialist']; ?>" required/>
                </div>  <div class="form-group">
                <label for="specialist">Specialist Category</label>
                <select name="specialist" id="specialist" class="form-control">
                    <option value="">Select Category</option>
                    <?php
                    while ($data = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $data['cat_id'] . '">' . $data['cat_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <input type="text" name="description" id="description" value="<?php echo $user['description']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="degree">Degree:</label>
                    <input type="text" name="degree" id="degree" value="<?php echo $user['degree']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="university">University:</label>
                    <input type="text" name="university" id="university" value="<?php echo $user['university']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="language">Language:</label>
                    <input type="text" name="language" id="language" value="<?php echo $user['language']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="days">Available Days:</label>
                    <input type="day" name="days" id="days" value="<?php echo $user['day']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="times">Available Times:</label>
                    <input type="time" name="times" id="times" value="<?php echo $user['time']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="fee">Fee:</label>
                    <input type="text" name="fee" id="fee" value="<?php echo $user['fee']; ?>" required/>
                </div>
                <div class="form-group">
                    <label for="profile_image">Profile Image:</label>
                    <input type="file" name="profile_image" id="profile_image"/>
                    <img src="<?php echo $user['image']; ?>" alt="Profile Image" width="70px">
                </div>
                <div class="form-group">
                    <label for="cover_image">Cover Image:</label>
                    <input type="file" name="cover_image" id="cover_image"/>
                    <img src="<?php echo $user['cover image']; ?>" alt="Cover Image">
                </div>
                <div class="form-group">
                    <label for="about_me">About Me:</label>
                    <textarea name="about_me" id="about_me" required><?php echo $user['about me']; ?></textarea>
                </div>
                <div class="form-submit">
                    <input type="reset" value="Reset All" class="submit" name="reset" id="reset" />
                    <input type="submit" value="Update" class="submit" name="submit" id="submit" />
                </div>
            </form>
        </div>
    </div>
</body>
</html>

