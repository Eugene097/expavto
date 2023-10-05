<?
    include_once("link.php");

    $query="SELECT idBrand FROM carmodels WHERE id=$_POST[idModel]";
    $result=mysqli_query($link, $query);
    $idBrand=mysqli_fetch_assoc($result);

    $query_model="SELECT model FROM carmodels WHERE idBrand=$idBrand[idBrand]";
    $result_model=mysqli_query($link, $query_model);
    $i=0;
    while($row=mysqli_fetch_array($result_model))
    {
        $models[$i]=$row["model"];
        $i++;
    }

    if((!empty($_POST["model"])) && (!empty($_POST["idModel"])))
    {
        if(!in_array($_POST["model"], $models))
        {
            $query_edit="UPDATE carmodels SET model='$_POST[model]' WHERE id='$_POST[idModel]'";
            mysqli_query($link, $query_edit);
            echo "true";
        }
    }
    
?>