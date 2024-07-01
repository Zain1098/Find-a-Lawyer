<?php
include('header.php');
include('connection.php');

// Check if 'id' parameter is set in the URL
if(isset($_GET['id'])) {
// Fetch details for a specific lawyer based on 'id'
$id = $_GET['id'];
$sql = "SELECT *
FROM lawyer
JOIN categorie ON categorie.cat_id = lawyer.specialist
WHERE lawyer.id = '$id';";
$res = mysqli_query($con, $sql);
if(mysqli_num_rows($res) > 0) {
$data = mysqli_fetch_assoc($res);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="profile.css">
    <style>
        @media (max-width:800px) {
    .container-fluid img {
        margin: 20px;

    }
}
    

        .profile {
            display: flex;
        }

        .profile h1 {
            font-family: "Roboto", sans-serif;
            font-weight: 300;
            font-style: normal;
            z-index: 1;
            padding-left: 20px;
            /* letter-spacing: 1px; */
            padding-top: 5px;
        }

        .profile .ex {
            font-family: "Roboto", sans-serif;
            font-weight: 300;
            font-style: normal;
            padding-left: 30px;
            font-size: 17px;
            padding-top: 1%;
            padding-top: 1.3%;
        }

        .bio {
            font-family: "PT Sans", sans-serif;
            font-weight: 400;
            font-style: normal;
            /* height: 400px; */
            width: 900px;
            position: relative;
            display: block;
            padding-left: 190px;
            margin-top: -50px;
            overflow-wrap: break-word;
        }

        .add {
            /* height: 400px; */
            width: 900px;
            position: relative;
            display: block;
            padding-left: 190px;
            margin-top: -8px;
            overflow-wrap: break-word;
        }

        @media (max-width:1300px) {
            .profile {
                display: block;
            }

            .profile h1 {
                padding-left: 50px;
                padding-top: 2%;
            }

            .profile .ex {
                padding-left: 50px;
                padding-top: 1px;
                padding-bottom: 1px;
            }

            .bio {
                width: auto;
                overflow-wrap: break-word;
                padding-top: 20px;
                padding-left: 50px;

            }

            .add {
                width: auto;
                overflow-wrap: break-word;
                padding-top: 2px;
                padding-left: 50px;

            }
        }

        .image {
            position: relative;
            border-radius: 50%;
            width: 130px;
            height: 130px;
            overflow: hidden;
            z-index: 2;
            margin-top: -25px;
            margin-left: 40px;
        }

        @media (max-width: 1300px) {
            .image {
                margin-left: 35px;
            }

        }

        .image img {
            width: 130px;
            height: 130px;
        }

        .about {
            /* margin: auto; */
            margin-top: 5%;
            max-width: 850px;
            height: auto;
            /* border: 1px solid rgb(233, 233, 233); */
            box-shadow: none;
            border-radius: 5px;
        }

        .about h2 {
            font-size: 40px;
            padding-left: 40px;
            padding-top: 5px;
            padding-bottom: 0;
            font-family: "Crimson Text", serif;
            font-weight: 400;
            font-style: normal;
        }

        .about p {
            overflow-wrap: break-word;
            padding-left: 40px;
            padding-right: 40px;
        }

        .education {
            /* margin: auto; */
            margin-top: 5%;
            max-width: 1000px;
            height: auto;
            /* border: 1px solid rgb(233, 233, 233); */
            box-shadow: none;
            border-radius: 5px;
        }

        .education h2 {
            font-size: 40px;
            padding-left: 30px;
            padding-top: 5px;
            padding-bottom: 0;
            font-family: "Crimson Text", serif;
            font-weight: 400;
            font-style: normal;
        }

        .education ul {
            /* overflow-wrap: break-word; */
            padding-left: 60px;
        }

        .box {
            position: relative;
            z-index: 1;
        }

        .appoint {
            z-index: 2;
            margin-right: 200px;
            float: right;
            width: 200px;
            height: 60px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: 500;
            text-align: center;
            letter-spacing: 1px;
            text-decoration: none;
            color: #000;
            background: transparent;
            cursor: pointer;
            transition: ease-out 0.5s;
            border: 2px solid #000;
            color: #fff;
            background: transparent;
            cursor: pointer;
            transition: ease-out 0.5s;
            border: 2px solid #fff;
            border-radius: 10px;
            box-shadow: inset 0 0 0 0 #ffc300;
        }

        .appoint:hover {
            color: #000;
            box-shadow: inset 0 -100px 0 0 #ffc300;
            border: 2px solid #ffc300;
        }

        .appoint:active {
            transform: scale(0.9);
        }

        @media (max-width:900px) {
            .appoint {
                float: none;
                margin-right: 0;
                margin-left: 40px;
            }
        }
    
    </style>
</head>
<body>
    <!-- HTML for displaying detailed lawyer information -->
<div class="box">
    <div class="container-fluid" style="width: 500px; margin-left: 0px;">
        <img src="<?php echo $data['cover image']; ?>" class="img-fluid" id="ban" alt="..." style="border-radius: 5px; max-width:800px; margin-left:0px;">
    </div>
    <div class="profile">
        <div class="image"><img src="<?php echo $data['image'] ?>" alt="" style="scale: 1.1;"></div>
        <h1><?php echo $data['name'] ?> <?php echo $data['last name'] ?></h1>
        <p class="ex "><?php echo $data['since'] ?>yrs Experience</p><br>
    </div>

    <p class="bio"><?php echo $data['cat_name'] ?></p>

    <p class="add "><?php echo $data['address'] ?></p>
    <a href=""><button class="appoint">Appointment</button></a>

    <div class="about">
        <h2>About</h2>
        <p><?php echo $data['about me'] ?></p>
    </div><br>

    <div class="education">
        <h2>Services</h2>
        <ul>
            <li><?php echo $data['description'] ?></li>
        </ul>
    </div><br><br>

    <div class="education">
        <h2>Available Timings</h2>
        <ul>
            <li><?php echo $data['available'] ?></li>
        </ul>
    </div><br><br>

    <div class="education">
        <h2>Education</h2>
        <ul>
            <li>I have got the law degree from <?php echo $data['university'] ?></li>
        </ul>
    </div><br><br><br>
</div>
</body>
</html>





<?php
 } else {
    echo "<p>No lawyer found with ID: $id</p>";
}
} else {
// Display a list of lawyers or handle other cases
// Example: Fetch list of lawyers and display links to their profiles
}
include('footer.php');
?>