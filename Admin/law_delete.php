<?php
include('connection.php');
$id=$_GET['id'];
$sql="delete from lawyer where id=$id";
$result=mysqli_query($con,$sql);
if($result){
    echo "<script>alert('Lawyer Deleted'); window.location.href='law_show.php'</script>";
}
?>