<?php
require_once __DIR__ . '/header.php';

$isUserLoggedIn = isLoggedIn() && getCurrentUserType() === 'user';
$userId = getCurrentUserId();
$userEmail = getCurrentUserEmail();

// Lookup for non-logged-in guest or logged-in user
$searchEmail = trim($_GET['lookup_email'] ?? ($isUserLoggedIn ? $userEmail : ''));
$appointments = [];

if (!empty($searchEmail) || $userId) {
    if ($userId && empty($_GET['lookup_email'])) {
        $stmt = $con->prepare("SELECT appointment.*, 
                                      lawyer.name as lawyer_name, lawyer.`last name` as lawyer_lastname, 
                                      lawyer.number as lawyer_phone, lawyer.email as lawyer_email, 
                                      lawyer.address as lawyer_address, lawyer.image as lawyer_image, lawyer.fee as lawyer_fee,
                                      categorie.cat_name 
                               FROM appointment 
                               JOIN lawyer ON appointment.lawyer = lawyer.id 
                               LEFT JOIN categorie ON lawyer.specialist = categorie.cat_id 
                               WHERE appointment.user_id = ? OR appointment.email = ? 
                               ORDER BY appointment.id DESC");
        $stmt->bind_param("is", $userId, $userEmail);
    } else {
        $stmt = $con->prepare("SELECT appointment.*, 
                                      lawyer.name as lawyer_name, lawyer.`last name` as lawyer_lastname, 
                                      lawyer.number as lawyer_phone, lawyer.email as lawyer_email, 
                                      lawyer.address as lawyer_address, lawyer.image as lawyer_image, lawyer.fee as lawyer_fee,
                                      categorie.cat_name 
                               FROM appointment 
                               JOIN lawyer ON appointment.lawyer = lawyer.id 
                               LEFT JOIN categorie ON lawyer.specialist = categorie.cat_id 
                               WHERE appointment.email = ? 
                               ORDER BY appointment.id DESC");
        $stmt->bind_param("s", $searchEmail);
    }

    if ($stmt) {
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        $stmt->close();
    }
}
?>

<style>
    .client-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
        color: #ffffff;
        padding: 50px 0 35px;
        border-bottom: 3px solid #d97706;
    }
    .client-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        color: #ffffff;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .booking-history-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 25px;
        overflow: hidden;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .booking-history-card:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }
    .card-top-bar {
        padding: 14px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .card-main-body {
        padding: 22px;
    }
    .card-bottom-bar {
        padding: 14px 20px;
        background: #fafbfc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .lawyer-mini-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #d97706;
        background: #e2e8f0;
    }
    .status-badge {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-confirmed { background: #d1fae5; color: #065f46; }
    .status-completed { background: #e0e7ff; color: #3730a3; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    .btn-connect-wa {
        background: #25d366;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-connect-wa:hover {
        background: #1ebc59;
    }
    .btn-connect-call {
        background: #1e3a8a;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-connect-call:hover {
        background: #0f172a;
    }
    .lookup-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
</style>

<!-- Banner -->
<div class="client-banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span style="color:#fbbf24; font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">
                    <i class="fa fa-user-check mr-1"></i> Client Portal
                </span>
                <h1>My Consultations & Appointments</h1>
                <p style="color: #cbd5e1; margin-bottom: 0;">
                    Track consultation booking statuses, view assigned advocate details, and connect directly with your legal counsel.
                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="appo/appoint.php" class="btn btn-warning" style="background:#d97706; border-color:#d97706; color:#fff; font-size:14px; font-weight:600; padding:10px 18px;">
                    <i class="fa fa-calendar-plus mr-1"></i> Schedule New Consultation
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 35px; margin-bottom: 70px;">
    <?php if (!$isUserLoggedIn): ?>
        <!-- Quick Email Lookup for Guests -->
        <div class="lookup-card">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 style="color:#0f172a; font-weight:700; margin-bottom:4px;">
                        <i class="fa fa-magnifying-glass text-primary mr-1"></i> Find Consultations by Email
                    </h5>
                    <p class="text-muted" style="font-size:13px; margin-bottom:0;">
                        Enter the email address you used while booking your appointment to view your live status.
                    </p>
                </div>
                <div class="col-md-6 mt-3 mt-md-0">
                    <form method="GET" action="my_appointments.php" class="d-flex" style="gap:8px;">
                        <input type="email" name="lookup_email" class="form-control" placeholder="Enter your email address" value="<?php echo htmlspecialchars($searchEmail); ?>" required style="height:44px; border-radius:6px;">
                        <button type="submit" class="btn btn-primary" style="height:44px; padding:0 20px; font-weight:600;">
                            Track
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Appointments Feed -->
    <?php if (!empty($appointments)): ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 style="color:#0f172a; font-weight:700; font-family:'Playfair Display', serif; margin-bottom:0;">
                Your Consultation Bookings (<?php echo count($appointments); ?>)
            </h4>
            <span class="badge badge-light text-dark p-2 border" style="font-size:12px;">
                <i class="fa fa-shield text-success mr-1"></i> Verified Advocates
            </span>
        </div>

        <?php foreach ($appointments as $app): 
            $status = strtolower($app['status'] ?? 'pending');
            $cleanPhone = preg_replace('/[^0-9]/', '', $app['lawyer_phone']);
            $waPhone = $cleanPhone;
            if (str_starts_with($waPhone, '0')) {
                $waPhone = '92' . substr($waPhone, 1);
            } elseif (!str_starts_with($waPhone, '92') && strlen($waPhone) === 10) {
                $waPhone = '92' . $waPhone;
            }
            $waText = urlencode("Hello Advocate " . $app['lawyer_name'] . ", I have booked a legal consultation with you (Ref: #APT-" . str_pad($app['id'], 5, '0', STR_PAD_LEFT) . ") for " . date('D, M j, Y', strtotime($app['appointment_date'])) . " at " . $app['appointment_time'] . ". Could you please confirm the session details?");
        ?>
            <div class="booking-history-card">
                <div class="card-top-bar">
                    <div>
                        <strong style="color:#1e3a8a; font-size:15px;">
                            <i class="fa fa-hashtag"></i> APT-<?php echo str_pad($app['id'], 5, '0', STR_PAD_LEFT); ?>
                        </strong>
                        <span class="text-muted ml-2" style="font-size:12px;">
                            Submitted on <?php echo date('M j, Y, g:i A', strtotime($app['created_at'])); ?>
                        </span>
                    </div>
                    <div>
                        <?php if ($status === 'confirmed'): ?>
                            <span class="status-badge status-confirmed"><i class="fa fa-circle-check"></i> Confirmed</span>
                        <?php elseif ($status === 'completed'): ?>
                            <span class="status-badge status-completed"><i class="fa fa-award"></i> Completed</span>
                        <?php elseif ($status === 'cancelled'): ?>
                            <span class="status-badge status-cancelled"><i class="fa fa-circle-xmark"></i> Cancelled</span>
                        <?php else: ?>
                            <span class="status-badge status-pending"><i class="fa fa-clock"></i> Pending Review</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-main-body">
                    <div class="row align-items-center">
                        <!-- Lawyer Info -->
                        <div class="col-md-5">
                            <div class="d-flex align-items-center">
                                <img src="uploads/<?php echo htmlspecialchars(!empty($app['lawyer_image']) ? $app['lawyer_image'] : 'default_lawyer.png'); ?>" alt="Lawyer" class="lawyer-mini-avatar mr-3" onerror="this.src='images/lawyer 1.jpg'">
                                <div>
                                    <span style="background:#eff6ff; color:#1e3a8a; font-size:11px; font-weight:700; padding:2px 8px; border-radius:4px; text-transform:uppercase;">
                                        <?php echo htmlspecialchars($app['cat_name'] ?? 'Legal Counsel'); ?>
                                    </span>
                                    <h4 style="font-size:18px; font-weight:700; color:#0f172a; margin:4px 0 2px;">
                                        <a href="profile.php?id=<?php echo $app['lawyer']; ?>" style="color:#0f172a; text-decoration:none;">
                                            Advocate <?php echo htmlspecialchars($app['lawyer_name'] . ' ' . ($app['lawyer_lastname'] ?? '')); ?>
                                        </a>
                                    </h4>
                                    <div style="font-size:12px; color:#64748b;">
                                        <i class="fa fa-location-dot mr-1 text-muted"></i> <?php echo htmlspecialchars($app['lawyer_address'] ?: 'Karachi, Pakistan'); ?>
                                    </div>
                                    <div style="font-size:13px; font-weight:700; color:#059669; margin-top:2px;">
                                        Fee: PKR <?php echo number_format($app['lawyer_fee']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scheduled Date & Time -->
                        <div class="col-md-3 mt-3 mt-md-0">
                            <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Scheduled Slot</div>
                            <div style="font-size:15px; font-weight:700; color:#1e293b; margin-top:2px;">
                                <i class="fa fa-calendar-day text-info mr-1"></i> 
                                <?php echo !empty($app['appointment_date']) ? date('l, M j, Y', strtotime($app['appointment_date'])) : 'Date TBD'; ?>
                            </div>
                            <div style="font-size:14px; font-weight:600; color:#d97706; margin-top:2px;">
                                <i class="fa fa-clock mr-1"></i> <?php echo htmlspecialchars($app['appointment_time'] ?: ($app['available'] ?? 'Standard slot')); ?>
                            </div>
                        </div>

                        <!-- Case Brief -->
                        <div class="col-md-4 mt-3 mt-md-0">
                            <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Your Case Summary</div>
                            <p style="font-size:13px; color:#475569; background:#f8fafc; padding:10px 12px; border-radius:6px; border:1px solid #e2e8f0; margin-top:4px; margin-bottom:0;">
                                <?php echo htmlspecialchars(!empty($app['message']) ? $app['message'] : 'Initial case consultation request.'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-bottom-bar">
                    <div>
                        <?php if ($status === 'confirmed'): ?>
                            <span class="text-success" style="font-size:13px; font-weight:600;">
                                <i class="fa fa-circle-check mr-1"></i> Appointment Confirmed by Advocate. You may connect directly below.
                            </span>
                        <?php elseif ($status === 'pending'): ?>
                            <span class="text-warning" style="font-size:13px; font-weight:600;">
                                <i class="fa fa-hourglass-start mr-1"></i> Advocate is reviewing your booking request. Status will update shortly.
                            </span>
                        <?php elseif ($status === 'completed'): ?>
                            <span class="text-primary" style="font-size:13px; font-weight:600;">
                                <i class="fa fa-award mr-1"></i> Consultation successfully concluded.
                            </span>
                        <?php elseif ($status === 'cancelled'): ?>
                            <span class="text-danger" style="font-size:13px; font-weight:600;">
                                <i class="fa fa-circle-xmark mr-1"></i> This appointment was cancelled. You may schedule with another advocate.
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex align-items-center" style="gap:8px;">
                        <a href="profile.php?id=<?php echo $app['lawyer']; ?>" class="btn btn-sm btn-default" style="border:1px solid #cbd5e1; font-size:13px; color:#1e293b;">
                            <i class="fa fa-user mr-1"></i> View Profile
                        </a>
                        <a href="https://wa.me/<?php echo $waPhone; ?>?text=<?php echo $waText; ?>" target="_blank" class="btn-connect-wa">
                            <i class="fab fa-whatsapp"></i> WhatsApp Advocate
                        </a>
                        <a href="tel:<?php echo htmlspecialchars($app['lawyer_phone']); ?>" class="btn-connect-call">
                            <i class="fa fa-phone"></i> Call Chambers
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5" style="background:#ffffff; border-radius:12px; border:1px dashed #cbd5e1; padding:40px 20px;">
            <i class="fa fa-calendar-plus fa-3x text-muted mb-3"></i>
            <h4 style="color:#1e293b;">No Consultation Records Found</h4>
            <p class="text-muted" style="max-width:480px; margin:0 auto 20px;">
                <?php echo (!empty($searchEmail)) ? "We could not find any appointments under <strong>" . htmlspecialchars($searchEmail) . "</strong>. Please check your email or book a new consultation." : "You haven't booked any legal consultations yet. Browse our verified advocates to get started."; ?>
            </p>
            <a href="lawyer.php" class="btn btn-primary" style="padding:10px 22px; font-weight:600;">
                <i class="fa fa-scale-balanced mr-1"></i> Browse Verified Advocates
            </a>
        </div>
    <?php endif; ?>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>
