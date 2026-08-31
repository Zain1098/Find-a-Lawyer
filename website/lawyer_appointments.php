<?php
require_once __DIR__ . '/header.php';

// Security check: Must be logged in as lawyer or admin
if (!isLoggedIn() || (getCurrentUserType() !== 'lawyer' && getCurrentUserType() !== 'admin')) {
    echo "<div class='container py-5 text-center' style='margin: 80px auto;'>";
    echo "<i class='fa fa-lock fa-3x text-danger mb-3'></i>";
    echo "<h2>Advocate Portal - Access Restricted</h2>";
    echo "<p class='text-muted'>Please sign in with your verified Advocate account to view your client consultation appointments.</p>";
    echo "<a href='Login&Singup/login_email.php' class='btn btn-primary mt-3'><i class='fa fa-arrow-right-to-bracket'></i> Sign In to Portal</a>";
    echo "</div>";
    require_once __DIR__ . '/footer.php';
    exit();
}

$lawyer_id = (getCurrentUserType() === 'lawyer') ? getCurrentUserId() : (isset($_GET['lawyer_id']) ? intval($_GET['lawyer_id']) : getCurrentUserId());

// Handle status updates by the lawyer
$status_msg = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $appointment_id = intval($_POST['appointment_id'] ?? 0);
    $new_status = trim($_POST['status'] ?? '');
    $valid_statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

    if ($appointment_id > 0 && in_array($new_status, $valid_statuses)) {
        // Ensure this appointment belongs to this lawyer
        $check_stmt = $con->prepare("SELECT id FROM appointment WHERE id = ? AND lawyer = ?");
        $check_stmt->bind_param("ii", $appointment_id, $lawyer_id);
        $check_stmt->execute();
        $check_res = $check_stmt->get_result();

        if ($check_res && $check_res->num_rows > 0) {
            $up_stmt = $con->prepare("UPDATE appointment SET status = ? WHERE id = ? AND lawyer = ?");
            $up_stmt->bind_param("sii", $new_status, $appointment_id, $lawyer_id);
            if ($up_stmt->execute()) {
                $status_msg = "Appointment status successfully changed to " . ucfirst($new_status) . ".";
            }
            $up_stmt->close();
        }
        $check_stmt->close();
    }
}

// Fetch lawyer profile details
$l_stmt = $con->prepare("SELECT lawyer.*, categorie.cat_name FROM lawyer JOIN categorie ON categorie.cat_id = lawyer.specialist WHERE lawyer.id = ?");
$l_stmt->bind_param("i", $lawyer_id);
$l_stmt->execute();
$l_res = $l_stmt->get_result();
$lawyer_info = ($l_res && $l_res->num_rows > 0) ? $l_res->fetch_assoc() : null;
$l_stmt->close();

// Fetch appointment counts
$count_total = 0;
$count_pending = 0;
$count_confirmed = 0;
$count_completed = 0;

