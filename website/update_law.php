<?php
require_once __DIR__ . '/header.php';

$lawyer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Security check: Must be logged in as admin or as the specific lawyer
if (!isLoggedIn() || (getCurrentUserType() !== 'admin' && (getCurrentUserType() !== 'lawyer' || getCurrentUserId() != $lawyer_id))) {
    echo "<div class='container py-5 text-center' style='margin: 80px auto;'>";
    echo "<i class='fa fa-lock fa-3x text-danger mb-3'></i>";
    echo "<h2>Access Denied</h2>";
    echo "<p class='text-muted'>You must be logged in as this advocate or an administrator to edit this profile.</p>";
    echo "<a href='Login&Singup/login_email.php' class='btn btn-primary mt-3'><i class='fa fa-arrow-right-to-bracket'></i> Sign In</a>";
    echo "</div>";
    require_once __DIR__ . '/footer.php';
    exit();
}

$stmt = $con->prepare("SELECT * FROM lawyer WHERE id = ?");
$stmt->bind_param("i", $lawyer_id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$user) {
    echo "<script>alert('Lawyer not found'); window.location.href='lawyer.php';</script>";
    exit();
}

// Fetch categories
$categories = [];
$cat_res = mysqli_query($con, "SELECT cat_id, cat_name FROM categorie ORDER BY cat_name ASC");
if ($cat_res) {
    while ($row = mysqli_fetch_assoc($cat_res)) {
        $categories[] = $row;
    }
}

