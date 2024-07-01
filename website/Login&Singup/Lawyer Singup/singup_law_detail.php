<?php
include "../conn.php";
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['bar_council_number'] = $_POST['bar_council_number'];
    $_SESSION['practicing_since'] = $_POST['practicing_since'];
    $_SESSION['specialization'] = $_POST['specialization'];
    $_SESSION['description'] = $_POST['description'];
    header("Location: singup_law_edu.php");
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
                <a href="../../index.php">Law<span>firm.</span></a>
            </div>
            <h1>Create a Lawyer Account</h1>
            <p>Enter your Professional Details</p>
            <p class="instructions">Please enter your bar council membership number, the year you started practicing, select your specialization, and provide a brief description.</p>
        </div>
        <div class="right-section">
            <form method="POST">
                <div class="input-group">
                    <input type="text" id="bar_council_number" name="bar_council_number" placeholder=" " required>
                    <label for="bar_council_number">Bar Council Membership Number</label>
                    <small class="condition">e.g., ABC123456</small>
                </div>
                <div class="input-group">
                    <input type="number" id="practicing_since" name="practicing_since" placeholder=" " required>
                    <label for="practicing_since">Practicing Since (Year)</label>
                    <small class="condition">e.g., 2005</small>
                </div>
                <div class="drop_cat">
                    <?php
                    $query = "SELECT * FROM categorie";
                    $result = mysqli_query($conn, $query);
                    ?>

                    <select name="specialization">
                        <option value="" selected>Select a specialist</option>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <option value="<?php echo $row['cat_id']; ?>"><?php echo $row['cat_name']; ?></option>
                        <?php } ?>
                    </select>
                    <label for="specialization">Specialization</label>
                </div>
                <div class="description">
                    <textarea id="description" name="description" placeholder=" " required></textarea>
                    <label for="description">Description</label>
                    <small class="condition">Provide a brief description of your professional background.</small>
                </div>
                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>

</html>