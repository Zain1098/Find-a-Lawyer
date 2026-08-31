<?php
require_once __DIR__ . '/header.php';

$query = "SELECT lawyer.*, categorie.cat_name FROM lawyer 
          LEFT JOIN categorie ON lawyer.specialist = categorie.cat_id 
          ORDER BY lawyer.id DESC";
$rows = mysqli_query($con, $query);
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Registered Legal Practitioners</h4>
                    <span class="ml-1">Directory of all registered lawyers and advocates</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="../website/Login&Singup/Lawyer%20Singup/signup_lawyer.php" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fa fa-user-plus"></i> Register New Lawyer
                </a>
            </div>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Verified Advocates Directory</h4>
                        <span class="badge badge-primary px-3 py-2"><?php echo mysqli_num_rows($rows); ?> Verified Practitioners</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive-sm text-dark">
                                <thead class="thead-light">
                                    <tr>
                                        <th># ID</th>
                                        <th>Advocate</th>
                                        <th>Practice Area</th>
                                        <th>Contact Info</th>
                                        <th>Bar Council ID</th>
                                        <th>Experience</th>
                                        <th>Fee (PKR)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($rows) > 0): ?>
                                        <?php while ($data = mysqli_fetch_assoc($rows)): ?>
                                            <tr>
                                                <td class="font-weight-bold">#<?php echo $data['id']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="../website/uploads/<?php echo htmlspecialchars(!empty($data['image']) ? $data['image'] : 'default_lawyer.png'); ?>" alt="Avatar" style="width:44px; height:44px; border-radius:50%; object-fit:cover;" class="mr-2 border border-primary" onerror="this.src='../website/images/lawyer 1.jpg'">
                                                        <div>
                                                            <strong class="text-primary"><?php echo htmlspecialchars($data['name'] . ' ' . ($data['last name'] ?? '')); ?></strong>
                                                            <div style="font-size: 11px; color: #64748b;"><?php echo htmlspecialchars($data['degree'] ?? 'LL.B'); ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light border border-secondary text-dark px-2 py-1">
                                                        <i class="fa fa-scale-balanced text-primary mr-1"></i>
                                                        <?php echo htmlspecialchars($data['cat_name'] ?? 'General Practice'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div style="font-size: 13px;"><i class="fa fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($data['email']); ?></div>
                                                    <div style="font-size: 12px; color:#475569;"><i class="fa fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($data['number']); ?></div>
                                                </td>
                                                <td>
                                                    <code><?php echo htmlspecialchars($data['bar council'] ?: 'BC-Verified'); ?></code>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold"><?php echo htmlspecialchars($data['since'] ? (date('Y') - intval($data['since'])) . ' yrs' : 'N/A'); ?></span>
                                                    <div style="font-size: 11px; color:#94a3b8;">Since <?php echo htmlspecialchars($data['since'] ?: 'N/A'); ?></div>
                                                </td>
                                                <td>
                                                    <strong class="text-success">PKR <?php echo number_format($data['fee']); ?></strong>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="../website/profile.php?id=<?php echo $data['id']; ?>" target="_blank" class="btn btn-sm btn-info text-white" title="View Public Profile">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <a href="../website/update_law.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-secondary text-white" title="Edit Details">
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        <a href="law_delete.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete profile for <?php echo addslashes($data['name']); ?>?');" title="Delete Profile">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">No lawyers registered yet.</td>
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