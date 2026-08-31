<?php
require_once __DIR__ . '/header.php';

$sql = "SELECT * FROM user ORDER BY id DESC";
$result = mysqli_query($con, $sql);
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Registered Clients / Users</h4>
                    <span class="ml-1">List of all registered client accounts</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Users</a></li>
                </ol>
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
                        <h4 class="card-title">User Accounts</h4>
                        <span class="badge badge-primary px-3 py-2"><?php echo mysqli_num_rows($result); ?> Registered Users</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive-sm text-dark">
                                <thead class="thead-light">
                                    <tr>
                                        <th># ID</th>
                                        <th>Client Name</th>
                                        <th>Email Address</th>
                                        <th>Phone Number</th>
                                        <th>Joined On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($result) > 0): ?>
                                        <?php while ($data = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td class="font-weight-bold">#<?php echo $data['id']; ?></td>
                                                <td>
                                                    <i class="fa fa-user-circle text-primary mr-1"></i>
                                                    <strong><?php echo htmlspecialchars($data['name']); ?></strong>
                                                </td>
                                                <td><?php echo htmlspecialchars($data['email']); ?></td>
                                                <td><?php echo htmlspecialchars(!empty($data['phone']) ? $data['phone'] : 'N/A'); ?></td>
                                                <td><small class="text-muted"><?php echo htmlspecialchars($data['created_at'] ?? 'Recently'); ?></small></td>
                                                <td>
                                                    <a href="user_edit.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-info text-white mr-1">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <a href="user_delete.php?id=<?php echo $data['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete client <?php echo addslashes($data['name']); ?>? This action cannot be undone.');">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">No client users registered yet.</td>
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