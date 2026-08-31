<?php
require_once __DIR__ . '/header.php';

$lawyer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$lawyer = null;

if ($lawyer_id > 0) {
    $stmt = $con->prepare("SELECT lawyer.*, categorie.cat_name, categorie.cat_desc 
                          FROM lawyer 
                          JOIN categorie ON categorie.cat_id = lawyer.specialist 
                          WHERE lawyer.id = ?");
    $stmt->bind_param("i", $lawyer_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $lawyer = $res->fetch_assoc();
    }
    $stmt->close();
}

if (!$lawyer) {
    echo "<div class='container py-5 text-center' style='margin: 80px auto;'>";
    echo "<i class='fa fa-user-slash fa-3x text-muted mb-3'></i>";
    echo "<h2>Advocate Profile Not Found</h2>";
    echo "<p class='text-muted'>The lawyer profile you are trying to view does not exist or has been removed.</p>";
    echo "<a href='lawyer.php' class='btn btn-primary mt-3'><i class='fa fa-arrow-left'></i> Browse All Lawyers</a>";
    echo "</div>";
    require_once __DIR__ . '/footer.php';
    exit();
}

$expYears = $lawyer['since'] ? (date('Y') - intval($lawyer['since'])) : 0;
$isOwnProfile = (isLoggedIn() && getCurrentUserType() === 'lawyer' && getCurrentUserId() == $lawyer['id']);

// Parse days
$daysList = [];
$rawDays = $lawyer['day'] ?? '';
if (!empty($rawDays)) {
    if (str_starts_with($rawDays, '[') || str_starts_with($rawDays, '{')) {
        $decoded = json_decode($rawDays, true);
        if (is_array($decoded)) {
            foreach ($decoded as $item) {
                if (is_array($item) && isset($item['day'])) $daysList[] = $item['day'];
            }
        }
    } else {
        $parts = explode(',', $rawDays);
        foreach ($parts as $p) {
            $trimmed = trim($p);
            if (!empty($trimmed)) $daysList[] = $trimmed;
        }
    }
}
if (empty($daysList)) {
    $daysList = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
}
?>

<style>
    .profile-hero {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.92), rgba(30, 58, 138, 0.95)), 
                    url('cover_images/<?php echo htmlspecialchars(!empty($lawyer['cover image']) ? $lawyer['cover image'] : 'Assign#1.PNG'); ?>');
        background-size: cover;
        background-position: center;
        color: #ffffff;
        padding: 80px 0 60px;
        position: relative;
    }

    .profile-avatar-box {
        width: 170px;
        height: 170px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
        object-fit: cover;
        background: #f1f5f9;
        margin-bottom: 20px;
    }

    .profile-name {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 8px;
    }

    .profile-specialty {
        display: inline-block;
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.4);
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        padding: 30px;
        margin-bottom: 30px;
    }

    .section-headline {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-headline i {
        color: #d97706;
    }

    .info-list-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px 25px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #e2e8f0;
    }
    .info-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
    }

    .day-pill-badge {
        display: inline-block;
        background: #eff6ff;
        color: #1e3a8a;
        font-size: 13px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 6px;
        margin-right: 6px;
        margin-bottom: 6px;
        border: 1px solid #bfdbfe;
    }

    .booking-cta-box {
        background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
        color: #ffffff;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
    }
    .booking-cta-fee {
        font-size: 32px;
        font-weight: 800;
        color: #34d399;
        margin: 10px 0;
    }

    @media (max-width: 768px) {
        .info-list-grid {
            grid-template-columns: 1fr;
        }
        .profile-name {
            font-size: 28px;
        }
    }
</style>

