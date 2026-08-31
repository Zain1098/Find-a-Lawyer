<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/includes/auth.php';

$current_user_type = getCurrentUserType();
$current_user_name = getCurrentUserName();
$current_user_email = getCurrentUserEmail();
$current_user_id = getCurrentUserId();
$current_user_image = getCurrentUserImage();

// Active page helper
$current_page = basename($_SERVER['PHP_SELF']);

// Pending requests counter for lawyer
$lawyer_pending_count = 0;
if ($con && $current_user_type === 'lawyer' && $current_user_id) {
    $lp_res = @mysqli_query($con, "SELECT COUNT(*) as pending_total FROM appointment WHERE lawyer = " . intval($current_user_id) . " AND status = 'pending'");
    if ($lp_res && $lp_row = mysqli_fetch_assoc($lp_res)) {
        $lawyer_pending_count = intval($lp_row['pending_total']);
    }
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Lawfirm - Find Top Verified Advocates & Book Consultations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Find verified legal advocates, attorneys, and law practitioners for consultation across Pakistan." />
    <meta name="keywords" content="lawyer, attorney, advocate, law firm, legal advice, appointment booking, corporate lawyer, family lawyer, criminal defense" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- Icomoon Icon Fonts-->
    <link rel="stylesheet" href="css/icomoon.css">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <!-- Flexslider -->
    <link rel="stylesheet" href="css/flexslider.css">
    <!-- Flaticons -->
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Modernizr JS -->
    <script src="js/modernizr-2.6.2.min.js"></script>

    <style>
        .profile-nav-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #d97706;
            vertical-align: middle;
            margin-right: 6px;
        }
        .colorlib-nav .dropdown {
            background: #ffffff !important;
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
            border: 1px solid #e2e8f0 !important;
            min-width: 230px !important;
        }
        .colorlib-nav .dropdown li a {
            color: #1e293b !important;
            padding: 10px 18px !important;
            font-size: 13px !important;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }
        .colorlib-nav .dropdown li:last-child a {
            border-bottom: none;
        }
        .colorlib-nav .dropdown li a:hover {
            background-color: #f8fafc !important;
            color: #d97706 !important;
            padding-left: 22px !important;
        }
        .user-nav-badge {
            background: rgba(217, 119, 6, 0.15);
            color: #d97706 !important;
            padding: 4px 12px !important;
            border-radius: 20px;
            font-weight: 600;
        }
        .pending-counter-badge {
            background: #ef4444;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 4px;
        }
    </style>
</head>
<body>
    <div class="colorlib-loader"></div>
    <div id="page">
    <nav class="colorlib-nav" role="navigation">
        <div class="top-menu">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div id="colorlib-logo"><a href="index.php">Law<span>firm.</span></a></div>
                    </div>
                    <div class="col-md-9 text-right menu-1">
                        <ul>
                            <li class="<?php echo ($current_page === 'index.php') ? 'active' : ''; ?>"><a href="index.php">Home</a></li>
                            <li class="<?php echo ($current_page === 'lawyer.php' || $current_page === 'cat_wise_lawyer.php') ? 'active' : ''; ?>"><a href="lawyer.php">Find a Lawyer</a></li>
                            <li class="<?php echo ($current_page === 'appoint.php') ? 'active' : ''; ?>"><a href="appo/appoint.php">Book Appointment</a></li>
                            
                            <?php if ($current_user_type === 'lawyer'): ?>
                                <li class="<?php echo ($current_page === 'lawyer_appointments.php') ? 'active' : ''; ?>">
                                    <a href="lawyer_appointments.php" style="color:#d97706; font-weight:700;">
                                        <i class="fa fa-calendar-check mr-1"></i> Client Requests
                                        <?php if ($lawyer_pending_count > 0): ?>
                                            <span class="pending-counter-badge"><?php echo $lawyer_pending_count; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php elseif ($current_user_type === 'user'): ?>
                                <li class="<?php echo ($current_page === 'my_appointments.php') ? 'active' : ''; ?>">
                                    <a href="my_appointments.php"><i class="fa fa-clock-rotate-left mr-1"></i> My Consultations</a>
                                </li>
                            <?php else: ?>
                                <li class="<?php echo ($current_page === 'my_appointments.php') ? 'active' : ''; ?>">
                                    <a href="my_appointments.php">Track Booking</a>
                                </li>
                            <?php endif; ?>

                            <li class="<?php echo ($current_page === 'about.php') ? 'active' : ''; ?>"><a href="about.php">About</a></li>
                            <li class="<?php echo ($current_page === 'contact.php') ? 'active' : ''; ?>"><a href="contact.php">Contact</a></li>

                            <?php if ($current_user_type): ?>
                                <li class="has-dropdown">
                                    <a href="#" class="user-nav-badge">
                                        <?php if ($current_user_type === 'lawyer' && !empty($current_user_image)): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($current_user_image); ?>" alt="Avatar" class="profile-nav-img" onerror="this.src='images/lawyer 1.jpg'">
                                        <?php else: ?>
                                            <i class="fa fa-user-circle mr-1"></i>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars(substr($current_user_name, 0, 15)); ?> <i class="fa fa-angle-down ml-1" style="font-size:11px;"></i>
                                    </a>
                                    <ul class="dropdown">
                                        <?php if ($current_user_type === 'user'): ?>
                                            <li class="px-3 py-2 text-muted font-weight-bold" style="font-size:11px;"><?php echo htmlspecialchars($current_user_email); ?></li>
                                            <li><a href="my_appointments.php"><i class="fa fa-calendar-check mr-2 text-primary"></i> My Consultations</a></li>
                                            <li><a href="appo/appoint.php"><i class="fa fa-calendar-plus mr-2 text-success"></i> Book New Consultation</a></li>
                                        <?php elseif ($current_user_type === 'lawyer'): ?>
                                            <li class="px-3 py-2 text-muted font-weight-bold" style="font-size:11px;"><?php echo htmlspecialchars($current_user_email); ?></li>
                                            <li><a href="lawyer_appointments.php"><i class="fa fa-calendar-days mr-2 text-primary"></i> Client Appointments <?php if ($lawyer_pending_count > 0) echo "($lawyer_pending_count)"; ?></a></li>
                                            <li><a href="profile.php?id=<?php echo $current_user_id; ?>"><i class="fa fa-eye mr-2 text-info"></i> View Public Profile</a></li>
                                            <li><a href="update_law.php?id=<?php echo $current_user_id; ?>"><i class="fa fa-user-pen mr-2 text-warning"></i> Edit Profile & Hours</a></li>
                                        <?php elseif ($current_user_type === 'admin'): ?>
                                            <li><a href="../Admin/index.php"><i class="fa fa-gauge mr-2 text-primary"></i> Admin Dashboard</a></li>
                                        <?php endif; ?>
                                        <li><a href="Login&Singup/logout.php" style="color:#e74c3c !important;"><i class="fa fa-arrow-right-from-bracket mr-2"></i> Log Out</a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a href="Login&Singup/login_email.php" class="btn btn-primary btn-sm" style="color:#ffffff !important; padding: 6px 14px; border-radius: 4px;">
                                        <i class="fa fa-arrow-right-to-bracket mr-1"></i> Sign In
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>