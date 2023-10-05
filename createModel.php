<?
    include_once("link.php");

    $model=$_POST["model"];
    $brand=$_POST["brand"];

    if(!empty($model) && !empty($brand))
    {
        $query_model="SELECT model FROM carmodels WHERE idBrand=$brand";
        $result_model=mysqli_query($link, $query_model);
        $i=0;
        while($row_model=mysqli_fetch_row($result_model))
        {
            $models[$i]=$row_model[0];
            $i++;
        }
        if(!in_array($model, $models))
        {
            $query_insertModel="INSERT INTO carmodels(model, idBrand) VALUE ('$model', $brand)";
            $result_insertModel=mysqli_query($link, $query_insertModel);
            $query_model="SELECT id FROM carmodels WHERE idBrand=$brand ORDER BY id DESC";
            $result_model=mysqli_query($link, $query_model);
            $lastId=mysqli_fetch_assoc($result_model);
            echo "true,".$lastId["id"];
        }    
    }
?>