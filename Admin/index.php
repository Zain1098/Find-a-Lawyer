<?php
require_once __DIR__ . '/header.php';

// Fetch key dashboard statistics
$total_lawyers = 0;
$l_res = mysqli_query($con, "SELECT COUNT(*) as total FROM lawyer");
if ($l_res && $row = mysqli_fetch_assoc($l_res)) $total_lawyers = $row['total'];

$total_users = 0;
$u_res = mysqli_query($con, "SELECT COUNT(*) as total FROM user");
if ($u_res && $row = mysqli_fetch_assoc($u_res)) $total_users = $row['total'];

$total_appointments = 0;
$pending_appointments = 0;
$a_res = mysqli_query($con, "SELECT COUNT(*) as total, SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as pending_total FROM appointment");
if ($a_res && $row = mysqli_fetch_assoc($a_res)) {
    $total_appointments = $row['total'] ?? 0;
    $pending_appointments = $row['pending_total'] ?? 0;
}

$total_categories = 0;
$c_res = mysqli_query($con, "SELECT COUNT(*) as total FROM categorie");
if ($c_res && $row = mysqli_fetch_assoc($c_res)) $total_categories = $row['total'];

// Recent 5 appointments
$recent_app_query = "SELECT appointment.*, lawyer.name as lawyer_name, lawyer.`last name` as lawyer_lastname, categorie.cat_name 
                     FROM appointment 
                     LEFT JOIN lawyer ON appointment.lawyer = lawyer.id 
                     LEFT JOIN categorie ON lawyer.specialist = categorie.cat_id 
                     ORDER BY appointment.id DESC LIMIT 5";
$recent_apps = mysqli_query($con, $recent_app_query);

// Recent 4 lawyers
$recent_law_query = "SELECT lawyer.*, categorie.cat_name FROM lawyer 
                     LEFT JOIN categorie ON lawyer.specialist = categorie.cat_id 
                     ORDER BY lawyer.id DESC LIMIT 4";
$recent_lawyers = mysqli_query($con, $recent_law_query);
?>

