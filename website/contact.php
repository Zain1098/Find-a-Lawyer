<?php
require_once __DIR__ . '/header.php';

$msg = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['send_contact_message'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all required fields.";
    } else {
        // Form submitted successfully
        $msg = "Thank you, " . htmlspecialchars($name) . ". Your inquiry has been received. Our legal support team will get back to you within 24 hours.";
    }
}
?>

<style>
    .contact-banner {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(30, 58, 138, 0.9)), url('images/img_bg_1.jpg');
        background-size: cover;
        background-position: center;
        color: #ffffff;
        padding: 70px 0 50px;
        text-align: center;
    }
    .contact-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 38px;
        color: #ffffff;
        margin-bottom: 12px;
        font-weight: 700;
    }
    .contact-banner p {
        color: #cbd5e1;
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
    }
    .contact-info-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    .contact-item {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        align-items: flex-start;
    }
    .contact-item:last-child {
        margin-bottom: 0;
    }
    .contact-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #eff6ff;
        color: #1e3a8a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
</style>

<div class="contact-banner">
    <div class="container">
        <h1>Contact & Client Support</h1>
        <p>Have questions regarding an appointment or platform registration? Get in touch with our legal administrative office.</p>
    </div>
</div>

<div class="container" style="margin-top: 50px; margin-bottom: 60px;">
    <div class="row">
        <!-- Contact Information (5 Columns) -->
        <div class="col-md-5">
            <div class="contact-info-card">
                <h3 style="font-family:'Playfair Display', serif; font-size:22px; font-weight:700; color:#0f172a; margin-bottom:20px; border-bottom:2px solid #f1f5f9; padding-bottom:10px;">
                    Chamber & Office Details
                </h3>

                <div class="contact-item">
                    <div class="contact-icon"><i class="fa fa-location-dot"></i></div>
                    <div>
                        <strong style="color:#0f172a; display:block; font-size:14px;">Principal Office</strong>
                        <p style="color:#64748b; font-size:13px; margin:0;">High Court Chambers, Court Road, Karachi, Pakistan</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon" style="background:#fef3c7; color:#d97706;"><i class="fa fa-phone"></i></div>
                    <div>
                        <strong style="color:#0f172a; display:block; font-size:14px;">Helpline & WhatsApp</strong>
                        <p style="color:#64748b; font-size:13px; margin:0;"><a href="tel:+923001234567" style="color:#475569;">+92 300 1234567</a></p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon" style="background:#ecfdf5; color:#059669;"><i class="fa fa-envelope"></i></div>
                    <div>
                        <strong style="color:#0f172a; display:block; font-size:14px;">Electronic Mail</strong>
                        <p style="color:#64748b; font-size:13px; margin:0;"><a href="mailto:info@lawfirm.com" style="color:#475569;">info@lawfirm.com</a></p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon" style="background:#f1f5f9; color:#475569;"><i class="fa fa-clock"></i></div>
                    <div>
                        <strong style="color:#0f172a; display:block; font-size:14px;">Chamber Hours</strong>
                        <p style="color:#64748b; font-size:13px; margin:0;">Monday - Saturday: 09:00 AM to 06:00 PM</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form (7 Columns) -->
        <div class="col-md-7">
            <div class="contact-info-card">
                <h3 style="font-family:'Playfair Display', serif; font-size:22px; font-weight:700; color:#0f172a; margin-bottom:10px;">
                    Send Us an Inquiry
                </h3>
                <p style="color:#64748b; font-size:14px; margin-bottom:25px;">Please provide your contact details and case inquiry below.</p>

                <?php if (!empty($msg)): ?>
                    <div class="alert alert-success"><i class="fa fa-check-circle mr-1"></i> <?php echo $msg; ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><i class="fa fa-exclamation-circle mr-1"></i> <?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="hidden" name="send_contact_message" value="1">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label style="font-size:13px; font-weight:600; color:#334155;">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Zain Ansari" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:13px; font-weight:600; color:#334155;">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. zain@example.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-size:13px; font-weight:600; color:#334155;">Subject / Case Category</label>
                        <input type="text" name="subject" class="form-control" placeholder="e.g. Commercial Contract Review Inquiry" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label style="font-size:13px; font-weight:600; color:#334155;">Message / Inquiries <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Briefly describe your inquiry..." required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="padding: 12px 30px; font-weight:600;">
                        <i class="fa fa-paper-plane mr-1"></i> Submit Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
?>
