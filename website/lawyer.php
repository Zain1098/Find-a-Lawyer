<?php
require_once __DIR__ . '/header.php';

// Fetch Categories for Filter Dropdown
$categories = [];
$cat_query = ($con && ($con instanceof mysqli)) ? @mysqli_query($con, "SELECT * FROM categorie ORDER BY cat_name ASC") : false;
if ($cat_query) {
    while ($c = mysqli_fetch_assoc($cat_query)) {
        $categories[] = $c;
    }
}

// Build Search & Filter SQL Query
$search = trim($_GET['q'] ?? ($_GET['search'] ?? ''));
$cat_id = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
$sort = trim($_GET['sort'] ?? 'newest');

$where_clauses = ["lawyer.status = 'active'"];
$params = [];
$types = "";

if (!empty($search)) {
    $where_clauses[] = "(lawyer.name LIKE ? OR lawyer.`last name` LIKE ? OR lawyer.degree LIKE ? OR lawyer.university LIKE ? OR lawyer.description LIKE ?)";
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sssss";
}

if ($cat_id > 0) {
    $where_clauses[] = "lawyer.specialist = ?";
    $params[] = $cat_id;
    $types .= "i";
}

$where_sql = implode(" AND ", $where_clauses);

// Sorting
$order_sql = "ORDER BY lawyer.id DESC";
if ($sort === 'exp_high') {
    $order_sql = "ORDER BY lawyer.since ASC"; // Earlier starting year = more experience
} elseif ($sort === 'fee_low') {
    $order_sql = "ORDER BY lawyer.fee ASC";
} elseif ($sort === 'fee_high') {
    $order_sql = "ORDER BY lawyer.fee DESC";
}

$sql = "SELECT lawyer.*, categorie.cat_name 
        FROM lawyer 
        JOIN categorie ON categorie.cat_id = lawyer.specialist 
        WHERE $where_sql 
        $order_sql";

if (!empty($params)) {
    $stmt = $con->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = mysqli_query($con, $sql);
}

$total_found = $res ? mysqli_num_rows($res) : 0;
?>

