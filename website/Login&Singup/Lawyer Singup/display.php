<?php
include "../conn.php";

// Fetch data from the database
$query = "SELECT `name`, `last name`, `email`, `number`, `address`, `bar council`, `since`, `specialist`, `description`, `degree`, `university`, `language`, `available`, `image`, `about me` FROM lawyer";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lawyer Availability</title>
    <link rel="stylesheet" href="../styles1.css">
</head>
<body>
    <h1>Lawyer Availability</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row['name']);
            $lastName = htmlspecialchars($row['last name']);
            $email = htmlspecialchars($row['email']);
            $number = htmlspecialchars($row['number']);
            $address = htmlspecialchars($row['address']);
            $barCouncil = htmlspecialchars($row['bar council']);
            $since = htmlspecialchars($row['since']);
            $specialist = htmlspecialchars($row['specialist']);
            $description = htmlspecialchars($row['description']);
            $degree = htmlspecialchars($row['degree']);
            $university = htmlspecialchars($row['university']);
            $language = htmlspecialchars($row['language']);
            $availability = json_decode($row['available'], true);
            $image = htmlspecialchars($row['image']);
            $aboutMe = htmlspecialchars($row['about me']);

            echo "<div class='lawyer'>";
            echo "<p><strong>Name:</strong> $name $lastName</p>";
            echo "<p><strong>Email:</strong> $email</p>";
            echo "<p><strong>Number:</strong> $number</p>";
            echo "<p><strong>Address:</strong> $address</p>";
            echo "<p><strong>Bar Council Number:</strong> $barCouncil</p>";
            echo "<p><strong>Practicing Since:</strong> $since</p>";
            echo "<p><strong>Specialization:</strong> $specialist</p>";
            echo "<p><strong>Description:</strong> $description</p>";
            echo "<p><strong>Degree:</strong> $degree</p>";
            echo "<p><strong>University:</strong> $university</p>";
            echo "<p><strong>Languages Spoken:</strong> $language</p>";
            echo "<p><strong>About Me:</strong> $aboutMe</p>";
            if ($image) {
                echo "<p><strong>Profile Image:</strong><br><img src='uploads/$image' alt='Profile Image' width='150'></p>";
            }
            if ($availability) {
                $availabilityDisplay = [];
                foreach ($availability as $slot) {
                    $availabilityDisplay[] = $slot['day'] . ": " . $slot['start'] . " to " . $slot['end'];
                }
                $availabilityString = implode('<br>', $availabilityDisplay);
                echo "<p><strong>Availability:</strong><br> $availabilityString</p>";
            }
            echo "</div><hr>";
        }
    } else {
        echo "<p>No data found.</p>";
    }
    ?>
</body>
</html>
