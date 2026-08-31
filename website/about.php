<?php
require_once __DIR__ . '/header.php';

// Dynamic lawyers
$lawyers_res = mysqli_query($con, "SELECT lawyer.*, categorie.cat_name FROM lawyer JOIN categorie ON categorie.cat_id = lawyer.specialist WHERE lawyer.status = 'active' ORDER BY lawyer.id DESC LIMIT 3");
?>

<style>
    .about-banner {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 58, 138, 0.9)), url('images/img_bg_1.jpg');
        background-size: cover;
        background-position: center;
        color: #ffffff;
        padding: 80px 0 60px;
        text-align: center;
    }
    .about-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 40px;
        color: #ffffff;
        margin-bottom: 12px;
        font-weight: 700;
    }
    .about-banner p {
        color: #cbd5e1;
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
    }
    .feature-box {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        height: calc(100% - 30px);
    }
    .feature-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #eff6ff;
        color: #1e3a8a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
    }
</style>

<div class="about-banner">
    <div class="container">
        <h1>About Lawfirm Portal</h1>
        <p>Connecting citizens, entrepreneurs, and organizations with top-tier verified legal advocates across Pakistan.</p>
    </div>
</div>

<div class="container" style="margin-top: 60px; margin-bottom: 60px;">
    <!-- Mission & Story -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6 animate-box">
            <h2 style="font-family:'Playfair Display', serif; font-size:32px; font-weight:700; color:#0f172a; margin-bottom:18px;">
                Simplifying Access to Reliable Legal Representation
            </h2>
            <p style="font-size:15px; color:#475569; line-height:1.8;">
                The <strong>Find-a-Lawyer</strong> platform was founded with a singular mission: to eliminate ambiguity in hiring legal practitioners. Finding the right advocate with specialized domain expertise, clear consultation charges, and verified licensing should be straightforward and transparent.
            </p>
            <p style="font-size:15px; color:#475569; line-height:1.8;">
                Our platform validates Bar Council credentials, displays authentic practice experience, and provides a direct, conflict-free channel to book initial case reviews and courtroom consultations.
            </p>
            <div class="mt-4">
                <a href="lawyer.php" class="btn btn-primary btn-lg" style="margin-right:10px;"><i class="fa fa-search mr-1"></i> Browse Directory</a>
                <a href="Login&Singup/Lawyer%20Singup/signup_lawyer.php" class="btn btn-default btn-lg"><i class="fa fa-user-plus mr-1"></i> Join as Advocate</a>
            </div>
        </div>
        <div class="col-md-6 animate-box text-center">
            <img src="images/img_bg_2.jpg" alt="About Lawfirm" class="img-responsive" style="border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
        </div>
    </div>

    <!-- 3 Core Pillars -->
    <div class="row mt-5">
        <div class="col-md-4 animate-box">
            <div class="feature-box">
                <div class="feature-icon"><i class="fa fa-shield-halved"></i></div>
                <h3 style="font-size:20px; font-weight:700; color:#0f172a; margin-bottom:10px;">100% Bar Verified</h3>
                <p style="font-size:14px; color:#64748b; line-height:1.7;">
                    Every registered advocate is cross-referenced with Bar Council membership records to ensure authentic, qualified representation.
                </p>
            </div>
        </div>
        <div class="col-md-4 animate-box">
            <div class="feature-box">
                <div class="feature-icon" style="background:#fef3c7; color:#d97706;"><i class="fa fa-calendar-check"></i></div>
                <h3 style="font-size:20px; font-weight:700; color:#0f172a; margin-bottom:10px;">Instant Online Booking</h3>
                <p style="font-size:14px; color:#64748b; line-height:1.7;">
                    Check working hours in real-time and reserve confidential consultation sessions without endless back-and-forth phone calls.
                </p>
            </div>
        </div>
        <div class="col-md-4 animate-box">
            <div class="feature-box">
                <div class="feature-icon" style="background:#ecfdf5; color:#059669;"><i class="fa fa-scale-balanced"></i></div>
                <h3 style="font-size:20px; font-weight:700; color:#0f172a; margin-bottom:10px;">Domain Specialization</h3>
                <p style="font-size:14px; color:#64748b; line-height:1.7;">
                    Filter legal counsel by specific disciplines: Corporate Law, Criminal Defense, Family/Custody, Taxation, and Real Estate.
                </p>
            </div>
        </div>
    </div>

    <!-- Featured Advocates -->
    <div class="row mt-5">
        <div class="col-md-8 col-md-offset-2 text-center colorlib-heading animate-box">
            <h2>Our Senior Advocates</h2>
            <p>Meet leading advocates registered on our platform offering specialized consultation.</p>
        </div>
    </div>
    <div class="row">
        <?php if ($lawyers_res && mysqli_num_rows($lawyers_res) > 0): ?>
            <?php while ($law = mysqli_fetch_assoc($lawyers_res)): ?>
                <div class="col-md-4 col-sm-4 text-center animate-box">
                    <div class="colorlib-staff" style="padding: 25px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); background:#fff;">
                        <img src="uploads/<?php echo htmlspecialchars(!empty($law['image']) ? $law['image'] : 'default_lawyer.png'); ?>" alt="<?php echo htmlspecialchars($law['name']); ?>" style="width:100px; height:100px; border-radius:50%; object-fit:cover; margin-bottom:15px; border:3px solid #d97706;" onerror="this.src='images/lawyer 1.jpg'">
                        <h3 style="margin-bottom:4px; font-weight:700;"><?php echo htmlspecialchars($law['name'] . ' ' . ($law['last name'] ?? '')); ?></h3>
                        <strong class="role" style="color:#d97706; display:block; margin-bottom:10px;"><?php echo htmlspecialchars($law['cat_name']); ?></strong>
                        <p style="font-size:13px; color:#64748b; min-height:50px;">
                            <?php echo htmlspecialchars(!empty($law['description']) ? (substr($law['description'], 0, 85) . '...') : 'Licensed advocate offering courtroom defense and legal consultation.'); ?>
                        </p>
                        <a href="profile.php?id=<?php echo $law['id']; ?>" class="btn btn-default btn-sm">View Profile</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>