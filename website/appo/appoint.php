<?php
include('connection.php');

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];
    $law=$_POST['law'];
    $avail=$_POST['avail'];
    $sql="insert into appointment(name,email,phone,lawyer,available) values('$name','$email','$phone','$law','$avail')";
    $res=mysqli_query($con,$sql);
    if($res){
        echo "<script>ok</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Booking Form HTML Template</title>

	<!-- Google font -->
	<link href="http://fonts.googleapis.com/css?family=Playfair+Display:900" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Alice:400,700" rel="stylesheet" type="text/css" />

	<!-- Bootstrap -->
	<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />

	<!-- Custom stlylesheet -->
	<link type="text/css" rel="stylesheet" href="css/style.css" />

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

</head>

<body>
	<div id="booking" class="section">
		<div class="section-center">
			<div class="container">
				<div class="row">
					<div class="booking-form">
						<div class="booking-bg">
							<div class="form-header">
								<h2>Make your reservation</h2>
								<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cupiditate laboriosam numquam at</p>
							</div>
						</div>


						<form method="POST">


							<div class="col-md-6">
								<div class="form-group">
									<span class="form-label">Name</span>
									<input class="form-control" type="text"  placeholder="Enter your Name" name="name" required>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<span class="form-label">Phone Number</span>
									<input class="form-control" type="tel" placeholder="Enter Phone Number" name="phone" required>
								</div>
							</div>

						

							<div class="row">
								
							<div class="col-md-6">
								<div class="form-group">
									<span class="form-label">Email</span>
									<input class="form-control" type="text" placeholder="Enter Phone Email" name="email" required>
								</div>
							</div>
                            <?php
                            $q="select * from lawyer";
                            $row=mysqli_query($con,$q);
                            ?>

							<div class="col-md-6">
								<div class="form-group">
									<span class="form-label">Lawyer</span>
									<select class="form-control" name="law" required>
                                        <?php
                                        while($data=mysqli_fetch_assoc($row)){
                                        ?>
										<option value="" selected hidden>Choose A Lawyer</option>
                                        <option value="<?php echo $data['id']?>"><?php echo $data['name']?></option>
                                        <?php
                                        }
                                        ?>
										
										
									</select>
									<span class="select-arrow"></span>
								</div>
	
							</div>
								
							</div>
                            <?php
                            $qa="select * from lawyer";
                            $rows=mysqli_query($con,$qa);
                            ?>
						    <?php
                            while($data=mysqli_fetch_assoc($rows)){
                            ?>
							<div class="form-group">
								<span class="form-label">available days</span>
								<select class="form-control" name="avail" required>	
                                    <option value="<?php echo $data['id']?>"><?php echo $data['day']?></option>

									<span class="form-label">available time</span>
								<select class="form-control" name="avail" required>	
                                    <option value="<?php echo $data['id']?>"><?php echo $data['Time']?></option>

                            <?php
                            }
                            ?>
									
								</select>
								<span class="select-arrow"></span>
							</div>
							<div class="form-btn">
								<button class="submit-btn" name="submit">Check availability</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>