<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Executive Administration Dashboard</h4>
                    <p class="mb-0 text-muted">Real-time overview of advocates, client consultations, and platform operations.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <span class="badge badge-success px-3 py-2 font-weight-bold"><i class="fa fa-server"></i> System Online & Secure</span>
            </div>
        </div>

        <!-- 4 Metric Cards -->
        <div class="row">
            <!-- Total Lawyers -->
            <div class="col-lg-3 col-sm-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1" style="font-size:12px; letter-spacing:0.5px;">Verified Advocates</h6>
                                <h2 class="text-white mb-0 font-weight-bold"><?php echo $total_lawyers; ?></h2>
                            </div>
                            <div style="font-size: 38px; opacity: 0.8;">
                                <i class="fa fa-scale-balanced"></i>
                            </div>
                        </div>
                        <div class="mt-3" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 8px;">
                            <a href="law_show.php" class="text-white" style="font-size: 12px;">View Lawyer Directory <i class="fa fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="col-lg-3 col-sm-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1" style="font-size:12px; letter-spacing:0.5px;">Registered Clients</h6>
                                <h2 class="text-white mb-0 font-weight-bold"><?php echo $total_users; ?></h2>
                            </div>
                            <div style="font-size: 38px; opacity: 0.8;">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                        <div class="mt-3" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 8px;">
                            <a href="user_show.php" class="text-white" style="font-size: 12px;">Manage Client Accounts <i class="fa fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Appointments -->
            <div class="col-lg-3 col-sm-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1" style="font-size:12px; letter-spacing:0.5px;">Consultations Booked</h6>
                                <h2 class="text-white mb-0 font-weight-bold"><?php echo $total_appointments; ?></h2>
                            </div>
                            <div style="font-size: 38px; opacity: 0.8;">
                                <i class="fa fa-calendar-check"></i>
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 8px;">
                            <a href="appointment.php" class="text-white" style="font-size: 12px;">Review All <i class="fa fa-arrow-right ml-1"></i></a>
                            <span class="badge badge-light text-dark font-weight-bold" style="font-size:11px;"><?php echo $pending_appointments; ?> Pending</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Categories -->
            <div class="col-lg-3 col-sm-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 text-uppercase mb-1" style="font-size:12px; letter-spacing:0.5px;">Practice Specializations</h6>
                                <h2 class="text-white mb-0 font-weight-bold"><?php echo $total_categories; ?></h2>
                            </div>
                            <div style="font-size: 38px; opacity: 0.8;">
                                <i class="fa fa-layer-group"></i>
                            </div>
                        </div>
                        <div class="mt-3" style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 8px;">
                            <a href="cat_show.php" class="text-white" style="font-size: 12px;">Manage Practice Areas <i class="fa fa-arrow-right ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments & Active Advocates -->
        <div class="row">
            <!-- Recent Appointments (8 columns) -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title"><i class="fa fa-calendar-days text-primary mr-1"></i> Recent Client Appointments</h4>
                        <a href="appointment.php" class="btn btn-outline-primary btn-sm">View All Appointments</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive-sm text-dark mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Ref #</th>
                                        <th>Client</th>
                                        <th>Advocate</th>
                                        <th>Date & Slot</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($recent_apps && mysqli_num_rows($recent_apps) > 0): ?>
                                        <?php while ($app = mysqli_fetch_assoc($recent_apps)): 
                                            $st = strtolower($app['status'] ?? 'pending');
                                            $badgeClass = 'badge-pending';
                                            if ($st === 'confirmed') $badgeClass = 'badge-confirmed';
                                            elseif ($st === 'completed') $badgeClass = 'badge-completed';
                                            elseif ($st === 'cancelled') $badgeClass = 'badge-cancelled';
                                        ?>
                                            <tr>
                                                <td class="font-weight-bold">#APT-<?php echo str_pad($app['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($app['name']); ?></strong>
                                                    <div style="font-size:11px; color:#64748b;"><?php echo htmlspecialchars($app['phone']); ?></div>
                                                </td>
                                                <td>
                                                    <span class="text-primary font-weight-bold"><?php echo htmlspecialchars($app['lawyer_name'] . ' ' . ($app['lawyer_lastname'] ?? '')); ?></span>
                                                    <div style="font-size:11px; color:#d97706;"><?php echo htmlspecialchars($app['cat_name'] ?? 'Legal Counsel'); ?></div>
                                                </td>
                                                <td>
                                                    <div><?php echo !empty($app['appointment_date']) ? date('M j, Y', strtotime($app['appointment_date'])) : 'TBD'; ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars(!empty($app['appointment_time']) ? $app['appointment_time'] : ($app['available'] ?? '')); ?></small>
                                                </td>
                                                <td>
                                                    <span class="<?php echo $badgeClass; ?>"><?php echo ucfirst($st); ?></span>
                                                </td>
                                                <td>
                                                    <a href="appointment.php" class="btn btn-sm btn-outline-secondary" title="View details"><i class="fa fa-arrow-right"></i></a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No appointments booked yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Advocates (4 columns) -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title"><i class="fa fa-user-tie text-primary mr-1"></i> New Advocates</h4>
                        <a href="law_show.php" class="btn btn-outline-secondary btn-sm">All</a>
                    </div>
                    <div class="card-body">
                        <?php if ($recent_lawyers && mysqli_num_rows($recent_lawyers) > 0): ?>
                            <?php while ($law = mysqli_fetch_assoc($recent_lawyers)): ?>
                                <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom: 1px solid #f1f5f9;">
                                    <img src="../website/uploads/<?php echo htmlspecialchars(!empty($law['image']) ? $law['image'] : 'default_lawyer.png'); ?>" alt="Lawyer" style="width:46px; height:46px; border-radius:50%; object-fit:cover;" class="mr-3 border" onerror="this.src='../website/images/lawyer 1.jpg'">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 font-weight-bold text-dark"><?php echo htmlspecialchars($law['name'] . ' ' . ($law['last name'] ?? '')); ?></h6>
                                        <small class="text-muted d-block"><?php echo htmlspecialchars($law['cat_name'] ?? 'General Practice'); ?></small>
                                        <small class="text-success font-weight-bold">PKR <?php echo number_format($law['fee']); ?> / session</small>
                                    </div>
                                    <a href="../website/profile.php?id=<?php echo $law['id']; ?>" target="_blank" class="btn btn-sm btn-outline-primary" title="View Profile"><i class="fa fa-external-link"></i></a>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted text-center py-3">No advocates registered yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>