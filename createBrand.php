<?
    include_once("link.php");

    $brand=$_POST["brand"];

    if(!empty($brand))
    {
        $query_brand="SELECT brand FROM carbrands";
        $result_brand=mysqli_query($link, $query_brand);
        $i=0;
        while($row_brand=mysqli_fetch_row($result_brand))
        {
            $brands[$i]=$row_brand[0];
            $i++;
        }
        if(!in_array($brand, $brands))
        {
            echo "true";
            $query_insertBrand="INSERT INTO carbrands(brand) VALUE ('$brand')";
            $result_insertBrand=mysqli_query($link, $query_insertBrand);
        }
    }
?>