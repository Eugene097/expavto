<?
    include_once("link.php");
    $query_AccessRight="SELECT id FROM accessright WHERE title='$_POST[accessRight]'";
    $result_AccessRight=mysqli_query($link, $query_AccessRight);
    $idAccessRight=mysqli_fetch_assoc($result_AccessRight);

    $query_changeAccessRight="UPDATE users SET idAccessRight='$idAccessRight[id]' WHERE id='$_POST[idUser]'";
    mysqli_query($link, $query_changeAccessRight);
?>