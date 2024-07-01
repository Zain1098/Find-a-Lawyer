<?php
include('header.php');
include('connection.php');

$q = "SELECT lawyer.*, categorie.cat_name FROM lawyer JOIN categorie ON categorie.cat_id = lawyer.specialist";
$res = mysqli_query($con, $q);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find A Lawyer</title>
    <link rel="stylesheet" href="lawyer.css">
    <style>
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around; /* Space evenly for the card layout */
        }

        .card {
            flex: 0 1 calc(33.333% - 20px); /* 3 cards per row with spacing */
            margin: 10px;
            box-sizing: border-box;
        }

        .colorlib-staff img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 5px;
        }

        .colorlib-staff {
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            border-radius: 10px;
        }

        .colorlib-staff h3 {
            margin: 10px 0;
            font-size: 1.2em;
        }

        .colorlib-staff .role {
            font-size: 1em;
            color: #888;
        }

        .colorlib-staff ul {
            list-style: none;
            padding: 0;
            margin: 10px 0 0;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .colorlib-staff ul li {
            display: inline;
        }

        .colorlib-staff ul li a {
            color: #007bff;
            text-decoration: none;
            font-size: 1.2em;
        }
    </style>
</head>

<body>
    <div class="banner">
        <div class="content">
            <h2>Find A Lawyer</h2>
            <p><a href="index.php" style="text-decoration: none; color: #fff;">Home</a> -> Find A Lawyer</p>
        </div>
    </div><br><br>
    <center>
        <div class="input-wrapper">
            <button class="icon" style="display:block;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" height="25px" width="25px">
                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="1.5" stroke="#fff" d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"></path>
                    <path stroke-linejoin="round" stroke-linecap="round" stroke-width="1.5" stroke="#fff" d="M22 22L20 20"></path>
                </svg>
            </button>
            <input placeholder="search.." class="input" name="text" type="text">
        </div><br><br>
    </center>
    <div class="row">
        <?php while ($data = mysqli_fetch_assoc($res)) { ?>
            <a href="update_law.php?id=<?php echo $data['id']; ?>" class="card">
                <div class="colorlib-staff">
                    <img src="<?php echo $data['image']; ?>" alt="Image">
                    <h3><?php echo $data['name']; ?></h3>
                    <strong class="role"><?php echo $data['cat_name']; ?></strong>
                    <p><?php echo $data['degree']; ?></p>
                    <ul class="colorlib-social-icons">
                        <li><a href="#"><i class="icon-facebook"></i></a></li>
                        <li><a href="#"><i class="icon-twitter"></i></a></li>
                        <li><a href="#"><i class="icon-dribbble"></i></a></li>
                        <li><a href="#"><i class="icon-github"></i></a></li>
                    </ul>
                </div>
            </a>
        <?php } ?>
    </div>
</body>

</html>

<?php
include('footer.php');
?>