<!-- Hero Section -->
<div class="profile-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center text-md-left">
                <img src="uploads/<?php echo htmlspecialchars(!empty($lawyer['image']) ? $lawyer['image'] : 'default_lawyer.png'); ?>" alt="<?php echo htmlspecialchars($lawyer['name']); ?>" class="profile-avatar-box" onerror="this.src='images/lawyer 1.jpg'">
            </div>
            <div class="col-md-9 text-center text-md-left">
                <span class="profile-specialty"><i class="fa fa-shield-halved mr-1"></i> <?php echo htmlspecialchars($lawyer['cat_name']); ?></span>
                <h1 class="profile-name"><?php echo htmlspecialchars($lawyer['name'] . ' ' . ($lawyer['last name'] ?? '')); ?></h1>
                <p style="color: #cbd5e1; font-size: 16px; margin-bottom: 20px;">
                    <i class="fa fa-graduation-cap mr-1 text-warning"></i> <?php echo htmlspecialchars($lawyer['degree'] ?: 'Legal Practitioner'); ?> | 
                    <i class="fa fa-building-columns mr-1 text-warning"></i> <?php echo htmlspecialchars($lawyer['university'] ?: 'Law School'); ?>
                </p>
                <?php 
                    $cleanLawyerPhone = preg_replace('/[^0-9]/', '', $lawyer['number']);
                    $waProfilePhone = $cleanLawyerPhone;
                    if (str_starts_with($waProfilePhone, '0')) {
                        $waProfilePhone = '92' . substr($waProfilePhone, 1);
                    } elseif (!str_starts_with($waProfilePhone, '92') && strlen($waProfilePhone) === 10) {
                        $waProfilePhone = '92' . $waProfilePhone;
                    }
                    $waInquiryMsg = urlencode("Hello Advocate " . $lawyer['name'] . ", I found your profile on Lawfirm and would like to inquire about a legal consultation regarding " . $lawyer['cat_name'] . ".");
                ?>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;" class="justify-content-center justify-content-md-start">
                    <a href="appo/appoint.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-primary" style="background:#d97706; border-color:#d97706; font-size:15px; padding:10px 22px; font-weight:600;">
                        <i class="fa fa-calendar-check mr-1"></i> Book Consultation (PKR <?php echo number_format($lawyer['fee']); ?>)
                    </a>
                    <a href="https://wa.me/<?php echo $waProfilePhone; ?>?text=<?php echo $waInquiryMsg; ?>" target="_blank" class="btn btn-success" style="background:#25d366; border-color:#25d366; font-size:15px; padding:10px 20px; font-weight:600;">
                        <i class="fab fa-whatsapp mr-1"></i> WhatsApp Inquiry
                    </a>
                    <?php if ($isOwnProfile): ?>
                        <a href="lawyer_appointments.php" class="btn btn-info" style="background:#2563eb; border-color:#2563eb; font-size:14px; padding:10px 18px; font-weight:600;">
                            <i class="fa fa-calendar-days mr-1"></i> View Client Consultations
                        </a>
                        <a href="update_law.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-default" style="background:rgba(255,255,255,0.2); border-color:#fff; color:#fff; padding:10px 18px;">
                            <i class="fa fa-pen-to-square mr-1"></i> Edit Profile & Schedule
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 60px;">
    <div class="row">
        <!-- Main Bio & Details (8 Columns) -->
        <div class="col-md-8">
            <!-- Biography -->
            <div class="profile-card">
                <h3 class="section-headline"><i class="fa fa-user-tie"></i> Professional Biography & Credentials</h3>
                <div style="font-size: 15px; line-height: 1.8; color: #334155; white-space: pre-line;">
                    <?php echo htmlspecialchars(!empty($lawyer['about me']) ? $lawyer['about me'] : 'Practicing legal advocate specializing in high-stakes dispute resolution, client counseling, and courtroom litigation.'); ?>
                </div>

                <?php if (!empty($lawyer['description'])): ?>
                    <div style="background: #f8fafc; border-left: 4px solid #d97706; padding: 16px; margin-top: 20px; border-radius: 4px;">
                        <strong style="color:#0f172a; font-size:14px;">Key Practice Areas & Services:</strong>
                        <p style="margin: 4px 0 0; color:#475569; font-size:14px;"><?php echo htmlspecialchars($lawyer['description']); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Credentials & Practice Facts -->
            <div class="profile-card">
                <h3 class="section-headline"><i class="fa fa-certificate"></i> Verified Legal Information</h3>
                <div class="info-list-grid">
                    <div class="info-item">
                        <span class="info-label">Bar Council Registration ID</span>
                        <span class="info-value"><code><?php echo htmlspecialchars($lawyer['bar council'] ?: 'BC-Verified'); ?></code></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Years in Legal Practice</span>
                        <span class="info-value"><?php echo $expYears > 0 ? $expYears . ' Years (Since ' . htmlspecialchars($lawyer['since']) . ')' : 'Licensed Advocate'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Degree & Qualification</span>
                        <span class="info-value"><?php echo htmlspecialchars($lawyer['degree'] ?: 'LL.B'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Law School / Alma Mater</span>
                        <span class="info-value"><?php echo htmlspecialchars($lawyer['university'] ?: 'Recognized Bar Institution'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Languages Spoken</span>
                        <span class="info-value"><?php echo htmlspecialchars($lawyer['language'] ?: 'English, Urdu'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Chambers / Office Location</span>
                        <span class="info-value"><?php echo htmlspecialchars($lawyer['address'] ?: 'Karachi, Pakistan'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Consultation Card (4 Columns) -->
        <div class="col-md-4">
            <div class="booking-cta-box mb-4">
                <h4 style="color:#ffffff; font-weight:700; font-family:'Playfair Display', serif;">Consultation Session</h4>
                <p style="color:#cbd5e1; font-size:13px;">Book a verified one-on-one session with <?php echo htmlspecialchars($lawyer['name']); ?>.</p>
                <div class="booking-cta-fee">
                    PKR <?php echo number_format($lawyer['fee']); ?>
                </div>
                <small style="color:#94a3b8; display:block; margin-bottom:20px;">Initial Case Review & Consultation Charges</small>
                <a href="appo/appoint.php?id=<?php echo $lawyer['id']; ?>" class="btn btn-warning btn-block" style="background:#fbbf24; border-color:#fbbf24; color:#0f172a; font-weight:700; font-size:16px; padding:12px;">
                    <i class="fa fa-calendar-plus mr-1"></i> Book Appointment Now
                </a>
            </div>

            <div class="profile-card">
                <h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 15px;">
                    <i class="fa fa-clock text-warning mr-1"></i> Consultation Hours
                </h4>
                <div class="mb-3">
                    <span class="info-label">Timing:</span>
                    <div style="font-weight: 600; color: #1e293b; font-size: 14px;">
                        <?php echo htmlspecialchars($lawyer['Time'] ?: '09:00 AM to 05:00 PM'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="info-label">Available Days:</span>
                    <div style="margin-top: 8px;">
                        <?php foreach ($daysList as $d): ?>
                            <span class="day-pill-badge"><?php echo htmlspecialchars($d); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div style="border-top: 1px solid #f1f5f9; padding-top: 15px; margin-top: 15px;">
                    <span class="info-label">Direct Contact (Inquiries):</span>
                    <div style="font-size: 13px; color: #475569; margin-top: 4px;">
                        <i class="fa fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($lawyer['number']); ?><br>
                        <i class="fa fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($lawyer['email']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>