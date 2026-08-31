<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

// Fetch categories for dropdown
$categories = [];
$cat_res = mysqli_query($con, "SELECT cat_id, cat_name FROM categorie ORDER BY cat_name ASC");
if ($cat_res) {
    while ($row = mysqli_fetch_assoc($cat_res)) {
        $categories[] = $row;
    }
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'register_lawyer') {
    // 1. Personal & Contact
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // 2. Professional
    $barCouncil = trim($_POST['bar_council'] ?? '');
    $since = intval($_POST['since'] ?? 0);
    $specialist = intval($_POST['specialist'] ?? 0);
    $degree = trim($_POST['degree'] ?? '');
    $university = trim($_POST['university'] ?? '');
    $languages = isset($_POST['languages']) && is_array($_POST['languages']) ? implode(', ', $_POST['languages']) : trim($_POST['languages'] ?? 'English');

    // 3. Schedule & Fee
    $daysArray = $_POST['days'] ?? [];
    $days = is_array($daysArray) ? implode(', ', $daysArray) : trim($daysArray);
    $startTime = trim($_POST['start_time'] ?? '09:00 AM');
    $endTime = trim($_POST['end_time'] ?? '05:00 PM');
    $timeSlot = !empty($startTime) && !empty($endTime) ? date("h:i A", strtotime($startTime)) . ' to ' . date("h:i A", strtotime($endTime)) : '09:00 AM to 05:00 PM';
    $fee = intval($_POST['fee'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $aboutMe = trim($_POST['about_me'] ?? '');

    // Basic Validation
    if (empty($firstName) || empty($email) || empty($phone) || empty($password) || empty($specialist) || empty($barCouncil)) {
        $error = "Please fill in all mandatory fields.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check for duplicate email
        $check_stmt = $con->prepare("SELECT email FROM lawyer WHERE email = ? UNION SELECT email FROM user WHERE email = ?");
        $check_stmt->bind_param("ss", $email, $email);
        $check_stmt->execute();
        $check_res = $check_stmt->get_result();

        if ($check_res && $check_res->num_rows > 0) {
            $error = "An account with this email address already exists. Please sign in.";
            $check_stmt->close();
        } else {
            $check_stmt->close();

            // Handle Profile Image Upload
            $profileImage = 'default_lawyer.png';
            $coverImage = 'default_cover.jpg';
            $uploadDirProfile = __DIR__ . '/../../uploads/';
            $uploadDirCover = __DIR__ . '/../../cover_images/';

            if (!is_dir($uploadDirProfile)) {
                mkdir($uploadDirProfile, 0777, true);
            }
            if (!is_dir($uploadDirCover)) {
                mkdir($uploadDirCover, 0777, true);
            }

            // Profile picture upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'jfif'];
                if (in_array($ext, $allowed) && $_FILES['profile_image']['size'] <= 5 * 1024 * 1024) {
                    $newProfileName = 'lawyer_' . time() . '_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDirProfile . $newProfileName)) {
                        $profileImage = $newProfileName;
                        // Also mirror to legacy path for backward compatibility
                        @copy($uploadDirProfile . $newProfileName, __DIR__ . '/uploads/' . $newProfileName);
                    }
                }
            }

            // Cover picture upload
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'jfif'];
                if (in_array($ext, $allowed) && $_FILES['cover_image']['size'] <= 8 * 1024 * 1024) {
                    $newCoverName = 'cover_' . time() . '_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDirCover . $newCoverName)) {
                        $coverImage = $newCoverName;
                        // Also mirror to legacy path
                        @copy($uploadDirCover . $newCoverName, __DIR__ . '/cover_images/' . $newCoverName);
                    }
                }
            }

            // Hash password
            $hashedPassword = hashPassword($password);

            // Prepared Insert Query
            $insert_query = "INSERT INTO lawyer (`name`, `last name`, `email`, `number`, `address`, `password`, `dob`, `bar council`, `since`, `specialist`, `description`, `degree`, `university`, `language`, `day`, `Time`, `fee`, `image`, `about me`, `cover image`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')";
            
            $stmt = $con->prepare($insert_query);
            if ($stmt) {
                $stmt->bind_param(
                    "ssssssssisssssssisss",
                    $firstName,
                    $lastName,
                    $email,
                    $phone,
                    $address,
                    $hashedPassword,
                    $dob,
                    $barCouncil,
                    $since,
                    $specialist,
                    $description,
                    $degree,
                    $university,
                    $languages,
                    $days,
                    $timeSlot,
                    $fee,
                    $profileImage,
                    $aboutMe,
                    $coverImage
                );

                if ($stmt->execute()) {
                    $lawyerId = $stmt->insert_id;
                    $stmt->close();

                    // Auto-login lawyer
                    $_SESSION['id'] = $lawyerId;
                    $_SESSION['user_id'] = $lawyerId;
                    $_SESSION['name'] = trim($firstName . ' ' . $lastName);
                    $_SESSION['user_name'] = trim($firstName . ' ' . $lastName);
                    $_SESSION['email'] = $email;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['image'] = $profileImage;
                    $_SESSION['user_image'] = $profileImage;
                    $_SESSION['user_type'] = 'lawyer';

                    header("Location: ../../profile.php?id=" . $lawyerId);
                    exit();
                } else {
                    $error = "Database error during registration: " . htmlspecialchars($stmt->error);
                    $stmt->close();
                }
            } else {
                $error = "Failed to prepare registration statement: " . htmlspecialchars($con->error);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join as a Verified Advocate | Find a Lawyer</title>
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
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: #0f172a;
            background-image: radial-gradient(#1e293b 1px, transparent 1px);
            background-size: 24px 24px;
            color: var(--text-dark);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wizard-container {
            width: 100%;
            max-width: 880px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }

        .wizard-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: #ffffff;
            padding: 35px 40px;
            position: relative;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 12px;
        }

        .logo span {
            color: #fbbf24;
        }

        .wizard-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .wizard-header p {
            color: #94a3b8;
            font-size: 15px;
        }

        /* Progress Steps */
        .step-progress {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            position: relative;
        }

        .step-progress::before {
            content: '';
            position: absolute;
            top: 18px;
            left: 30px;
            right: 30px;
            height: 2px;
            background: rgba(255, 255, 255, 0.2);
            z-index: 1;
        }

        .step-item {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #1e293b;
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin: 0 auto 8px;
            transition: all 0.3s ease;
        }

        .step-item.active .step-circle {
            background: #d97706;
            border-color: #fbbf24;
            color: #ffffff;
            box-shadow: 0 0 15px rgba(217, 119, 6, 0.5);
        }

        .step-item.completed .step-circle {
            background: #10b981;
            border-color: #34d399;
            color: #ffffff;
        }

        .step-label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }

        .step-item.active .step-label {
            color: #ffffff;
            font-weight: 600;
        }

        /* Form Body */
        .wizard-body {
            padding: 40px;
        }

        .alert-error {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--bg-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-title i {
            color: var(--accent);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-grid.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
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
            padding: 12px 16px;
            font-size: 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            background-color: #ffffff;
            color: var(--text-dark);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 90px;
        }

        .input-hint {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Days Selection Pills */
        .days-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 5px;
        }

        .day-pill {
            position: relative;
        }

        .day-pill input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .day-pill label {
            display: block;
            text-align: center;
            padding: 10px 8px;
            background: var(--bg-light);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .day-pill input[type="checkbox"]:checked + label {
            background: #eff6ff;
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 2px 4px rgba(30, 58, 138, 0.1);
        }

        /* Upload Previews */
        .upload-cards-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
            align-items: center;
        }

        .avatar-upload-box {
            text-align: center;
        }

        .avatar-preview {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 3px solid var(--primary);
            object-fit: cover;
            margin: 0 auto 12px;
            display: block;
            background: #f1f5f9;
        }

        .cover-preview {
            width: 100%;
            height: 140px;
            border-radius: 10px;
            border: 2px dashed var(--border);
            object-fit: cover;
            display: block;
            background: #f1f5f9;
            margin-bottom: 12px;
        }

        .btn-upload-file {
            display: inline-block;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            background: #eff6ff;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-upload-file:hover {
            background: #dbeafe;
        }

        /* Wizard Footer Actions */
        .wizard-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid var(--border);
        }

        .btn {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #1e40af;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.25);
        }

        .btn-accent {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: #ffffff;
            font-size: 15px;
            padding: 14px 28px;
        }

        .btn-accent:hover {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
        }

        .sign-in-link {
            font-size: 14px;
            color: #64748b;
        }

        .sign-in-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .days-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .upload-cards-grid {
                grid-template-columns: 1fr;
            }
            .wizard-body {
                padding: 24px;
            }
        }
    </style>
