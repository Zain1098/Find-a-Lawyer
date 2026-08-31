<?php
require_once __DIR__ . '/header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user = null;

if ($id > 0) {
    $stmt = $con->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
    }
    $stmt->close();
}

if (!$user) {
    echo "<script>alert('User not found'); window.location.href='user_show.php';</script>";
    exit();
}

$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {
        $stmt_up = $con->prepare("UPDATE user SET name = ?, email = ?, phone = ? WHERE id = ?");
        $stmt_up->bind_param("sssi", $name, $email, $phone, $id);
        if ($stmt_up->execute()) {
            $msg = "User updated successfully!";
            $user['name'] = $name;
            $user['email'] = $email;
            $user['phone'] = $phone;
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
                    <h4>Edit Client / User</h4>
                    <span class="ml-1">Manage user account details</span>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="user_show.php">Users</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit User</a></li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User #<?php echo $user['id']; ?> Information</h4>
                        <a href="user_show.php" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left"></i> Back to Users</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($msg)): ?>
                            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $msg; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="update_user" value="1">
                            <div class="form-group">
                                <label class="font-weight-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="e.g. +92 300 1234567">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Changes</button>
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