$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {
    $uploadDirProfile = __DIR__ . '/uploads/';
    $uploadDirCover = __DIR__ . '/cover_images/';

    if (!is_dir($uploadDirProfile)) mkdir($uploadDirProfile, 0777, true);
    if (!is_dir($uploadDirCover)) mkdir($uploadDirCover, 0777, true);

    $profileImage = $user['image'];
    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'jfif'];
        if (in_array($ext, $allowed) && $_FILES["profile_image"]["size"] <= 5000000) {
            $newProfileName = 'lawyer_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $uploadDirProfile . $newProfileName)) {
                $profileImage = $newProfileName;
            }
        }
    }

    $coverImage = $user['cover image'] ?? $user['cover_image'];
    if (isset($_FILES["cover_image"]) && $_FILES["cover_image"]["error"] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES["cover_image"]["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'jfif'];
        if (in_array($ext, $allowed) && $_FILES["cover_image"]["size"] <= 8000000) {
            $newCoverName = 'cover_' . time() . '_' . uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $uploadDirCover . $newCoverName)) {
                $coverImage = $newCoverName;
            }
        }
    }

    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $number = trim($_POST['number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $barCouncil = trim($_POST['bar_council'] ?? '');
    $since = intval($_POST['since'] ?? 0);
    $specialistId = intval($_POST['specialist'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $degree = trim($_POST['degree'] ?? '');
    $university = trim($_POST['university'] ?? '');
    $language = trim($_POST['languages_spoken'] ?? '');
    $daysArray = $_POST['days'] ?? [];
    $days = is_array($daysArray) ? implode(', ', $daysArray) : trim($daysArray);
    $times = trim($_POST['times'] ?? '');
    $fee = intval($_POST['fee'] ?? 0);
    $aboutMe = trim($_POST['about_me'] ?? '');

    $up_stmt = $con->prepare("UPDATE lawyer SET `name`=?, `last name`=?, `email`=?, `number`=?, `address`=?, `bar council`=?, `since`=?, `specialist`=?, `description`=?, `degree`=?, `university`=?, `language`=?, `day`=?, `Time`=?, `fee`=?, `image`=?, `about me`=?, `cover image`=? WHERE id=?");
    
    if ($up_stmt) {
        $up_stmt->bind_param(
            "ssssssiissssssisssi",
            $firstName,
            $lastName,
            $email,
            $number,
            $address,
            $barCouncil,
            $since,
            $specialistId,
            $description,
            $degree,
            $university,
            $language,
            $days,
            $times,
            $fee,
            $profileImage,
            $aboutMe,
            $coverImage,
            $lawyer_id
        );

        if ($up_stmt->execute()) {
            $msg = "Profile updated successfully!";
            // Update session image if own profile
            if (getCurrentUserId() == $lawyer_id) {
                $_SESSION['user_image'] = $profileImage;
                $_SESSION['user_name'] = trim($firstName . ' ' . $lastName);
            }
            $up_stmt->close();
            header("Location: profile.php?id=" . $lawyer_id);
            exit();
        } else {
            $error = "Update failed: " . htmlspecialchars($up_stmt->error);
            $up_stmt->close();
        }
    }
}

// Current days
$currentDays = [];
$rawD = $user['day'] ?? '';
if (!empty($rawD)) {
    $parts = explode(',', $rawD);
    foreach ($parts as $p) $currentDays[] = trim($p);
}
?>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div style="background:#ffffff; border-radius:12px; border:1px solid #e2e8f0; box-shadow:0 10px 25px rgba(0,0,0,0.06); padding:35px;">
                <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom:2px solid #f1f5f9; padding-bottom:15px;">
                    <div>
                        <h2 style="font-family:'Playfair Display', serif; font-weight:700; color:#0f172a; margin:0;">Edit Advocate Profile</h2>
                        <small class="text-muted">Manage your credentials, consultation fee, working hours, and biography</small>
                    </div>
                    <a href="profile.php?id=<?php echo $lawyer_id; ?>" class="btn btn-default"><i class="fa fa-eye mr-1"></i> View Profile</a>
                </div>

                <?php if (!empty($msg)): ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $msg; ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="update_profile" value="1">

                    <!-- Personal -->
                    <h4 style="color:#1e3a8a; font-weight:700; font-size:16px; margin:20px 0 15px;"><i class="fa fa-user-tie"></i> 1. Personal & Contact Details</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last name'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Official Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Phone / WhatsApp Number</label>
                            <input type="text" name="number" class="form-control" value="<?php echo htmlspecialchars($user['number']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Chambers / Office Address</label>
                        <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                    </div>

                    <!-- Professional -->
                    <h4 style="color:#1e3a8a; font-weight:700; font-size:16px; margin:30px 0 15px;"><i class="fa fa-graduation-cap"></i> 2. Professional Credentials & Licensing</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Bar Council Membership ID</label>
                            <input type="text" name="bar_council" class="form-control" value="<?php echo htmlspecialchars($user['bar council'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Practicing Since (Year)</label>
                            <input type="number" name="since" class="form-control" value="<?php echo htmlspecialchars($user['since']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Primary Specialization</label>
                            <select name="specialist" class="form-control" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['cat_id']; ?>" <?php echo ($user['specialist'] == $cat['cat_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['cat_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Degree</label>
                            <input type="text" name="degree" class="form-control" value="<?php echo htmlspecialchars($user['degree']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Law School / University</label>
                            <input type="text" name="university" class="form-control" value="<?php echo htmlspecialchars($user['university']); ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Languages Spoken</label>
                            <input type="text" name="languages_spoken" class="form-control" value="<?php echo htmlspecialchars($user['language']); ?>" required>
                        </div>
                    </div>

                    <!-- Schedule & Charges -->
                    <h4 style="color:#1e3a8a; font-weight:700; font-size:16px; margin:30px 0 15px;"><i class="fa fa-calendar-check"></i> 3. Consultation Hours & Fee</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Consultation Hours (e.g. 09:00 AM to 05:00 PM)</label>
                            <input type="text" name="times" class="form-control" value="<?php echo htmlspecialchars($user['Time']); ?>" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Consultation Fee (PKR)</label>
                            <input type="number" name="fee" class="form-control" value="<?php echo htmlspecialchars($user['fee']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Available Working Days</label>
                        <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 5px;">
                            <?php 
                            $weekDays = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
                            foreach ($weekDays as $wd):
                                $isChecked = in_array($wd, $currentDays) ? 'checked' : '';
                            ?>
                                <label style="font-weight: normal; margin-right: 10px;">
                                    <input type="checkbox" name="days[]" value="<?php echo $wd; ?>" <?php echo $isChecked; ?>> <?php echo $wd; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Short Service Highlight</label>
                        <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($user['description']); ?>">
                    </div>

                    <div class="form-group">
                        <label>Professional Biography / About Me</label>
                        <textarea name="about_me" class="form-control" rows="5"><?php echo htmlspecialchars($user['about me'] ?? ''); ?></textarea>
                    </div>

                    <!-- Visual Media -->
                    <h4 style="color:#1e3a8a; font-weight:700; font-size:16px; margin:30px 0 15px;"><i class="fa fa-camera"></i> 4. Profile Picture & Banner</h4>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Update Profile Photo</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*">
                            <small class="text-muted">Current: <?php echo htmlspecialchars($user['image']); ?></small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Update Chamber Cover Banner</label>
                            <input type="file" name="cover_image" class="form-control" accept="image/*">
                            <small class="text-muted">Current: <?php echo htmlspecialchars($user['cover image'] ?? 'default_cover.jpg'); ?></small>
                        </div>
                    </div>

                    <div style="margin-top: 30px; text-align: right;">
                        <a href="profile.php?id=<?php echo $lawyer_id; ?>" class="btn btn-default mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-weight:600;"><i class="fa fa-save mr-1"></i> Save Profile Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>
