<?php
require_once __DIR__ . '/header.php';

$sql = "SELECT categorie.*, COUNT(lawyer.id) as lawyer_count 
        FROM categorie 
        LEFT JOIN lawyer ON lawyer.specialist = categorie.cat_id 
        GROUP BY categorie.cat_id 
        ORDER BY categorie.cat_name ASC";
$result = mysqli_query($con, $sql);
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Legal Practice Categories</h4>
                    <span class="ml-1">Manage all available legal specializations</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="cat_add.php" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add Practice Area</a>
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
                        <h4 class="card-title">All Categories</h4>
                        <span class="badge badge-primary px-3 py-2"><?php echo mysqli_num_rows($result); ?> Total Areas</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-responsive-sm text-dark">
                                <thead class="thead-light">
                                    <tr>
                                        <th># ID</th>
                                        <th>Category / Practice Area</th>
                                        <th>Description</th>
                                        <th>Active Lawyers</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td class="font-weight-bold">#<?php echo $data['cat_id']; ?></td>
                                            <td>
                                                <i class="fa fa-folder-open text-warning mr-1"></i>
                                                <strong><?php echo htmlspecialchars($data['cat_name']); ?></strong>
                                            </td>
                                            <td><small class="text-muted"><?php echo htmlspecialchars(!empty($data['cat_desc']) ? substr($data['cat_desc'], 0, 70) . '...' : 'No description provided'); ?></small></td>
                                            <td>
                                                <span class="badge badge-info px-2 py-1"><?php echo $data['lawyer_count']; ?> Lawyers</span>
                                            </td>
                                            <td>
                                                <a href="cat_edit.php?id=<?php echo $data['cat_id']; ?>" class="btn btn-sm btn-info text-white mr-1">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="cat_delete.php?id=<?php echo $data['cat_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete category <?php echo addslashes($data['cat_name']); ?>? Lawyers linked to this category may be affected.');">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
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