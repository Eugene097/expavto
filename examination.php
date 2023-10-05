<?php
    if(empty($_SESSION["Token"]))
    {
        header("Location:index.php");
    }
?>