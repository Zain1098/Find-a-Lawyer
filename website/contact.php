<?php
include "headerss.php";
?>
<!DOCTYPE HTML>
<html>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Lawfirm Template</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="" />
   <meta name="keywords" content="" />
   <meta name="author" content="" />

   <!-- Facebook and Twitter integration -->
   <meta property="og:title" content=""/>
   <meta property="og:image" content=""/>
   <meta property="og:url" content=""/>
   <meta property="og:site_name" content=""/>
   <meta property="og:description" content=""/>
   <meta name="twitter:title" content="" />
   <meta name="twitter:image" content="" />
   <meta name="twitter:url" content="" />
   <meta name="twitter:card" content="" />

   <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet">

   <!-- Animate.css -->
   <link rel="stylesheet" href="css/animate.css">
   <!-- Icomoon Icon Fonts-->
   <link rel="stylesheet" href="css/icomoon.css">
   <!-- Bootstrap  -->
   <link rel="stylesheet" href="css/bootstrap.css">

   <!-- Magnific Popup -->
   <link rel="stylesheet" href="css/magnific-popup.css">

   <!-- Owl Carousel  -->
   <link rel="stylesheet" href="css/owl.carousel.min.css">
   <link rel="stylesheet" href="css/owl.theme.default.min.css">
   <!-- Flexslider  -->
   <link rel="stylesheet" href="css/flexslider.css">
   <!-- Flaticons  -->
   <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

   <!-- Theme style  -->
   <link rel="stylesheet" href="css/style.css">

   <!-- Modernizr JS -->
   <script src="js/modernizr-2.6.2.min.js"></script>
   <!-- FOR IE9 below -->
   <!--[if lt IE 9]>
   <script src="js/respond.min.js"></script>
   <![endif]-->
</head>
<body>
<aside id="colorlib-hero" class="js-fullheight">
   <div class="flexslider js-fullheight">
      <ul class="slides">
         <li style="background-image: url(images/img_bg_1.jpg);">
            <div class="overlay-gradient"></div>
            <div class="container">
               <div class="col-md-10 col-md-offset-1 text-center js-fullheight slider-text">
                  <div class="slider-text-inner desc">
                     <h2 class="heading-section">Contact Us</h2>
                     <p class="colorlib-lead">Designed with love by the fine folks at <a href="#" target="_blank">Lawfirm</a></p>
                  </div>
               </div>
            </div>
         </li>
      </ul>
   </div>
</aside>

<div id="colorlib-contact">
   <div class="container">
      <div class="row">
         <div class="col-md-5 col-md-push-1 animate-box">
            <div class="colorlib-contact-info">
               <h3>Contact Information</h3>
               <ul>
                  <li class="address">198 West 21th Street, <br> Suite 721 New York NY 10016</li>
                  <li class="phone"><a href="tel://1234567920">+ 1235 2355 98</a></li>
                  <li class="email"><a href="mailto:info@yoursite.com">info@yoursite.com</a></li>
                  <li class="url"><a href="#">lawfirm.com</a></li>
               </ul>
            </div>
         </div>
         <div class="col-md-6 animate-box">
            <h3>Send A Message</h3>
            <form id="contact-form" onsubmit="sendMail(event)">
               <div class="row form-group">
                  <div class="col-md-6">
                     <input type="text" id="fname" name="name" class="form-control" placeholder="Your firstname" required>
                  </div>
                  <div class="col-md-6">
                     <input type="text" id="lname" name="from_lastname" class="form-control" placeholder="Your lastname" required>
                  </div>
               </div>
               <div class="row form-group">
                  <div class="col-md-12">
                     <input type="email" id="email" name="email" class="form-control" placeholder="Your email address" required>
                  </div>
               </div>
               <div class="row form-group">
                  <div class="col-md-12">
                     <input type="text" id="subject" name="subject" class="form-control" placeholder="Your subject of this message" required>
                  </div>
               </div>
               <div class="row form-group">
                  <div class="col-md-12">
                     <textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Say something about us" required></textarea>
                  </div>
               </div>
               <div class="form-group">
                  <input type="submit" value="Send Message" class="btn btn-primary">
               </div>
            </form>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="https://cdn.emailjs.com/dist/email.min.js"></script>
<script type="text/javascript">
  (function () {
    emailjs.init("AvgkUbQFSsE27b003");
  })();
</script>

<script type="text/javascript">
  function sendMail(event) {
    event.preventDefault(); // Prevent form submission

    var name = document.getElementById("fname").value;
    var subject = document.getElementById("subject").value;
    var email = document.getElementById("email").value;
    var message = document.getElementById("message").value;

    console.log("Sending email with the following details:");
    console.log("Name:", name);
    console.log("Subject:", subject);
    console.log("Email:", email);
    console.log("Message:", message);

    emailjs.send("service_ih5ns2r", "template_dxxjw09", {
        name: name,
        subject: subject,
        email: email,
        message: message,
    })
    .then(
      function(response) {
        console.log("SUCCESS!", response.status, response.text);
        alert("Message sent successfully!");
      },
      function(error) {
        console.log("FAILED...", error);
        alert("Message sending failed. Please try again.");
      }
    );

    // Clear form fields
    document.getElementById("contact-form").reset();
  }
</script>

<?php
include "footer.php";
?>
