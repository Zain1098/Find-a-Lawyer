<?php
include ('headerss.php');

// Ensure cat_id is present and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $cat_id = intval($_GET['id']);
} else {
    // Redirect or show error if cat_id is not valid
    echo "Invalid category ID.";
    exit;
}

// Fetch lawyers based on the category ID
$q = "SELECT lawyer.*, categorie.cat_name as cat_name FROM lawyer 
      JOIN categorie ON categorie.cat_id = lawyer.specialist 
      WHERE categorie.cat_id = $cat_id";

$res = mysqli_query($con, $q);
?>
<style>
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .card {
            flex: 0 1 calc(33.333% - 20px);
            margin: 10px;
            box-sizing: border-box;
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
            color: #bea34d;
            text-decoration: none;
            font-size: 1.2em;
        }
    </style>
<link rel="stylesheet" href="lawyer.css">
<div class="banner">
    <div class="content">
        <h2>Lawyers</h2>
        <p><a href="index.php" style="text-decoration: none; color: #fff;">Home</a> -> Lawyers by Category</p>
    </div>
</div>
<br><br>

<div class="row">
        <?php while ($data = mysqli_fetch_assoc($res)) { ?>
            <div class="colorlib-staff">
                <img src="../website/Login&Singup/Lawyer Singup/uploads/<?php echo $data['image']; ?>" alt="Image" style="width:90px; height:90px; border-radius:50%;">
                <h3><?php echo $data['name']; ?></h3>
                <strong class="role"><?php echo $data['cat_name']; ?></strong>
                <p><?php echo $data['degree']; ?></p>
                <ul class="colorlib-social-icons">
                    <!-- <li><a href="#"><i class="icon-facebook"></i></a></li>
                    <li><a href="#"><i class="icon-twitter"></i></a></li>
                    <li><a href="#"><i class="icon-dribbble"></i></a></li>
                    <li><a href="#"><i class="icon-github"></i></a></li> -->
                </ul>
                <a href="profile.php?id=<?php echo $data['id'] ?>" class="btn btn-primary">Veiw Profile</a>
            </div>
        <?php } ?>
    </div>

<?php
include ('footer.php');
?>