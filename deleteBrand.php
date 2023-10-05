<?
    include_once("link.php");

    $query_delete = "DELETE FROM carbrands WHERE id=$_POST[idBrand]";
    $result_delete = mysqli_query($link, $query_delete);
?>