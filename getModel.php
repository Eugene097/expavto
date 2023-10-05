<?
    include_once("link.php");

    $brand=$_POST["brand"];
    $query="SELECT id FROM carBrands WHERE brand='$brand'";
    $result=mysqli_query($link, $query);
    $row=mysqli_fetch_assoc($result);
    $id=$row['id'];
    $query="SELECT COUNT(*) FROM carModels WHERE idBrand='$id'";
    $result=mysqli_query($link, $query);
    $row=mysqli_fetch_array($result);
    $count=$row[0];
    $i=0;
    $query="SELECT model FROM carModels WHERE idBrand='$id'";
    $result=mysqli_query($link, $query);
    while($row=mysqli_fetch_assoc($result))
    {
        echo $row['model'].",";
        $i++;
    }
?>