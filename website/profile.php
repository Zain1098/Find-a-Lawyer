<?php
include "headerss.php";

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Fetch details for a specific lawyer based on 'id'
    $id = $_GET['id'];
    $sql = "SELECT *
    FROM lawyer
    JOIN categorie ON categorie.cat_id = lawyer.specialist
    WHERE lawyer.id = '$id';";
    $res = mysqli_query($con, $sql);
}
if (mysqli_num_rows($res) > 0) {
    $data = mysqli_fetch_assoc($res);
}
?>


<!-- Favicon -->
<link href="img/favicon.ico" rel="icon">

<!-- Google Web Fonts -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

<!-- Libraries Stylesheet -->
<link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

<!-- Customized Bootstrap Stylesheet -->
<link href="profile.css" rel="stylesheet">










<!-- Header Start -->
<div class="container-fluid bg-primary d-flex align-items-center mb-5 py-5" id="home" style="min-height: 100vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 px-5 pl-lg-0 pb-5 pb-lg-0">
            <img src="../website/Login&Singup/Lawyer Singup/uploads/<?php echo $data['image']; ?>" alt="Image" class="img-fluid w-100 rounded-circle shadow-sm">
            </div>
            <div class="col-lg-7 text-center text-lg-left">
                <h3 class="text-white font-weight-normal mb-3">I'm</h3>
                <h1 class="display-3 text-uppercase text-primary mb-2" style="-webkit-text-stroke: 2px #ffffff;"><?php echo $data['name'] ?> <?php echo $data['last name'] ?></h1>
                <h1 class="typed-text-output d-inline font-weight-lighter text-white"></h1>
                <div class="typed-text d-none"><?php echo $data['cat_name'] ?></div>
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start pt-5">
                    <a href="./appo/appoint.php?id=<?php echo $id ?>" class="btn btn-outline-light mr-5">Appointment</a>


                </div>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->


<!-- About Start -->
<div class="container-fluid py-5" id="about">
    <div class="container">
        <div class="position-relative d-flex align-items-center justify-content-center">
            <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">About</h1>
            <h1 class="position-absolute text-uppercase text-primary">About Me</h1>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-5 pb-4 pb-lg-0">
            <img src="../website/Login&Singup/Lawyer Singup/uploads/<?php echo $data['image']; ?>" alt="Image" class="img-fluid rounded w-100">
            </div>
            <div class="col-lg-7">
                <h3 class="mb-4"><?php echo $data['cat_name'] ?></h3>
                <p><?php echo $data['about me'] ?></p>
                <div class="row mb-3">
                    <div class="col-sm-6 py-2">
                        <h6>Name: <span class="text-secondary"><?php echo $data['name'] ?> <?php echo $data['last name'] ?></span></h6>
                    </div>
                    <div class="col-sm-6 py-2">
                        <h6>Birthday: <span class="text-secondary">1 April 1990</span></h6>
                    </div>
                    <div class="col-sm-6 py-2">
                        <h6>Degree: <span class="text-secondary"> <?php echo $data['university'] ?></span></h6>
                    </div>
                    <div class="col-sm-6 py-2">
                        <h6>Experience: <span class="text-secondary"><?php echo $data['since'] ?> Years</span></h6>
                    </div>
                    <div class="col-sm-6 py-2">
                        <h6>Phone: <span class="text-secondary"><?php echo $data['number'] ?></span></h6>
                    </div>
                    <div class="col-sm-6 py-2">
                        <h6>Email: <span class="text-secondary"><?php echo $data['email'] ?></span></h6>
                    </div>
                    <div class="col-sm-6 py-2">
                        <h6>Address: <span class="text-secondary"><?php echo $data['address'] ?></span></h6>
                    </div>
                    <div class="col-sm-6 py-2">
                        <h6>Freelance: <span class="text-secondary">Available</span></h6>
                    </div>
                </div>

                <a href="" class="btn btn-outline-primary">Hire Me</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->









