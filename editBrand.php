<?
    include_once("link.php");
    $array_brands=array();

    $query="SELECT brand FROM carbrands";
    $result=mysqli_query($link, $query);
    $i=0;
    while($row=mysqli_fetch_assoc($result))
    {
        $array_brands[$i]=$row["brand"];
        $i++;
    }
    if(!in_array($_POST["brand"], $array_brands))
    {
        $query_edit="UPDATE carbrands SET brand='$_POST[brand]' WHERE id='$_POST[idBrand]'";
        mysqli_query($link, $query_edit);
        echo "true";
    }
?>