<style>
    .directory-banner {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 58, 138, 0.9)), url('images/img_bg_1.jpg');
        background-size: cover;
        background-position: center;
        color: #ffffff;
        padding: 70px 0 50px;
        text-align: center;
        margin-bottom: 40px;
    }
    .directory-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 38px;
        color: #ffffff;
        margin-bottom: 12px;
        font-weight: 700;
    }
    .directory-banner p {
        color: #cbd5e1;
        font-size: 16px;
        max-width: 650px;
        margin: 0 auto;
    }

    /* Filter Bar */
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 24px;
        margin-top: -55px;
        margin-bottom: 40px;
        border: 1px solid #e2e8f0;
        position: relative;
        z-index: 10;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1.2fr auto;
        gap: 15px;
        align-items: center;
    }

    .search-input-group {
        position: relative;
    }
    .search-input-group i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .search-input-group input {
        padding-left: 40px;
    }

    /* Lawyer Cards */
    .lawyer-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        transition: all 0.3s ease;
        margin-bottom: 30px;
        display: flex;
        flex-direction: column;
        height: calc(100% - 30px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .lawyer-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }

    .lawyer-card-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }
    .lawyer-card-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #d97706;
        flex-shrink: 0;
        background: #f1f5f9;
    }

    .lawyer-name {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .lawyer-cat-badge {
        display: inline-block;
        background: #eff6ff;
        color: #1e3a8a;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 14px;
        border: 1px solid #bfdbfe;
    }

    .lawyer-details-list {
        list-style: none;
        padding: 0;
        margin: 15px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        padding: 12px 0;
        flex-grow: 1;
    }
    .lawyer-details-list li {
        font-size: 13px;
        color: #475569;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .lawyer-details-list li:last-child {
        margin-bottom: 0;
    }
    .lawyer-details-list li i {
        color: #d97706;
        width: 16px;
        text-align: center;
    }

    .lawyer-fee-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
    }
    .lawyer-fee {
        font-size: 18px;
        font-weight: 700;
        color: #059669;
    }
    .lawyer-fee small {
        font-size: 11px;
        color: #64748b;
        font-weight: 500;
    }

    .lawyer-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    .btn-card-profile {
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        color: #334155;
        padding: 10px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 6px;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-card-profile:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-card-book {
        background: #1e3a8a;
        border: 1.5px solid #1e3a8a;
        color: #ffffff;
        padding: 10px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 6px;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-card-book:hover {
        background: #1e40af;
        color: #ffffff;
    }

    @media (max-width: 991px) {
        .filter-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media (max-width: 600px) {
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Banner -->
<div class="directory-banner">
    <div class="container">
        <h1>Find Top Verified Advocates & Legal Experts</h1>
        <p>Browse licensed attorneys across specializations, check verified Bar Council credentials, view consultation charges, and book appointments instantly.</p>
    </div>
</div>

<div class="container">
    <!-- Filter Search Box -->
    <div class="filter-card">
        <form method="GET" action="lawyer.php">
            <div class="filter-grid">
                <div class="search-input-group">
                    <i class="fa fa-search"></i>
                    <input type="text" name="q" class="form-control" placeholder="Search lawyer name, law firm, degree..." value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div>
                    <select name="cat" class="form-control" onchange="this.form.submit()">
                        <option value="">-- All Practice Areas --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['cat_id']; ?>" <?php echo ($cat_id === intval($cat['cat_id'])) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['cat_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <select name="sort" class="form-control" onchange="this.form.submit()">
                        <option value="newest" <?php echo ($sort === 'newest') ? 'selected' : ''; ?>>Latest Registered</option>
                        <option value="exp_high" <?php echo ($sort === 'exp_high') ? 'selected' : ''; ?>>Most Experienced</option>
                        <option value="fee_low" <?php echo ($sort === 'fee_low') ? 'selected' : ''; ?>>Fee: Low to High</option>
                        <option value="fee_high" <?php echo ($sort === 'fee_high') ? 'selected' : ''; ?>>Fee: High to Low</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Filter</button>
                    <?php if (!empty($search) || $cat_id > 0 || $sort !== 'newest'): ?>
                        <a href="lawyer.php" class="btn btn-default" style="padding: 10px 14px;" title="Reset filters"><i class="fa fa-rotate-left"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 style="font-size: 18px; font-weight: 700; color: #1e293b;">
            Showing <?php echo $total_found; ?> Verified Advocate<?php echo $total_found === 1 ? '' : 's'; ?>
            <?php if (!empty($search)): ?> for "<span class="text-primary"><?php echo htmlspecialchars($search); ?></span>"<?php endif; ?>
        </h4>
        <a href="Login&Singup/Lawyer%20Singup/signup_lawyer.php" class="btn btn-outline-primary btn-sm"><i class="fa fa-user-plus mr-1"></i> Are you a lawyer? Register Here</a>
    </div>

    <!-- Lawyers Card Grid -->
    <div class="row">
        <?php if ($total_found > 0): ?>
            <?php while ($lawyer = mysqli_fetch_assoc($res)): 
                $expYears = $lawyer['since'] ? (date('Y') - intval($lawyer['since'])) : 0;
            ?>
                <div class="col-md-4 col-sm-6">
                    <div class="lawyer-card">
                        <div class="lawyer-card-header">
                            <img src="uploads/<?php echo htmlspecialchars(!empty($lawyer['image']) ? $lawyer['image'] : 'default_lawyer.png'); ?>" alt="<?php echo htmlspecialchars($lawyer['name']); ?>" class="lawyer-card-avatar" onerror="this.src='images/lawyer 1.jpg'">
                            <div>
                                <h3 class="lawyer-name"><?php echo htmlspecialchars($lawyer['name'] . ' ' . ($lawyer['last name'] ?? '')); ?></h3>
                                <span class="lawyer-cat-badge"><?php echo htmlspecialchars($lawyer['cat_name']); ?></span>
                            </div>
                        </div>

                        <ul class="lawyer-details-list">
                            <li><i class="fa fa-graduation-cap"></i> <strong>Degree:</strong> <?php echo htmlspecialchars($lawyer['degree'] ?: 'LL.B'); ?></li>
                            <li><i class="fa fa-building-columns"></i> <strong>University:</strong> <?php echo htmlspecialchars($lawyer['university'] ?: 'Recognized Law School'); ?></li>
                            <li><i class="fa fa-award"></i> <strong>Experience:</strong> <?php echo $expYears > 0 ? $expYears . '+ Years in Practice' : 'Licensed Practitioner'; ?></li>
                            <li><i class="fa fa-language"></i> <strong>Languages:</strong> <?php echo htmlspecialchars($lawyer['language'] ?: 'English, Urdu'); ?></li>
                            <li><i class="fa fa-location-dot"></i> <strong>Chambers:</strong> <?php echo htmlspecialchars(substr($lawyer['address'] ?: 'Karachi, Pakistan', 0, 32)); ?></li>
                        </ul>

                        <div class="lawyer-fee-row">
                            <div>
                                <small>Consultation Fee</small>
                                <div class="lawyer-fee">PKR <?php echo number_format($lawyer['fee']); ?></div>
                            </div>
                            <div>
                                <span class="badge badge-success" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; padding:6px 10px; font-weight:600;">
                                    <i class="fa fa-circle-check mr-1"></i> Bar Verified
                                </span>
                            </div>
                        </div>

                        <div class="lawyer-card-actions">
                            <a href="profile.php?id=<?php echo $lawyer['id']; ?>" class="btn-card-profile"><i class="fa fa-user mr-1"></i> Profile</a>
                            <a href="appo/appoint.php?id=<?php echo $lawyer['id']; ?>" class="btn-card-book"><i class="fa fa-calendar-check mr-1"></i> Book</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-md-12 text-center py-5">
                <div style="background: #ffffff; border-radius: 12px; padding: 50px 20px; border: 1px solid #e2e8f0; max-width: 500px; margin: 0 auto;">
                    <i class="fa fa-user-slash text-muted" style="font-size: 48px; margin-bottom: 16px;"></i>
                    <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 8px;">No Advocates Found</h3>
                    <p style="color: #64748b; font-size: 14px; margin-bottom: 20px;">We couldn't find any lawyers matching your selected search query or filters.</p>
                    <a href="lawyer.php" class="btn btn-primary">Clear Filters & View All</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>