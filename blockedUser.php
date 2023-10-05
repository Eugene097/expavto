<?
    include_once("link.php");

    $token=$_SESSION["Token"];
    $query="SELECT idAccessRight FROM users WHERE token='$token'";
    $result=mysqli_query($link, $query);
    $idAccessRight=mysqli_fetch_assoc($result);
    if($idAccessRight["idAccessRight"]==2) header("Location: index.php");
?>