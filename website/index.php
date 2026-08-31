<?php
require_once __DIR__ . '/header.php';

// Dynamic stats for counters
$count_lawyers = 15;
$l_count_res = mysqli_query($con, "SELECT COUNT(*) as total FROM lawyer");
if ($l_count_res && $row = mysqli_fetch_assoc($l_count_res)) $count_lawyers = max(intval($row['total']), 15);

$count_appointments = 120;
$a_count_res = mysqli_query($con, "SELECT COUNT(*) as total FROM appointment");
if ($a_count_res && $row = mysqli_fetch_assoc($a_count_res)) $count_appointments = max(intval($row['total']), 120);

// Dynamic categories
$categories_res = mysqli_query($con, "SELECT * FROM categorie ORDER BY cat_id ASC LIMIT 6");

// Dynamic top lawyers
$lawyers_res = mysqli_query($con, "SELECT lawyer.*, categorie.cat_name FROM lawyer JOIN categorie ON categorie.cat_id = lawyer.specialist WHERE lawyer.status = 'active' ORDER BY lawyer.id DESC LIMIT 3");
?>

	<aside id="colorlib-hero" class="js-fullheight">
		<div class="flexslider js-fullheight">
			<ul class="slides">
		   	<li style="background-image: url(images/img_bg_1.jpg);">
		   		<div class="overlay-gradient"></div>
		   		<div class="container">
		   			<div class="row">
			   			<div class="col-md-8 col-md-offset-2 text-center js-fullheight slider-text">
			   				<div class="slider-text-inner">
			   					<h1>Trusted Advocates for Complex Legal Disputes</h1>
								<h2>Protecting your constitutional rights with strategic legal defense.</h2>
								<p>
									<a class="btn btn-primary btn-lg" href="lawyer.php"><i class="fa fa-search mr-1"></i> Find a Lawyer</a>
									<a class="btn btn-default btn-lg" href="appo/appoint.php" style="background:rgba(255,255,255,0.2); border-color:#fff; color:#fff; margin-left:10px;"><i class="fa fa-calendar-check mr-1"></i> Book Consultation</a>
								</p>
			   				</div>
			   			</div>
			   		</div>
		   		</div>
		   	</li>
		   	<li style="background-image: url(images/img_bg_2.jpg);">
		   		<div class="overlay-gradient"></div> 
		   		<div class="container">
		   			<div class="row">
			   			<div class="col-md-8 col-md-offset-2 text-center js-fullheight slider-text">
			   				<div class="slider-text-inner">
			   					<h1>Corporate & Commercial Law Expertise</h1>
								<h2>From startup contracts to multinational litigation & arbitration.</h2>
								<p>
									<a class="btn btn-primary btn-lg" href="lawyer.php?cat=3">Corporate Law Experts</a>
								</p>
			   				</div>
			   			</div>
			   		</div>
		   		</div>
		   	</li>
		   	<li style="background-image: url(images/img_bg_3.jpg);">
		   		<div class="overlay-gradient"></div>
		   		<div class="container">
		   			<div class="row">
			   			<div class="col-md-8 col-md-offset-2 text-center js-fullheight slider-text">
			   				<div class="slider-text-inner">
			   					<h1>Family, Custody & Civil Litigation Support</h1>
								<h2>Compassionate counsel during sensitive legal proceedings.</h2>
								<p>
									<a class="btn btn-primary btn-lg" href="lawyer.php?cat=1">Family Law Advocates</a>
								</p>
			   				</div>
			   			</div>
			   		</div>
		   		</div>
		   	</li>		   	
		  	</ul>
	  	</div>
	</aside>

	<div id="intro-bg">
		<div class="container">
			<div id="colorlib-intro">
				<div class="third-col">
					<span class="icon"><i class="fa fa-scale-balanced" style="font-size: 28px; color:#d97706;"></i></span>
					<h2>Need Legal Consultation?</h2>
					<p>Connect with verified High Court and District Bar advocates across Pakistan. Schedule confidential in-person or virtual legal appointments seamlessly.</p>
				</div>
				<div class="third-col third-col-color">
					<span class="icon"><i class="fa fa-phone" style="font-size: 28px; color:#ffffff;"></i></span>
					<h2>Helpline: (+92) 300-1234567</h2>
					<h2>Email: <a href="mailto:info@lawfirm.com" style="color:#ffffff;">info@lawfirm.com</a></h2>
					<p>Our client assistance team is available 6 days a week to guide you to the right legal specialist for your case.</p>
				</div>
			</div>
		</div>
	</div>

	<!-- Counters -->
	<div id="colorlib-counter" class="colorlib-counters" style="background-image: url(images/img_bg_3.jpg);" data-stellar-background-ratio="0.5">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-3 text-center animate-box">
					<span class="icon"><i class="flaticon-lawyer-1"></i></span>
					<span class="colorlib-counter js-counter" data-from="0" data-to="<?php echo $count_lawyers; ?>" data-speed="2000" data-refresh-interval="50"></span>
					<span class="colorlib-counter-label">Verified Advocates</span>
				</div>
				<div class="col-md-3 text-center animate-box">
					<span class="icon"><i class="flaticon-courthouse"></i></span>
					<span class="colorlib-counter js-counter" data-from="0" data-to="2500" data-speed="2000" data-refresh-interval="50"></span>
					<span class="colorlib-counter-label">Clients Advised</span>
				</div>
				<div class="col-md-3 text-center animate-box">
					<span class="icon"><i class="flaticon-libra"></i></span>
					<span class="colorlib-counter js-counter" data-from="0" data-to="<?php echo $count_appointments; ?>" data-speed="2000" data-refresh-interval="50"></span>
					<span class="colorlib-counter-label">Appointments Booked</span>
				</div>
				<div class="col-md-3 text-center animate-box">
					<span class="icon"><i class="flaticon-police-badge"></i></span>
					<span class="colorlib-counter js-counter" data-from="0" data-to="98" data-speed="2000" data-refresh-interval="50"></span>
					<span class="colorlib-counter-label">% Client Satisfaction</span>
				</div>
			</div>
		</div>
	</div>

	<!-- Practice Areas -->
	<div id="colorlib-practice">
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading">
					<h2>Practice Areas</h2>
					<p>Explore specialized practice areas where our registered advocates provide strategic representation and dispute advisory.</p>
				</div>
			</div>
			<div class="row">
				<?php 
				$iconMap = [
					1 => 'flaticon-lawyer-1',
					2 => 'flaticon-police-badge',
					3 => 'flaticon-courthouse',
					4 => 'flaticon-libra',
					5 => 'flaticon-courthouse',
					6 => 'flaticon-libra'
				];
				if ($categories_res && mysqli_num_rows($categories_res) > 0):
					while($data = mysqli_fetch_assoc($categories_res)): 
						$icon = $iconMap[$data['cat_id']] ?? 'flaticon-courthouse';
				?>
					<div class="col-md-4 text-center animate-box">
						<div class="services" style="padding:30px 20px; min-height: 240px; border-radius: 8px; transition: transform 0.3s ease;">
							<span class="icon">
								<i class="<?php echo $icon; ?>"></i>
							</span>
							<div class="desc">
								<h3><a href="lawyer.php?cat=<?php echo $data['cat_id']; ?>"><?php echo htmlspecialchars($data['cat_name']); ?></a></h3>
								<p><?php echo htmlspecialchars(!empty($data['cat_desc']) ? $data['cat_desc'] : 'Expert legal consultation and representation for this specialized field of law.'); ?></p>
								<p><a href="lawyer.php?cat=<?php echo $data['cat_id']; ?>" style="color:#d97706; font-weight:600;">View Lawyers <i class="icon-arrow-right"></i></a></p>
							</div>
						</div>
					</div>
				<?php 
					endwhile;
				endif; 
				?>
			</div>
			<div class="row">
				<div class="col-md-12 text-center animate-box mt-3">
					<p><a class="btn btn-primary btn-lg btn-learn" href="lawyer.php">Browse All Advocates <i class="icon-arrow-right"></i></a></p>
				</div>
			</div>
		</div>
	</div>

	<!-- Callout -->
	<div id="colorlib-started" style="background-image:url(images/img_bg_2.jpg);" data-stellar-background-ratio="0.5">
		<div class="overlay"></div>
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading colorlib-heading2">
					<h2>Are You a Licensed Advocate?</h2>
					<p>Join our premier legal directory to manage your consultation schedule, expand your reach, and connect with prospective clients online.</p>
					<p><a href="Login&Singup/Lawyer%20Singup/signup_lawyer.php" class="btn btn-primary btn-lg"><i class="fa fa-user-plus mr-1"></i> Register as a Lawyer</a></p>
				</div>
			</div>
		</div>
	</div>

	<!-- Top Attorneys Section -->
	<div id="colorlib-about">
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading">
					<h2>Featured Advocates</h2>
					<p>Meet our verified attorneys recognized for high litigation standards, courtroom victories, and ethical legal counsel.</p>
				</div>
			</div>
			<div class="row">
				<?php if ($lawyers_res && mysqli_num_rows($lawyers_res) > 0): ?>
					<?php while ($law = mysqli_fetch_assoc($lawyers_res)): ?>
						<div class="col-md-4 col-sm-4 text-center animate-box" data-animate-effect="fadeIn">
							<div class="colorlib-staff" style="padding: 25px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); background:#fff; height:100%;">
								<img src="uploads/<?php echo htmlspecialchars(!empty($law['image']) ? $law['image'] : 'default_lawyer.png'); ?>" alt="<?php echo htmlspecialchars($law['name']); ?>" style="width:110px; height:110px; border-radius:50%; object-fit:cover; margin-bottom:15px; border:3px solid #d97706;" onerror="this.src='images/lawyer 1.jpg'">
								<h3 style="margin-bottom:4px; font-weight:700;"><?php echo htmlspecialchars($law['name'] . ' ' . ($law['last name'] ?? '')); ?></h3>
								<strong class="role" style="color:#d97706; display:block; margin-bottom:10px; font-size:13px;"><?php echo htmlspecialchars($law['cat_name']); ?></strong>
								<p style="font-size:13px; color:#64748b; line-height:1.6; min-height:60px;">
									<?php echo htmlspecialchars(!empty($law['description']) ? (substr($law['description'], 0, 95) . '...') : 'Licensed advocate dedicated to safeguarding client interests and constitutional rights.'); ?>
								</p>
								<div style="margin: 15px 0;">
									<span style="font-weight:700; color:#059669; font-size:15px;">PKR <?php echo number_format($law['fee']); ?> / session</span>
								</div>
								<div>
									<a href="profile.php?id=<?php echo $law['id']; ?>" class="btn btn-default btn-sm" style="margin-right:6px;">View Profile</a>
									<a href="appo/appoint.php?id=<?php echo $lawyer['id'] ?? $law['id']; ?>" class="btn btn-primary btn-sm">Book Session</a>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php
require_once __DIR__ . '/footer.php';
?>