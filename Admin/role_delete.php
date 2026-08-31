<?php
include('connection.php');
$id=$_GET['id'];
$sql="delete from role where role_id=$id";
$result=mysqli_query($con,$sql);
if($result){
    echo "<script>alert('Role Deleted'); window.location.href='role_show.php'</script>";
}
?>