<?php
include('connection.php');
session_start();

// Function to check if user is logged in and their type
function checkUserType($con)
{
	if (isset($_SESSION['email']) && isset($_SESSION['user_type'])) {
		$email = $_SESSION['email'];
		$user_type = $_SESSION['user_type'];

		if ($user_type == 'user') {
			$query = "SELECT id, name, email FROM user WHERE email='$email'";
		} else if ($user_type == 'lawyer') {
			$query = "SELECT id, name, email, image FROM lawyer WHERE email='$email'";
		}

		$result = $con->query($query);

		if ($result->num_rows > 0) {
			return $result->fetch_assoc();
		}
	}
	return null;
}

$user_info = checkUserType($con);
?>
<!DOCTYPE HTML>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Lawfirm Template</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />

	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content="" />
	<meta property="og:image" content="" />
	<meta property="og:url" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:description" content="" />
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet">

	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">

	<!-- Magnific Popup -->
	<link rel="stylesheet" href="css/magnific-popup.css">

	<!-- Owl Carousel  -->
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">
	<!-- Flexslider  -->
	<link rel="stylesheet" href="css/flexslider.css">
	<!-- Flaticons  -->
	<link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

	<!-- Theme style  -->
	<link rel="stylesheet" href="css/style.css">

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

</head>

<body>
	<div class="colorlib-loader"></div>

	<div id="page">
		<nav class="colorlib-nav" role="navigation">
			<div class="top-menu">
				<div class="container">
					<div class="row">
						<div class="col-md-2">
							<div id="colorlib-logo"><a href="index.php">Law<span>firm.</span></a></div>
						</div>
						<div class="col-md-10 text-right menu-1">
							<ul>
								<li class="active"><a href="index.php">Home</a></li>
								<li><a href="lawyer.php">Find a Lawyer</a></li>
								<li><a href="about.php">About</a></li>
								<li><a href="contact.php">Contact</a></li>
								<?php if ($user_info) : ?>
									<li class="has-dropdown">
										<a href="#"><?= $user_info['name'] ?></a>
										<ul class="dropdown">
											<?php if ($_SESSION['user_type'] == 'user') : ?>
												<li><a href="#"><?= $user_info['email'] ?></a></li>
											<?php elseif ($_SESSION['user_type'] == 'lawyer') : ?>
												<li><a href="update_law.php?id=<?= $user_info['id'] ?>">Profile</a></li>
											<?php endif; ?>
											<li><a href="../website/Login&Singup/logout.php">Log out</a></li>
										</ul>
									</li>
								<?php else : ?>
									<li><a href="Login&Singup/login_email.php"><i class="fa-solid fa-user"></i></a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</nav>
		<!-- Rest of the HTML content -->
</body>

</html>