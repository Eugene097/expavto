<?
    include_once("link.php");

    $query_delete = "DELETE FROM carmodels WHERE id=$_POST[idModel]";
    $result_delete = mysqli_query($link, $query_delete);
?>