<?
    include_once("link.php");
    $query_delete = "DELETE FROM users WHERE id=$_POST[idUser]";
    $result_delete = mysqli_query($link, $query_delete);
?>