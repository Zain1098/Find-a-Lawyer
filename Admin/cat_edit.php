<?php
require_once __DIR__ . '/header.php';

$cat_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cat = null;

if ($cat_id > 0) {
    $stmt = $con->prepare("SELECT * FROM categorie WHERE cat_id = ?");
    $stmt->bind_param("i", $cat_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $cat = $res->fetch_assoc();
    }
    $stmt->close();
}

if (!$cat) {
    echo "<script>alert('Category not found'); window.location.href='cat_show.php';</script>";
    exit();
}

$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_cat'])) {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['desc'] ?? '');

    if (empty($name)) {
        $error = "Category name cannot be empty.";
    } else {
        $stmt_up = $con->prepare("UPDATE categorie SET cat_name = ?, cat_desc = ? WHERE cat_id = ?");
        $stmt_up->bind_param("ssi", $name, $desc, $cat_id);
        if ($stmt_up->execute()) {
            $msg = "Category updated successfully!";
            $cat['cat_name'] = $name;
            $cat['cat_desc'] = $desc;
        } else {
            $error = "Update failed: " . htmlspecialchars($stmt_up->error);
        }
        $stmt_up->close();
    }
}
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Edit Practice Category</h4>
                    <span class="ml-1">Update legal specialization details</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="cat_show.php">Categories</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Category</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Edit Category #<?php echo $cat['cat_id']; ?></h4>
                        <a href="cat_show.php" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left"></i> Back to Categories</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($msg)): ?>
                            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $msg; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="update_cat" value="1">
                            <div class="form-group">
                                <label class="font-weight-bold">Practice Area / Category Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($cat['cat_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Description / Overview</label>
                                <textarea name="desc" class="form-control" rows="3" placeholder="Brief overview of this practice area..."><?php echo htmlspecialchars($cat['cat_desc'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Category Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>