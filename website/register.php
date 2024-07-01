<?php
include('../Admin/connection.php');

if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    $sql="insert into user(user_name,user_email,user_password) values('$name','$email','$pass')";
    $res=mysqli_query($con,$sql);
    if($res){
        header("location:log.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="https://common.olemiss.edu/_js/sweet-alert/sweet-alert.css">
    <link rel="stylesheet" href="./css/form.css">
</head>
<body>
    <form method="POST">
        <div class="form">
            <div class="title">Welcome</div>
            <div class="subtitle">Let's create your account!</div>
            <div class="input-container ic1">
                <input id="firstname" name="name" class="input" type="text" placeholder=" " />
                <div class="cut"></div>
                <label for="firstname" class="placeholder">First Name</label>
            </div>
            <div class="input-container ic2">
                <input id="lastname" name="email" class="input" type="email" placeholder=" " />
                <div class="cut"></div>
                <label for="lastname" class="placeholder">Email</label>
            </div>
            <div class="input-container ic2">
                <input id="email" name="pass" class="input" type="password" placeholder=" " />
                <div class="cut cut-short"></div>
                <label for="email" class="placeholder">Password</>
            </div>
            <button type="submit" class="submit" name="submit">Sign Up</button>
        </div>
    </form>
    <script src="https://common.olemiss.edu/_js/sweet-alert/sweet-alert.min.js"></script>
</body>
</html>
