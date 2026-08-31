<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

$lawyer_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['lawyer_id']) ? intval($_GET['lawyer_id']) : 0);
$lawyer = null;

if ($lawyer_id > 0) {
    $stmt = $con->prepare("SELECT lawyer.*, categorie.cat_name FROM lawyer JOIN categorie ON categorie.cat_id = lawyer.specialist WHERE lawyer.id = ?");
    $stmt->bind_param("i", $lawyer_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $lawyer = $res->fetch_assoc();
    }
    $stmt->close();
}

// If no specific lawyer selected, fetch all lawyers for dropdown selection
$all_lawyers = [];
if (!$lawyer) {
    $lawyer_list_res = mysqli_query($con, "SELECT lawyer.id, lawyer.name, lawyer.`last name` as last_name, lawyer.fee, categorie.cat_name FROM lawyer JOIN categorie ON categorie.cat_id = lawyer.specialist WHERE lawyer.status = 'active' ORDER BY lawyer.name ASC");
    if ($lawyer_list_res) {
        while ($l = mysqli_fetch_assoc($lawyer_list_res)) {
            $all_lawyers[] = $l;
        }
    }
}

// User auto-fill if logged in
$loggedInUserId = $_SESSION['user_id'] ?? ($_SESSION['id'] ?? null);
$clientName = $_SESSION['user_name'] ?? ($_SESSION['name'] ?? '');
$clientEmail = $_SESSION['user_email'] ?? ($_SESSION['email'] ?? '');
$clientPhone = '';

if ($loggedInUserId && empty($clientPhone)) {
    $u_stmt = $con->prepare("SELECT phone FROM user WHERE id = ?");
    if ($u_stmt) {
        $u_stmt->bind_param("i", $loggedInUserId);
        $u_stmt->execute();
        $u_res = $u_stmt->get_result();
        if ($u_res && $u_row = $u_res->fetch_assoc()) {
            $clientPhone = $u_row['phone'] ?? '';
        }
        $u_stmt->close();
    }
}

