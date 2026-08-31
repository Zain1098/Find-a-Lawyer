<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/../website/includes/auth.php';

// Strict Admin Auth Lock
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: Signup/login_email.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lawfirm - Administrative Management Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <style>
        .badge-pending { background-color: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }
        .badge-confirmed { background-color: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }
        .badge-completed { background-color: #e0e7ff; color: #3730a3; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }
        .badge-cancelled { background-color: #fee2e2; color: #991b1b; padding: 4px 8px; border-radius: 4px; font-weight: 600; font-size: 12px; }
    </style>
</head>
<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">
        <div class="nav-header">
            <a href="index.php" class="brand-logo">
                <span style="font-size: 20px; font-weight: 700; color: #fff; font-family: serif;">Law<span style="color:#d97706;">firm.</span> <small style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color:#94a3b8;">Admin</small></span>
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>

        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="search_bar">
                                <a href="../website/index.php" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fa fa-external-link"></i> View Public Website</a>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="fa fa-user-shield" style="font-size: 18px; color:#1e3a8a;"></i>
                                    <span class="ml-2 font-weight-bold"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-item disabled font-weight-bold" style="color: #64748b; font-size: 12px;">
                                        <?php echo htmlspecialchars($_SESSION['user_email'] ?? 'admin@lawfirm.com'); ?>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="./Signup/logout.php">
                                        <i class="fa fa-arrow-right-from-bracket"></i> Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Main Navigation</li>
                    <li><a href="index.php"><i class="fa fa-gauge"></i><span class="nav-text">Dashboard</span></a></li>
                    <li><a href="appointment.php"><i class="fa fa-calendar-check"></i><span class="nav-text">Appointments</span></a></li>
                    <li><a href="law_show.php"><i class="fa fa-scale-balanced"></i><span class="nav-text">Lawyers Directory</span></a></li>
                    <li><a href="user_show.php"><i class="fa fa-users"></i><span class="nav-text">Clients / Users</span></a></li>
                    
                    <li class="nav-label">Configuration</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="fa fa-layer-group"></i><span class="nav-text">Categories</span></a>
                        <ul aria-expanded="false">
                            <li><a href="cat_add.php"><i class="fa fa-plus-circle"></i> Add Practice Area</a></li>
                            <li><a href="cat_show.php"><i class="fa fa-list"></i> Manage Categories</a></li>
                        </ul>
                    </li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="fa fa-user-tag"></i><span class="nav-text">Roles</span></a>
                        <ul aria-expanded="false">
                            <li><a href="role_add.php">Add Role</a></li>
                            <li><a href="role_show.php">Show Roles</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