<!-- Portfolio Start -->
<!-- <div class="container-fluid pt-5 pb-3" id="portfolio">
        <div class="container">
            <div class="position-relative d-flex align-items-center justify-content-center">
                <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Gallery</h1>
                <h1 class="position-absolute text-uppercase text-primary">My Portfolio</h1>
            </div>
            <div class="row">
                <div class="col-12 text-center mb-2">
                    <ul class="list-inline mb-4" id="portfolio-flters">
                        <li class="btn btn-sm btn-outline-primary m-1 active"  data-filter="*">All</li>
                        <li class="btn btn-sm btn-outline-primary m-1" data-filter=".first">Design</li>
                        <li class="btn btn-sm btn-outline-primary m-1" data-filter=".second">Development</li>
                        <li class="btn btn-sm btn-outline-primary m-1" data-filter=".third">Marketing</li>
                    </ul>
                </div>
            </div>
            <div class="row portfolio-container">
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item first">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/portfolio-1.jpg" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="img/portfolio-1.jpg" data-lightbox="portfolio">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item second">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/portfolio-2.jpg" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="img/portfolio-2.jpg" data-lightbox="portfolio">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item third">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/portfolio-3.jpg" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="img/portfolio-3.jpg" data-lightbox="portfolio">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item first">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/portfolio-4.jpg" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="img/portfolio-4.jpg" data-lightbox="portfolio">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item second">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/portfolio-5.jpg" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="img/portfolio-5.jpg" data-lightbox="portfolio">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 portfolio-item third">
                    <div class="position-relative overflow-hidden mb-2">
                        <img class="img-fluid rounded w-100" src="img/portfolio-6.jpg" alt="">
                        <div class="portfolio-btn bg-primary d-flex align-items-center justify-content-center">
                            <a href="img/portfolio-6.jpg" data-lightbox="portfolio">
                                <i class="fa fa-plus text-white" style="font-size: 60px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
<!-- Portfolio End -->





<!-- Blog Start -->
<div class="container-fluid pt-5" id="blog">
    <div class="container">
        <div class="position-relative d-flex align-items-center justify-content-center">
            <h1 class="display-1 text-uppercase text-white" style="-webkit-text-stroke: 1px #dee2e6;">Blog</h1>
            <h1 class="position-absolute text-uppercase text-primary">Latest Blog</h1>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-5">
                <div class="position-relative mb-4">
                    <img class="img-fluid rounded w-100" src="img/blog-1.jpg" alt="">
                    <div class="blog-date">
                        <h4 class="font-weight-bold mb-n1">01</h4>
                        <small class="text-white text-uppercase">Jan</small>
                    </div>
                </div>
                <h5 class="font-weight-medium mb-4">Rebum lorem no eos ut ipsum diam tempor sed rebum elitr ipsum</h5>
                <a class="btn btn-sm btn-outline-primary py-2" href="">Read More</a>
            </div>
            <div class="col-lg-4 mb-5">
                <div class="position-relative mb-4">
                    <img class="img-fluid rounded w-100" src="img/blog-2.jpg" alt="">
                    <div class="blog-date">
                        <h4 class="font-weight-bold mb-n1">01</h4>
                        <small class="text-white text-uppercase">Jan</small>
                    </div>
                </div>
                <h5 class="font-weight-medium mb-4">Rebum lorem no eos ut ipsum diam tempor sed rebum elitr ipsum</h5>
                <a class="btn btn-sm btn-outline-primary py-2" href="">Read More</a>
            </div>
            <div class="col-lg-4 mb-5">
                <div class="position-relative mb-4">
                    <img class="img-fluid rounded w-100" src="img/blog-3.jpg" alt="">
                    <div class="blog-date">
                        <h4 class="font-weight-bold mb-n1">01</h4>
                        <small class="text-white text-uppercase">Jan</small>
                    </div>
                </div>
                <h5 class="font-weight-medium mb-4">Rebum lorem no eos ut ipsum diam tempor sed rebum elitr ipsum</h5>
                <a class="btn btn-sm btn-outline-primary py-2" href="">Read More</a>
            </div>
        </div>
    </div>
</div>
<!-- Blog End -->







<!-- Scroll to Bottom -->
<i class="fa fa-2x fa-angle-down text-white scroll-to-bottom"></i>

<!-- Back to Top -->
<a href="#" class="btn btn-outline-dark px-0 back-to-top"><i class="fa fa-angle-double-up"></i></a>


<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/typed/typed.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/isotope/isotope.pkgd.min.js"></script>
<script src="lib/lightbox/js/lightbox.min.js"></script>





<!-- Template Javascript -->
<script src="main.js"></script>
<?php
include "footer.php";
?>