$booking_success = false;
$booking_id = 0;
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_booking'])) {
    $selected_lawyer_id = intval($_POST['lawyer_id'] ?? $lawyer_id);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $appointment_time = trim($_POST['appointment_time'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($selected_lawyer_id) || empty($name) || empty($email) || empty($phone) || empty($appointment_date) || empty($appointment_time)) {
        $error = "Please fill in all required appointment details.";
    } else {
        // Compute available summary string
        $dayOfWeek = date('l', strtotime($appointment_date));
        $availableSummary = "$dayOfWeek at $appointment_time";

        $ins_stmt = $con->prepare("INSERT INTO appointment (`user_id`, `name`, `email`, `phone`, `lawyer`, `available`, `appointment_date`, `appointment_time`, `message`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        
        if ($ins_stmt) {
            $ins_stmt->bind_param(
                "isssissss",
                $loggedInUserId,
                $name,
                $email,
                $phone,
                $selected_lawyer_id,
                $availableSummary,
                $appointment_date,
                $appointment_time,
                $message
            );

            if ($ins_stmt->execute()) {
                $booking_id = $ins_stmt->insert_id;
                $booking_success = true;
                $ins_stmt->close();

                // Ensure lawyer details are populated for success screen
                if (!$lawyer && $selected_lawyer_id > 0) {
                    $l_fetch = $con->prepare("SELECT lawyer.*, categorie.cat_name FROM lawyer JOIN categorie ON categorie.cat_id = lawyer.specialist WHERE lawyer.id = ?");
                    if ($l_fetch) {
                        $l_fetch->bind_param("i", $selected_lawyer_id);
                        $l_fetch->execute();
                        $lf_res = $l_fetch->get_result();
                        if ($lf_res && $lf_res->num_rows > 0) {
                            $lawyer = $lf_res->fetch_assoc();
                        }
                        $l_fetch->close();
                    }
                }
            } else {
                $error = "Failed to submit booking: " . htmlspecialchars($ins_stmt->error);
                $ins_stmt->close();
            }
        } else {
            $error = "Database prepare error: " . htmlspecialchars($con->error);
        }
    }
}

// Parse lawyer available days & times for dropdowns
$availableDaysList = [];
$availableTimeSlots = [];

if ($lawyer) {
    // Parse Days
    $rawDay = $lawyer['day'] ?? '';
    if (!empty($rawDay)) {
        // If stored as JSON or string
        if (str_starts_with($rawDay, '[') || str_starts_with($rawDay, '{')) {
            $decoded = json_decode($rawDay, true);
            if (is_array($decoded)) {
                foreach ($decoded as $item) {
                    if (is_array($item) && isset($item['day'])) {
                        $availableDaysList[] = $item['day'];
                    }
                }
            }
        } else {
            $parts = explode(',', $rawDay);
            foreach ($parts as $p) {
                $trimmed = trim($p);
                if (!empty($trimmed)) {
                    $availableDaysList[] = $trimmed;
                }
            }
        }
    }

    if (empty($availableDaysList)) {
        $availableDaysList = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
    }

    // Parse Working Time
    $rawTime = $lawyer['Time'] ?? '';
    // Standard generated slots
    $availableTimeSlots = [
        "09:00 AM", "10:00 AM", "11:00 AM", "12:00 PM",
        "02:00 PM", "03:00 PM", "04:00 PM", "05:00 PM", "06:00 PM"
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Schedule Consultation | Find a Lawyer</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --primary: #1e3a8a;
            --primary-dark: #0f172a;
            --accent: #d97706;
            --accent-hover: #b45309;
            --bg-light: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --card-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: var(--text-dark);
            min-height: 100vh;
            padding: 30px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .booking-card {
            width: 100%;
            max-width: 950px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .booking-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
        }

        .logo span {
            color: #fbbf24;
        }

        .nav-back {
            color: #cbd5e1;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s;
        }

        .nav-back:hover {
            color: #ffffff;
        }

        .booking-content {
            padding: 40px;
        }

        .appointment-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 35px;
        }

        /* Lawyer Summary Card */
        .lawyer-summary-card {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            height: fit-content;
        }

        .lawyer-badge-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .lawyer-avatar-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid var(--primary);
            object-fit: cover;
            margin-bottom: 12px;
            background: #e2e8f0;
        }

        .lawyer-summary-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .specialty-tag {
            display: inline-block;
            background: #eff6ff;
            color: var(--primary);
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-item .label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .summary-item .value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .fee-badge {
            font-size: 16px;
            color: #059669;
            font-weight: 700;
        }

        .days-pill-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 8px;
        }

        .day-chip {
            background: #e0f2fe;
            color: #0369a1;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* Form */
        .form-title {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 25px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }

        .form-group label .required {
            color: #ef4444;
        }

        .form-control, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 14px;
            font-size: 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            background-color: #ffffff;
            color: var(--text-dark);
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-submit-booking {
            width: 100%;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            color: #ffffff;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
            margin-top: 10px;
        }

        .btn-submit-booking:hover {
            background: linear-gradient(135deg, #1e40af 0%, #172554 100%);
            transform: translateY(-1px);
        }

        .alert-error {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Success Card */
        .success-box {
            text-align: center;
            padding: 40px 20px;
        }

        .success-icon {
            width: 75px;
            height: 75px;
            background: #ecfdf5;
            color: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin: 0 auto 20px;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
        }

        .success-box h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .success-box p {
            color: var(--text-muted);
            font-size: 15px;
            max-width: 500px;
            margin: 0 auto 25px;
        }

        .booking-details-summary {
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            max-width: 480px;
            margin: 0 auto 30px;
            text-align: left;
        }

        .btn-group-success {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-outline {
            padding: 12px 24px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-outline:hover {
            background: #e2e8f0;
        }

        @media (max-width: 820px) {
            .appointment-layout {
                grid-template-columns: 1fr;
            }
            .booking-content {
                padding: 24px;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>

<div class="booking-card">
    <div class="booking-header">
        <div>
            <a href="../index.php" class="logo">Law<span>firm.</span></a>
        </div>
        <div>
            <a href="<?php echo $lawyer ? '../profile.php?id=' . $lawyer['id'] : '../lawyer.php'; ?>" class="nav-back">
                <i class="fa fa-arrow-left"></i> Back to <?php echo $lawyer ? 'Lawyer Profile' : 'Find a Lawyer'; ?>
            </a>
        </div>
    </div>

    <div class="booking-content">
        <?php if ($booking_success): 
            $cleanPhone = $lawyer ? preg_replace('/[^0-9]/', '', $lawyer['number']) : '';
            $waPhone = $cleanPhone;
            if (str_starts_with($waPhone, '0')) {
                $waPhone = '92' . substr($waPhone, 1);
            } elseif (!str_starts_with($waPhone, '92') && strlen($waPhone) === 10) {
                $waPhone = '92' . $waPhone;
            }
            $lawyerFullName = $lawyer ? ($lawyer['name'] . ' ' . ($lawyer['last name'] ?? '')) : 'Advocate';
            $waMsg = urlencode("Hello Advocate $lawyerFullName, I have submitted a consultation booking with you (Ref: #APT-" . str_pad($booking_id, 5, '0', STR_PAD_LEFT) . ") for " . date('D, M j, Y', strtotime($appointment_date)) . " at " . $appointment_time . ". Looking forward to connecting with you.");
        ?>
            <!-- Success Screen -->
            <div class="success-box">
                <div class="success-icon">
                    <i class="fa fa-circle-check"></i>
                </div>
                <h2>Appointment Reserved Successfully!</h2>
                <p>Your consultation request has been submitted to Advocate <?php echo htmlspecialchars($lawyerFullName); ?>. You can monitor the live confirmation status or connect directly below.</p>

                <div class="booking-details-summary">
                    <div class="summary-item">
                        <span class="label">Reference ID:</span>
                        <span class="value" style="color:#1e3a8a; font-size:15px; font-weight:700;">#APT-<?php echo str_pad($booking_id, 5, '0', STR_PAD_LEFT); ?></span>
                    </div>
                    <?php if ($lawyer): ?>
                        <div class="summary-item">
                            <span class="label">Advocate:</span>
                            <span class="value">Advocate <?php echo htmlspecialchars($lawyerFullName); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Practice Area:</span>
                            <span class="value"><?php echo htmlspecialchars($lawyer['cat_name'] ?? 'Legal Counsel'); ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="summary-item">
                        <span class="label">Client Name:</span>
                        <span class="value"><?php echo htmlspecialchars($name); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Consultation Date:</span>
                        <span class="value"><?php echo date('D, M j, Y', strtotime($appointment_date)); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Scheduled Time:</span>
                        <span class="value"><?php echo htmlspecialchars($appointment_time); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="label">Status:</span>
                        <span class="value" style="color:#d97706; font-weight:700;"><i class="fa fa-clock mr-1"></i> Pending Advocate Confirmation</span>
                    </div>
                </div>

                <?php if ($lawyer && !empty($lawyer['number'])): ?>
                    <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:16px; max-width:480px; margin:0 auto 25px; text-align:center;">
                        <strong style="color:#166534; font-size:14px; display:block; margin-bottom:8px;">
                            <i class="fa fa-comments mr-1"></i> Need Instant Confirmation or Urgent Consultation?
                        </strong>
                        <div style="display:flex; justify-content:center; gap:10px; flex-wrap:wrap;">
                            <a href="https://wa.me/<?php echo $waPhone; ?>?text=<?php echo $waMsg; ?>" target="_blank" style="background:#25d366; color:#ffffff; padding:9px 18px; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                                <i class="fab fa-whatsapp"></i> WhatsApp Advocate
                            </a>
                            <a href="tel:<?php echo htmlspecialchars($lawyer['number']); ?>" style="background:#1e3a8a; color:#ffffff; padding:9px 18px; border-radius:6px; font-size:13px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                                <i class="fa fa-phone"></i> Call Chambers
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="btn-group-success">
                    <a href="../my_appointments.php" class="btn-outline" style="background:#1e3a8a; color:#fff; border-color:#1e3a8a;"><i class="fa fa-calendar-check mr-1"></i> Track in My Consultations</a>
                    <a href="../lawyer.php" class="btn-outline"><i class="fa fa-scale-balanced mr-1"></i> Browse More Advocates</a>
                    <a href="../index.php" class="btn-outline"><i class="fa fa-home mr-1"></i> Back to Home</a>
                </div>
            </div>

        <?php else: ?>
            <!-- Booking Form -->
            <div class="appointment-layout">
                <!-- Sidebar Summary -->
                <div class="lawyer-summary-card">
                    <?php if ($lawyer): ?>
                        <div class="lawyer-badge-header">
                            <img src="../uploads/<?php echo htmlspecialchars($lawyer['image'] ?: 'default_lawyer.png'); ?>" class="lawyer-avatar-img" alt="Lawyer" onerror="this.src='../images/lawyer 1.jpg'">
                            <h3><?php echo htmlspecialchars($lawyer['name'] . ' ' . ($lawyer['last name'] ?? '')); ?></h3>
                            <span class="specialty-tag"><i class="fa fa-shield-halved"></i> <?php echo htmlspecialchars($lawyer['cat_name']); ?></span>
                        </div>

                        <div class="summary-item">
                            <span class="label">Consultation Fee:</span>
                            <span class="fee-badge">PKR <?php echo number_format($lawyer['fee']); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Experience:</span>
                            <span class="value"><?php echo (date('Y') - intval($lawyer['since'])); ?>+ Years (Since <?php echo htmlspecialchars($lawyer['since']); ?>)</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Bar Council ID:</span>
                            <span class="value"><?php echo htmlspecialchars($lawyer['bar council'] ?: 'BC-Verified'); ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Working Hours:</span>
                            <span class="value"><?php echo htmlspecialchars($lawyer['Time'] ?: '09:00 AM - 05:00 PM'); ?></span>
                        </div>

                        <div style="margin-top: 15px;">
                            <span class="label" style="font-size:12px; color:var(--text-muted);">Available Days:</span>
                            <div class="days-pill-list">
                                <?php foreach ($availableDaysList as $dayChip): ?>
                                    <span class="day-chip"><?php echo htmlspecialchars($dayChip); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="lawyer-badge-header">
                            <i class="fa fa-scale-balanced" style="font-size: 48px; color: var(--primary); margin-bottom: 12px;"></i>
                            <h3>Lawyer Appointment</h3>
                            <p style="font-size: 13px; color: var(--text-muted);">Select your preferred advocate from our directory to book a consultation session.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Form Section -->
                <div>
                    <h2 class="form-title">Reserve a Legal Consultation</h2>
                    <p class="form-subtitle">Fill in your contact details and preferred date/time slot to confirm your appointment.</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert-error">
                            <i class="fa fa-circle-exclamation"></i>
                            <div><?php echo htmlspecialchars($error); ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="submit_booking" value="1">

                        <?php if (!$lawyer): ?>
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label>Select Advocate / Legal Counsel <span class="required">*</span></label>
                                <select name="lawyer_id" class="form-select" required onchange="window.location.href='appoint.php?id='+this.value">
                                    <option value="">-- Choose a Lawyer --</option>
                                    <?php foreach ($all_lawyers as $lawItem): ?>
                                        <option value="<?php echo $lawItem['id']; ?>">
                                            <?php echo htmlspecialchars($lawItem['name'] . ' ' . $lawItem['last_name'] . ' (' . $lawItem['cat_name'] . ') - PKR ' . number_format($lawItem['fee'])); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="lawyer_id" value="<?php echo $lawyer['id']; ?>">
                        <?php endif; ?>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Your Full Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required value="<?php echo htmlspecialchars($_POST['name'] ?? $clientName); ?>">
                            </div>
                            <div class="form-group">
                                <label>Email Address <span class="required">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="e.g. client@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? $clientEmail); ?>">
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Phone / WhatsApp Number <span class="required">*</span></label>
                                <input type="tel" name="phone" class="form-control" placeholder="e.g. +92 300 1234567" required value="<?php echo htmlspecialchars($_POST['phone'] ?? $clientPhone); ?>">
                            </div>
                            <div class="form-group">
                                <label>Appointment Date <span class="required">*</span></label>
                                <input type="date" name="appointment_date" id="appointment_date" class="form-control" min="<?php echo date('Y-m-d'); ?>" required value="<?php echo htmlspecialchars($_POST['appointment_date'] ?? date('Y-m-d', strtotime('+1 day'))); ?>">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 16px;">
                            <label>Preferred Time Slot <span class="required">*</span></label>
                            <select name="appointment_time" class="form-select" required>
                                <option value="">-- Choose Consultation Slot --</option>
                                <?php foreach ($availableTimeSlots as $slot): ?>
                                    <option value="<?php echo $slot; ?>" <?php echo (isset($_POST['appointment_time']) && $_POST['appointment_time'] === $slot) ? 'selected' : ''; ?>>
                                        <?php echo $slot; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label>Brief Case Summary / Consultation Subject</label>
                            <textarea name="message" class="form-textarea" placeholder="Briefly describe your legal concern or documents you would like the advocate to review..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" class="btn-submit-booking">
                            <i class="fa fa-calendar-plus"></i> Confirm & Book Consultation
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
