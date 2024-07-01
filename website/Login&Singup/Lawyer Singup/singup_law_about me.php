<?php
include "../conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['languages_spoken'] = $_POST['languages_spoken'];
    $_SESSION['about-me'] = $_POST['about-me'];
    header("Location: singup_law_image.php"); 
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
            <p>Share the languages you speak and provide a comprehensive overview of your professional background. This helps us better understand your capabilities and experience as a lawyer.</p>
            <p class="instructions">Please write a detailed description of your life and experience as a lawyer. Include information about your education, career achievements, and personal interests.</p>
        </div>
        <div class="right-section">
            <form method="POST">
                <div class="input-group">
                    <select id="languages_spoken" name="languages_spoken" class="custom-select" required>
                        <option value="" disabled selected>Select Languages Spoken</option>
                        <?php
                        $languages = [
                            "English", "Spanish", "French", "German", "Chinese", "Japanese", "Korean", "Italian", "Russian", "Portuguese", "Hindi", "Arabic", "Bengali", "Urdu", "Indonesian", "Turkish", "Vietnamese", "Thai", "Polish", "Dutch", "Swedish", "Greek", "Hebrew", "Norwegian", "Danish", "Finnish", "Czech", "Hungarian", "Romanian", "Slovak", "Bulgarian", "Croatian", "Serbian", "Slovenian", "Lithuanian", "Latvian", "Estonian", "Maltese", "Icelandic", "Irish", "Welsh", "Scottish Gaelic", "Basque", "Catalan", "Galician"
                        ];

                        foreach ($languages as $language) {
                            echo '<option value="' . htmlspecialchars($language) . '">' . htmlspecialchars($language) . '</option>';
                        }
                        ?>
                    </select>
                    <label for="languages_spoken" class="custom-label">Languages Spoken</label>
                </div>

                <div class="input-group">
                    <textarea id="about-me" name="about-me" placeholder=" " rows="5" required></textarea>
                    <label for="about-me">About Me</label>
                    <small class="condition">Describe your life and experience as a lawyer. Include details about your education, career achievements, and personal interests.</small>
                </div>
                <button type="button" class="previous-button" onclick="history.back()">Previous</button>
                <button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>

</html>