</head>
<body>

<div class="wizard-container">
    <!-- Header -->
    <div class="wizard-header">
        <a href="../../index.php" class="logo">Law<span>firm.</span></a>
        <h1>Join as a Certified Legal Practitioner</h1>
        <p>Create your verified advocate profile to start accepting client consultations online.</p>

        <!-- Progress Tracker -->
        <div class="step-progress">
            <div class="step-item active" id="step-node-1">
                <div class="step-circle">1</div>
                <div class="step-label">Personal Details</div>
            </div>
            <div class="step-item" id="step-node-2">
                <div class="step-circle">2</div>
                <div class="step-label">Credentials</div>
            </div>
            <div class="step-item" id="step-node-3">
                <div class="step-circle">3</div>
                <div class="step-label">Practice & Hours</div>
            </div>
            <div class="step-item" id="step-node-4">
                <div class="step-circle">4</div>
                <div class="step-label">Profile Media</div>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="wizard-body">
        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <i class="fa fa-circle-exclamation"></i>
                <div><?php echo htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>

        <form id="lawyerForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="register_lawyer">

            <!-- STEP 1: Personal & Account Info -->
            <div class="step-content active" id="step-1">
                <div class="form-section-title">
                    <i class="fa fa-user-tie"></i> 1. Personal & Account Information
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" name="first_name" class="form-control" placeholder="e.g. Barrister Ammar" required value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="e.g. Motan" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Official Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. ammar@lawfirm.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <span class="input-hint">Will be used for client inquiries and logging in.</span>
                    </div>
                    <div class="form-group">
                        <label>Phone / WhatsApp Number <span class="required">*</span></label>
                        <input type="tel" name="number" class="form-control" placeholder="e.g. +92 300 1234567" required value="<?php echo htmlspecialchars($_POST['number'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Chambers / Office Address <span class="required">*</span></label>
                        <input type="text" name="address" class="form-control" placeholder="e.g. Suite 402, High Court Chambers, Karachi" required value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <input type="password" name="password" id="pass1" class="form-control" placeholder="Min. 6 characters" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password <span class="required">*</span></label>
                        <input type="password" name="confirm_password" id="pass2" class="form-control" placeholder="Re-type password" required>
                    </div>
                </div>

                <div class="wizard-footer">
                    <div class="sign-in-link">Already registered? <a href="../login_email.php">Sign In</a></div>
                    <button type="button" class="btn btn-primary" onclick="nextStep(2)">Continue to Credentials <i class="fa fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 2: Professional Credentials -->
            <div class="step-content" id="step-2">
                <div class="form-section-title">
                    <i class="fa fa-graduation-cap"></i> 2. Professional Credentials & Licensing
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Bar Council Membership ID <span class="required">*</span></label>
                        <input type="text" name="bar_council" class="form-control" placeholder="e.g. BC-KHI-85462" required value="<?php echo htmlspecialchars($_POST['bar_council'] ?? ''); ?>">
                        <span class="input-hint">High Court or District Bar registration number.</span>
                    </div>
                    <div class="form-group">
                        <label>Practicing Since (Year) <span class="required">*</span></label>
                        <input type="number" name="since" class="form-control" placeholder="e.g. 2014" min="1960" max="2026" required value="<?php echo htmlspecialchars($_POST['since'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Primary Specialization / Practice Area <span class="required">*</span></label>
                        <select name="specialist" class="form-select" required>
                            <option value="">-- Choose Primary Practice Area --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['cat_id']; ?>" <?php echo (isset($_POST['specialist']) && $_POST['specialist'] == $cat['cat_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['cat_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Law Degree <span class="required">*</span></label>
                        <input type="text" name="degree" class="form-control" placeholder="e.g. LL.M (Commercial Law), Bar-at-Law" required value="<?php echo htmlspecialchars($_POST['degree'] ?? ''); ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Law School / University <span class="required">*</span></label>
                        <input type="text" name="university" class="form-control" placeholder="e.g. Harvard Law / University of London" required value="<?php echo htmlspecialchars($_POST['university'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Languages Spoken</label>
                        <input type="text" name="languages" class="form-control" placeholder="e.g. English, Urdu, Sindhi" value="<?php echo htmlspecialchars($_POST['languages'] ?? 'English, Urdu'); ?>">
                    </div>
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(1)"><i class="fa fa-arrow-left"></i> Previous</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(3)">Continue to Schedule <i class="fa fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 3: Schedule, Consultation Fee & Bio -->
            <div class="step-content" id="step-3">
                <div class="form-section-title">
                    <i class="fa fa-calendar-check"></i> 3. Availability Schedule & Consultation Fee
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Available Consultation Days <span class="required">*</span></label>
                    <div class="days-grid">
                        <?php 
                        $allDays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                        $defaultDays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
                        foreach ($allDays as $d):
                            $checked = in_array($d, $defaultDays) ? 'checked' : '';
                        ?>
                            <div class="day-pill">
                                <input type="checkbox" name="days[]" id="day_<?php echo $d; ?>" value="<?php echo $d; ?>" <?php echo $checked; ?>>
                                <label for="day_<?php echo $d; ?>"><?php echo $d; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Available Working Hours</label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="time" name="start_time" class="form-control" value="09:00">
                            <span>to</span>
                            <input type="time" name="end_time" class="form-control" value="17:00">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Initial Consultation Fee (PKR) <span class="required">*</span></label>
                        <input type="number" name="fee" class="form-control" placeholder="e.g. 5000" min="0" step="500" required value="<?php echo htmlspecialchars($_POST['fee'] ?? '5000'); ?>">
                        <span class="input-hint">Per appointment session consultation charges.</span>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label>Short Service Highlight <span class="required">*</span></label>
                    <input type="text" name="description" class="form-control" placeholder="e.g. Specialist in Corporate Mergers, Commercial Contracts & Dispute Resolution" required value="<?php echo htmlspecialchars($_POST['description'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Professional Bio / About Me <span class="required">*</span></label>
                    <textarea name="about_me" class="form-textarea" placeholder="Provide a detailed biography of your legal experience, key litigation victories, and client advocacy principles..." required><?php echo htmlspecialchars($_POST['about_me'] ?? ''); ?></textarea>
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(2)"><i class="fa fa-arrow-left"></i> Previous</button>
                    <button type="button" class="btn btn-primary" onclick="nextStep(4)">Continue to Media <i class="fa fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 4: Profile Photos & Verification -->
            <div class="step-content" id="step-4">
                <div class="form-section-title">
                    <i class="fa fa-camera"></i> 4. Profile Picture & Cover Media
                </div>

                <div class="upload-cards-grid">
                    <div class="avatar-upload-box">
                        <img src="../../images/lawyer 1.jpg" id="avatarPreviewImg" class="avatar-preview" alt="Profile Preview">
                        <label for="profile_input" class="btn-upload-file"><i class="fa fa-cloud-arrow-up"></i> Upload Photo</label>
                        <input type="file" id="profile_input" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(this, 'avatarPreviewImg')">
                        <p class="input-hint" style="margin-top: 6px;">Recommended: Square headshot (JPG/PNG, Max 5MB)</p>
                    </div>

                    <div>
                        <img src="../../cover_images/Assign#1.PNG" id="coverPreviewImg" class="cover-preview" alt="Cover Preview">
                        <label for="cover_input" class="btn-upload-file"><i class="fa fa-image"></i> Upload Chamber / Office Banner</label>
                        <input type="file" id="cover_input" name="cover_image" accept="image/*" style="display: none;" onchange="previewImage(this, 'coverPreviewImg')">
                        <p class="input-hint" style="margin-top: 6px;">Recommended: 1200x400 Landscape Cover Banner (Max 8MB)</p>
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: 8px; padding: 16px; margin-top: 25px;">
                    <label style="display: flex; gap: 10px; align-items: flex-start; cursor: pointer; font-size: 13px; color: #334155;">
                        <input type="checkbox" required style="margin-top: 3px;">
                        <span>I declare that all information, Bar Council credentials, and practice history provided above are authentic and verifiable.</span>
                    </label>
                </div>

                <div class="wizard-footer">
                    <button type="button" class="btn btn-secondary" onclick="prevStep(3)"><i class="fa fa-arrow-left"></i> Previous</button>
                    <button type="submit" class="btn btn-accent"><i class="fa fa-check-circle"></i> Complete Registration & Activate Profile</button>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    let currentStep = 1;

    function showStep(stepNumber) {
        document.querySelectorAll('.step-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.step-item').forEach(el => el.classList.remove('active'));

        const targetContent = document.getElementById('step-' + stepNumber);
        if (targetContent) {
            targetContent.classList.add('active');
        }

        for (let i = 1; i <= 4; i++) {
            const node = document.getElementById('step-node-' + i);
            if (i < stepNumber) {
                node.classList.add('completed');
                node.classList.remove('active');
            } else if (i === stepNumber) {
                node.classList.add('active');
                node.classList.remove('completed');
            } else {
                node.classList.remove('active', 'completed');
            }
        }
        currentStep = stepNumber;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function nextStep(stepNumber) {
        // Validate inputs in current step before moving forward
        const currentContent = document.getElementById('step-' + currentStep);
        const requiredInputs = currentContent.querySelectorAll('input[required], select[required], textarea[required]');
        
        let valid = true;
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                input.reportValidity();
                valid = false;
                return;
            }
        });

        if (currentStep === 1) {
            const p1 = document.getElementById('pass1').value;
            const p2 = document.getElementById('pass2').value;
            if (p1.length < 6) {
                alert('Password must be at least 6 characters.');
                return;
            }
            if (p1 !== p2) {
                alert('Passwords do not match.');
                return;
            }
        }

        if (valid) {
            showStep(stepNumber);
        }
    }

    function prevStep(stepNumber) {
        showStep(stepNumber);
    }

    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>
