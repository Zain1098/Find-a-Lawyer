<?php
require_once __DIR__ . '/header.php';

$filter = trim($_GET['filter'] ?? 'all');
$where_clause = "";
if (in_array($filter, ['pending', 'confirmed', 'completed', 'cancelled'])) {
    $where_clause = "WHERE appointment.status = '" . mysqli_real_escape_string($con, $filter) . "'";
}

$q = "SELECT appointment.*, 
             lawyer.name as lawyer_name, lawyer.`last name` as lawyer_lastname, lawyer.fee, lawyer.image as lawyer_image, lawyer.number as lawyer_phone,
             categorie.cat_name
      FROM appointment 
      LEFT JOIN lawyer ON appointment.lawyer = lawyer.id 
      LEFT JOIN categorie ON lawyer.specialist = categorie.cat_id 
      $where_clause
      ORDER BY appointment.id DESC";
$result = mysqli_query($con, $q);

// Total counts
$total_all = 0;
$total_pending = 0;
$total_confirmed = 0;
$total_completed = 0;
$total_cancelled = 0;

$count_res = mysqli_query($con, "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as p_cnt,
    SUM(CASE WHEN status='confirmed' THEN 1 ELSE 0 END) as c_cnt,
    SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as comp_cnt,
    SUM(CASE WHEN status='cancelled' THEN 1 ELSE 0 END) as canc_cnt
    FROM appointment");
if ($count_res && $c_row = mysqli_fetch_assoc($count_res)) {
    $total_all = intval($c_row['total'] ?? 0);
    $total_pending = intval($c_row['p_cnt'] ?? 0);
    $total_confirmed = intval($c_row['c_cnt'] ?? 0);
    $total_completed = intval($c_row['comp_cnt'] ?? 0);
    $total_cancelled = intval($c_row['canc_cnt'] ?? 0);
}
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Client Appointments Management</h4>
                    <span class="ml-1">Track and manage consultation bookings across all registered advocates</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="../website/appo/appoint.php" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fa fa-calendar-plus mr-1"></i> New Public Booking Form
                </a>
            </div>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle mr-1"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Filter Nav Pills -->
        <div class="mb-3 d-flex flex-wrap" style="gap:8px;">
            <a href="appointment.php?filter=all" class="btn btn-sm <?php echo ($filter === 'all') ? 'btn-primary' : 'btn-outline-primary'; ?>">
                All Bookings (<?php echo $total_all; ?>)
            </a>
            <a href="appointment.php?filter=pending" class="btn btn-sm <?php echo ($filter === 'pending') ? 'btn-warning' : 'btn-outline-warning'; ?>">
                <i class="fa fa-clock mr-1"></i> Pending (<?php echo $total_pending; ?>)
            </a>
            <a href="appointment.php?filter=confirmed" class="btn btn-sm <?php echo ($filter === 'confirmed') ? 'btn-success' : 'btn-outline-success'; ?>">
                <i class="fa fa-check mr-1"></i> Confirmed (<?php echo $total_confirmed; ?>)
            </a>
            <a href="appointment.php?filter=completed" class="btn btn-sm <?php echo ($filter === 'completed') ? 'btn-info' : 'btn-outline-info'; ?>">
                <i class="fa fa-circle-check mr-1"></i> Completed (<?php echo $total_completed; ?>)
            </a>
            <a href="appointment.php?filter=cancelled" class="btn btn-sm <?php echo ($filter === 'cancelled') ? 'btn-danger' : 'btn-outline-danger'; ?>">
                <i class="fa fa-ban mr-1"></i> Cancelled (<?php echo $total_cancelled; ?>)
            </a>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Consultation Records <?php echo ($filter !== 'all') ? '(' . ucfirst($filter) . ')' : ''; ?></h4>
                        <span class="badge badge-primary px-3 py-2"><?php echo mysqli_num_rows($result); ?> Consultations Listed</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive-sm text-dark">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Ref #</th>
                                        <th>Client Contact</th>
                                        <th>Assigned Advocate</th>
                                        <th>Scheduled Slot</th>
                                        <th>Client Brief</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result) > 0): ?>
                                        <?php while ($row = mysqli_fetch_assoc($result)): 
                                            $st = strtolower($row['status'] ?? 'pending');
                                            $badgeClass = 'badge-pending';
                                            if ($st === 'confirmed') $badgeClass = 'badge-confirmed';
                                            elseif ($st === 'completed') $badgeClass = 'badge-completed';
                                            elseif ($st === 'cancelled') $badgeClass = 'badge-cancelled';

                                            $cleanClientPhone = preg_replace('/[^0-9]/', '', $row['phone']);
                                            $waClientPhone = $cleanClientPhone;
                                            if (str_starts_with($waClientPhone, '0')) {
                                                $waClientPhone = '92' . substr($waClientPhone, 1);
                                            } elseif (!str_starts_with($waClientPhone, '92') && strlen($waClientPhone) === 10) {
                                                $waClientPhone = '92' . $waClientPhone;
                                            }
                                        ?>
                                            <tr>
                                                <td class="font-weight-bold">
                                                    #APT-<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?>
                                                </td>
                                                <td>
                                                    <strong><i class="fa fa-user text-primary mr-1"></i> <?php echo htmlspecialchars($row['name']); ?></strong>
                                                    <div style="font-size: 12px; color:#475569;">
                                                        <i class="fa fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($row['phone']); ?>
                                                        <a href="https://wa.me/<?php echo $waClientPhone; ?>" target="_blank" class="text-success ml-1" title="WhatsApp Client">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </a>
                                                    </div>
                                                    <div style="font-size: 12px; color:#475569;">
                                                        <i class="fa fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($row['email']); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if (!empty($row['lawyer_name'])): ?>
                                                        <div class="d-flex align-items-center">
                                                            <img src="../website/uploads/<?php echo htmlspecialchars(!empty($row['lawyer_image']) ? $row['lawyer_image'] : 'default_lawyer.png'); ?>" alt="Lawyer" style="width:36px; height:36px; border-radius:50%; object-fit:cover;" class="mr-2 border" onerror="this.src='../website/images/lawyer 1.jpg'">
                                                            <div>
                                                                <strong><?php echo htmlspecialchars($row['lawyer_name'] . ' ' . ($row['lawyer_lastname'] ?? '')); ?></strong>
                                                                <div style="font-size: 11px; color:#d97706;"><?php echo htmlspecialchars($row['cat_name'] ?? 'General'); ?></div>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-danger">Unassigned (#<?php echo $row['lawyer']; ?>)</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold text-dark">
                                                        <i class="fa fa-calendar-day text-info mr-1"></i> 
                                                        <?php echo !empty($row['appointment_date']) ? date('D, M j, Y', strtotime($row['appointment_date'])) : 'Date TBD'; ?>
                                                    </div>
                                                    <div style="font-size: 12px; color:#64748b;">
                                                        <i class="fa fa-clock text-muted mr-1"></i> 
                                                        <?php echo htmlspecialchars(!empty($row['appointment_time']) ? $row['appointment_time'] : ($row['available'] ?? 'N/A')); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted" style="max-width: 180px; display: inline-block;">
                                                        <?php echo htmlspecialchars(!empty($row['message']) ? (substr($row['message'], 0, 60) . '...') : 'No additional notes'); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="<?php echo $badgeClass; ?>">
                                                        <?php echo ucfirst($st); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="dropdown d-inline-block">
                                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-toggle="dropdown">
                                                            Status
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item text-success font-weight-bold" href="appointment_status.php?id=<?php echo $row['id']; ?>&status=confirmed">
                                                                <i class="fa fa-check mr-1"></i> Mark Confirmed
                                                            </a>
                                                            <a class="dropdown-item text-primary font-weight-bold" href="appointment_status.php?id=<?php echo $row['id']; ?>&status=completed">
                                                                <i class="fa fa-circle-check mr-1"></i> Mark Completed
                                                            </a>
                                                            <a class="dropdown-item text-warning" href="appointment_status.php?id=<?php echo $row['id']; ?>&status=pending">
                                                                <i class="fa fa-clock mr-1"></i> Mark Pending
                                                            </a>
                                                            <a class="dropdown-item text-danger" href="appointment_status.php?id=<?php echo $row['id']; ?>&status=cancelled">
                                                                <i class="fa fa-ban mr-1"></i> Mark Cancelled
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <a href="appointment_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger ml-1" onclick="return confirm('Are you sure you want to permanently delete appointment #APT-<?php echo $row['id']; ?>?');" title="Delete Booking">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">No appointments found matching this filter.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>