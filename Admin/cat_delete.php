<?php
include('connection.php');
$id=$_GET['id'];
$sql="delete from categorie where id=$id";
$result=mysqli_query($con,$sql);
if($result){
    echo "<script>alert('Categorie Deleted'); window.location.href='cat_show.php'</script>";
}
?>