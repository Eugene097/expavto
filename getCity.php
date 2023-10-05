<?php
    include_once("link.php");

    $region=$_POST["region"];
    $query="SELECT id FROM regions WHERE name_region='$region'";
    $result=mysqli_query($link, $query);
    $idRegion=mysqli_fetch_assoc($result);
    $query_city="SELECT name_city FROM cities WHERE idRegion=$idRegion[id]";
    $result_city=mysqli_query($link, $query_city);
    while($city=mysqli_fetch_assoc($result_city))
    {
        echo $city["name_city"].",";
    }
?>