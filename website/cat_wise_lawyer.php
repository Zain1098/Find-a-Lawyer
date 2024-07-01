<?php
include ('header.php');
include ('connection.php');

// Ensure cat_id is present and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $cat_id = intval($_GET['id']);
} else {
    // Redirect or show error if cat_id is not valid
    echo "Invalid category ID.";
    exit;
}

// Fetch lawyers based on the category ID
$q = "SELECT lawyer.*, categorie.cat_name as cat_name FROM lawyer 
      JOIN categorie ON categorie.cat_id = lawyer.specialist 
      WHERE categorie.cat_id = $cat_id";

$res = mysqli_query($con, $q);
?>
<style>
    /* banner */
.banner {
  margin-top: 1%;
  height: 400px;
  background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("find_back.jpg");
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  /* background-attachment: fixed; */
  margin-bottom: 0;
}

.banner .content {
  color: #fff;
  text-align: center;
  padding-top: 140px;
}

.banner .content h2 {
  color: #fff;
  font-family: "Crimson Text", serif;
  font-size: 45px;
  font-weight: 550;
  letter-spacing: 1px;
}

.banner .content p {
  font-size: 18px;
  font-style: italic;
  letter-spacing: 1px;
}

/* banner */

.input-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
  position: relative;
}

.input {
  border-style: none;
  height: 50px;
  width: 50px;
  padding: 10px;
  outline: none;
  border-radius: 50%;
  transition: .5s ease-in-out;
  background-color: #7e4fd4;
  box-shadow: 0px 0px 3px #f3f3f3;
  padding-right: 40px;
  color: #000;
}

.input::placeholder,
.input {
  font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
  font-size: 17px;
}

.input::placeholder {
  color: #8f8f8f;
}

.icon {
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  right: 0px;
  cursor: pointer;
  width: 50px;
  height: 50px;
  outline: none;
  border-style: none;
  border-radius: 50%;
  pointer-events: painted;
  background-color: transparent;
  transition: .2s linear;
}

.icon:focus~.input,
.input:focus {
  box-shadow: none;
  width: 250px;
  border-radius: 0px;
  background-color: transparent;
  border-bottom: 3px solid #7e4fd4;
  transition: all 500ms cubic-bezier(0, 0.110, 0.35, 2);
}


/* Container for the profile cards */
.profile-cards-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    max-width: 800px;
    margin: auto;
}

/* Link style for the profile card */
.profile-card-link {
    text-decoration: none;
    color: inherit;
    width: 100%;
}

/* Main profile card styling */
.profile-card {
    margin-left: 20px;
    margin-bottom: 20px;
    padding: 20px;
    border: 1px solid #000;
    max-width: 600px;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.5s, box-shadow 0.3s;
    overflow-wrap: break-word;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* Profile image wrapper */
.profile-img {
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    width: 100%;
    height: 100%;
    /* object-fit: cover; */
    transition: transform 0.3s, opacity 0.3s;
}

/* .profile-card:hover .profile-img {
    transform: scale(1.1);
    opacity: 0.9;
} */

/* Profile details styling */
.profile-details {
    padding: 20px;
}

.profile-name {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

.profile-specialty {
    font-size: 16px;
    color: #666;
    margin-bottom: 10px;
}

.profile-about {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.profile-degree {
    font-size: 14px;
    color: #444;
    margin-bottom: 15px;
}

/* View profile button styling */
.btn-view-profile {
    display: inline-block;
    background-color: #8f8f8f;
    border: none;
    color: #fff;
    padding: 10px 20px;
    border-radius: 20px;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.3s;
}

.btn-view-profile:hover {
    background-color: #333;
    transform: translateY(-3px);
}

</style>
<link rel="stylesheet" href="lawyer.css">
<div class="banner">
    <div class="content">
        <h2>Lawyers in Category</h2>
        <p><a href="index.php" style="text-decoration: none; color: #fff;">Home</a> -> Lawyers by Category</p>
    </div>
</div>
<br><br>
<?php while ($data = mysqli_fetch_assoc($res)) { ?>
    <a href="profile.php?id=<?php echo htmlspecialchars($data['id']); ?>" class="profile-card-link">
        <div class="profile-card">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?php echo htmlspecialchars($data['image']); ?>" alt="Profile Image" class="profile-img img-fluid rounded-start">
                </div>
                <div class="col-md-8">
                    <div class="profile-details card-body">
                        <h5 class="profile-name card-title"><?php echo htmlspecialchars($data['name']); ?></h5>
                        <p class="profile-specialty card-text"><small class="text-muted"><?php echo htmlspecialchars($data['cat_name']); ?></small></p>
                        <p class="profile-about card-text"><?php echo htmlspecialchars($data['about me']); ?></p>
                        <p class="profile-degree card-text"><b>Degree:</b> <?php echo htmlspecialchars($data['degree']); ?></p>
                        <a href="profile.php?id=<?php echo htmlspecialchars($data['id']); ?>" class="btn-view-profile">View Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </a>
<?php } ?>

<?php
include ('footer.php');
?>