$count_stmt = $con->prepare("SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_cnt,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_cnt,
    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_cnt
    FROM appointment WHERE lawyer = ?");
$count_stmt->bind_param("i", $lawyer_id);
$count_stmt->execute();
$c_res = $count_stmt->get_result();
if ($c_res && $crow = $c_res->fetch_assoc()) {
    $count_total = intval($crow['total'] ?? 0);
    $count_pending = intval($crow['pending_cnt'] ?? 0);
    $count_confirmed = intval($crow['confirmed_cnt'] ?? 0);
    $count_completed = intval($crow['completed_cnt'] ?? 0);
}
$count_stmt->close();

// Filter status tab
$current_filter = trim($_GET['filter'] ?? 'all');
$filter_sql = "";
$filter_params = [$lawyer_id];
$filter_types = "i";

if (in_array($current_filter, ['pending', 'confirmed', 'completed', 'cancelled'])) {
    $filter_sql = " AND appointment.status = ?";
    $filter_params[] = $current_filter;
    $filter_types .= "s";
}

$app_query = "SELECT appointment.*, user.name as registered_user_name, user.email as registered_user_email, user.phone as registered_user_phone 
              FROM appointment 
              LEFT JOIN user ON appointment.user_id = user.id 
              WHERE appointment.lawyer = ? $filter_sql 
              ORDER BY appointment.id DESC";

$app_stmt = $con->prepare($app_query);
$app_stmt->bind_param($filter_types, ...$filter_params);
$app_stmt->execute();
$appointments_res = $app_stmt->get_result();
?>

<style>
    .portal-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
        color: #ffffff;
        padding: 50px 0 35px;
        border-bottom: 3px solid #d97706;
    }
    .portal-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        color: #ffffff;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .metric-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .metric-pending { background: #fef3c7; color: #d97706; }
    .metric-confirmed { background: #d1fae5; color: #059669; }
    .metric-completed { background: #eff6ff; color: #2563eb; }
    .metric-total { background: #f1f5f9; color: #475569; }

    .appointment-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 20px;
        overflow: hidden;
        transition: border-color 0.2s ease;
    }
    .appointment-card:hover {
        border-color: #cbd5e1;
    }
    .app-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .app-body {
        padding: 20px;
    }
    .app-footer {
        padding: 14px 20px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-confirmed { background: #d1fae5; color: #065f46; }
    .status-completed { background: #e0e7ff; color: #3730a3; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }

    .btn-action-whatsapp {
        background: #25d366;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13px;
        padding: 6px 14px;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-action-whatsapp:hover {
        background: #1ebc59;
        color: #ffffff !important;
    }
    .btn-action-call {
        background: #0284c7;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 13px;
        padding: 6px 14px;
        border-radius: 6px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-action-call:hover {
        background: #0369a1;
        color: #ffffff !important;
    }
    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }
    .filter-pill {
        padding: 8px 18px;
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .filter-pill:hover, .filter-pill.active {
        background: #1e3a8a;
        color: #ffffff !important;
        border-color: #1e3a8a;
    }
</style>

<!-- Portal Banner -->
<div class="portal-banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span style="color:#fbbf24; font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">
                    <i class="fa fa-shield-halved mr-1"></i> Advocate Practice Portal
                </span>
                <h1>Consultation & Client Requests</h1>
                <p style="color: #cbd5e1; margin-bottom: 0;">
                    Welcome back, Advocate <?php echo htmlspecialchars($lawyer_info ? ($lawyer_info['name'] . ' ' . $lawyer_info['last name']) : getCurrentUserName()); ?>. Manage your upcoming consultations, review client briefs, and confirm appointments.
                </p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="profile.php?id=<?php echo $lawyer_id; ?>" class="btn btn-default" style="background:rgba(255,255,255,0.15); color:#fff; border-color:rgba(255,255,255,0.3); font-size:13px;">
                    <i class="fa fa-eye mr-1"></i> View Public Profile
                </a>
                <a href="update_law.php?id=<?php echo $lawyer_id; ?>" class="btn btn-warning" style="background:#d97706; border-color:#d97706; color:#fff; font-size:13px; font-weight:600;">
                    <i class="fa fa-user-pen mr-1"></i> Update Schedule
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 35px; margin-bottom: 70px;">
    <?php if (!empty($status_msg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-circle-check mr-2"></i> <?php echo htmlspecialchars($status_msg); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- 4 Metric Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="metric-card">
                <div>
                    <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Pending Review</div>
                    <div style="font-size:26px; font-weight:800; color:#d97706;"><?php echo $count_pending; ?></div>
                </div>
                <div class="metric-icon metric-pending">
                    <i class="fa fa-hourglass-half"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="metric-card">
                <div>
                    <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Confirmed Sessions</div>
                    <div style="font-size:26px; font-weight:800; color:#059669;"><?php echo $count_confirmed; ?></div>
                </div>
                <div class="metric-icon metric-confirmed">
                    <i class="fa fa-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="metric-card">
                <div>
                    <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Completed Cases</div>
                    <div style="font-size:26px; font-weight:800; color:#2563eb;"><?php echo $count_completed; ?></div>
                </div>
                <div class="metric-icon metric-completed">
                    <i class="fa fa-circle-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="metric-card">
                <div>
                    <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Total Bookings</div>
                    <div style="font-size:26px; font-weight:800; color:#1e293b;"><?php echo $count_total; ?></div>
                </div>
                <div class="metric-icon metric-total">
                    <i class="fa fa-briefcase"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="lawyer_appointments.php?filter=all" class="filter-pill <?php echo ($current_filter === 'all') ? 'active' : ''; ?>">
            All Requests (<?php echo $count_total; ?>)
        </a>
        <a href="lawyer_appointments.php?filter=pending" class="filter-pill <?php echo ($current_filter === 'pending') ? 'active' : ''; ?>">
            <i class="fa fa-clock mr-1 text-warning"></i> Pending Review (<?php echo $count_pending; ?>)
        </a>
        <a href="lawyer_appointments.php?filter=confirmed" class="filter-pill <?php echo ($current_filter === 'confirmed') ? 'active' : ''; ?>">
            <i class="fa fa-check mr-1 text-success"></i> Confirmed (<?php echo $count_confirmed; ?>)
        </a>
        <a href="lawyer_appointments.php?filter=completed" class="filter-pill <?php echo ($current_filter === 'completed') ? 'active' : ''; ?>">
            <i class="fa fa-circle-check mr-1 text-primary"></i> Completed (<?php echo $count_completed; ?>)
        </a>
        <a href="lawyer_appointments.php?filter=cancelled" class="filter-pill <?php echo ($current_filter === 'cancelled') ? 'active' : ''; ?>">
            <i class="fa fa-ban mr-1 text-danger"></i> Cancelled
        </a>
    </div>

    <!-- Appointment List -->
    <?php if ($appointments_res && $appointments_res->num_rows > 0): ?>
        <?php while ($app = $appointments_res->fetch_assoc()): 
            $status = strtolower($app['status'] ?? 'pending');
            $cleanPhone = preg_replace('/[^0-9]/', '', $app['phone']);
            // Pakistan phone prefix helper for whatsapp
            $waPhone = $cleanPhone;
            if (str_starts_with($waPhone, '0')) {
                $waPhone = '92' . substr($waPhone, 1);
            } elseif (!str_starts_with($waPhone, '92') && strlen($waPhone) === 10) {
                $waPhone = '92' . $waPhone;
            }
            $waMessage = urlencode("Hello " . $app['name'] . ", this is Advocate " . ($lawyer_info['name'] ?? 'Lawfirm') . " regarding your scheduled consultation on " . date('D, M j, Y', strtotime($app['appointment_date'])) . " at " . $app['appointment_time'] . " (Ref: #APT-" . str_pad($app['id'], 5, '0', STR_PAD_LEFT) . ").");
        ?>
            <div class="appointment-card">
                <div class="app-header">
                    <div>
                        <strong style="color:#1e3a8a; font-size:15px;">
                            <i class="fa fa-hashtag"></i> APT-<?php echo str_pad($app['id'], 5, '0', STR_PAD_LEFT); ?>
                        </strong>
                        <span class="text-muted ml-2" style="font-size:12px;">
                            Booked on <?php echo date('M j, Y, g:i A', strtotime($app['created_at'])); ?>
                        </span>
                    </div>
                    <div>
                        <span class="status-badge status-<?php echo $status; ?>">
                            <?php echo ucfirst($status); ?>
                        </span>
                    </div>
                </div>

                <div class="app-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Client Details</div>
                            <div style="font-size:17px; font-weight:700; color:#0f172a; margin-top:2px;">
                                <i class="fa fa-user-circle text-primary mr-1"></i> <?php echo htmlspecialchars($app['name']); ?>
                            </div>
                            <div style="font-size:13px; color:#475569; margin-top:4px;">
                                <i class="fa fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($app['phone']); ?><br>
                                <i class="fa fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($app['email']); ?>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3 mt-md-0">
                            <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Scheduled Slot</div>
                            <div style="font-size:16px; font-weight:700; color:#1e293b; margin-top:2px;">
                                <i class="fa fa-calendar-day text-info mr-1"></i> 
                                <?php echo !empty($app['appointment_date']) ? date('l, M j, Y', strtotime($app['appointment_date'])) : 'Date TBD'; ?>
                            </div>
                            <div style="font-size:14px; font-weight:600; color:#d97706; margin-top:3px;">
                                <i class="fa fa-clock mr-1"></i> <?php echo htmlspecialchars($app['appointment_time'] ?: ($app['available'] ?? 'Standard slot')); ?>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3 mt-md-0">
                            <div style="font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase;">Client Case Brief / Notes</div>
                            <p style="font-size:13px; color:#334155; margin-top:4px; background:#f8fafc; padding:10px 12px; border-radius:6px; border:1px solid #e2e8f0; margin-bottom:0;">
                                <?php echo htmlspecialchars(!empty($app['message']) ? $app['message'] : 'No initial case summary provided by client.'); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="app-footer">
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <a href="https://wa.me/<?php echo $waPhone; ?>?text=<?php echo $waMessage; ?>" target="_blank" class="btn-action-whatsapp">
                            <i class="fab fa-whatsapp"></i> WhatsApp Client
                        </a>
                        <a href="tel:<?php echo htmlspecialchars($app['phone']); ?>" class="btn-action-call">
                            <i class="fa fa-phone"></i> Call Direct
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($app['email']); ?>" class="btn btn-sm btn-default" style="border:1px solid #cbd5e1; font-size:12px; color:#475569;">
                            <i class="fa fa-envelope"></i> Email
                        </a>
                    </div>

                    <div class="d-flex align-items-center" style="gap:8px;">
                        <form method="POST" action="lawyer_appointments.php?filter=<?php echo urlencode($current_filter); ?>" style="display:inline-flex; gap:6px;">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="appointment_id" value="<?php echo $app['id']; ?>">
                            
                            <?php if ($status === 'pending'): ?>
                                <button type="submit" name="status" value="confirmed" class="btn btn-sm btn-success" style="font-weight:600;">
                                    <i class="fa fa-check mr-1"></i> Accept & Confirm
                                </button>
                                <button type="submit" name="status" value="cancelled" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to decline this appointment request?');">
                                    <i class="fa fa-times mr-1"></i> Decline
                                </button>
                            <?php elseif ($status === 'confirmed'): ?>
                                <button type="submit" name="status" value="completed" class="btn btn-sm btn-primary" style="font-weight:600;">
                                    <i class="fa fa-circle-check mr-1"></i> Mark as Completed
                                </button>
                                <button type="submit" name="status" value="cancelled" class="btn btn-sm btn-outline-danger" onclick="return confirm('Cancel this confirmed appointment?');">
                                    <i class="fa fa-ban mr-1"></i> Cancel
                                </button>
                            <?php elseif ($status === 'completed'): ?>
                                <span class="text-success font-weight-bold" style="font-size:13px;">
                                    <i class="fa fa-circle-check"></i> Consultation Concluded
                                </span>
                            <?php elseif ($status === 'cancelled'): ?>
                                <button type="submit" name="status" value="pending" class="btn btn-sm btn-outline-secondary" style="font-size:12px;">
                                    <i class="fa fa-rotate-left mr-1"></i> Reopen as Pending
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="text-center py-5" style="background:#ffffff; border-radius:12px; border:1px dashed #cbd5e1;">
            <i class="fa fa-calendar-xmark fa-3x text-muted mb-3"></i>
            <h4 style="color:#1e293b;">No Consultation Requests Found</h4>
            <p class="text-muted" style="max-width:450px; margin:0 auto 15px;">
                <?php echo ($current_filter !== 'all') ? "There are currently no appointments with '" . ucfirst($current_filter) . "' status." : "You have not received any client appointments yet. Ensure your consultation schedule and profile details are up to date."; ?>
            </p>
            <?php if ($current_filter !== 'all'): ?>
                <a href="lawyer_appointments.php?filter=all" class="btn btn-primary btn-sm">View All Appointments</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$app_stmt->close();
require_once __DIR__ . '/footer.php';
?>
