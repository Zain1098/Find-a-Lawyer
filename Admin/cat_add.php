<?php
require_once __DIR__ . '/header.php';

$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_cat'])) {
    $name = trim($_POST['name'] ?? '');
    $desc = trim($_POST['desc'] ?? '');

    if (empty($name)) {
        $error = "Category name is required.";
    } else {
        $stmt = $con->prepare("INSERT INTO categorie (cat_name, cat_desc) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $desc);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: cat_show.php?msg=Category+Added+Successfully");
            exit();
        } else {
            $error = "Failed to add category: " . htmlspecialchars($stmt->error);
            $stmt->close();
        }
    }
}
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Add New Practice Area</h4>
                    <span class="ml-1">Create a new legal specialization category</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="cat_show.php">Categories</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Category</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">New Legal Category</h4>
                        <a href="cat_show.php" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left"></i> View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="submit_cat" value="1">
                            <div class="form-group">
                                <label class="font-weight-bold">Practice Area / Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="e.g. Intellectual Property Law" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Description / Scope of Practice</label>
                                <textarea name="desc" class="form-control" rows="3" placeholder="Brief overview of the legal services under this category..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Create Category</button>
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