<?
    include_once("link.php");
    $query_block = "UPDATE users SET idAccessRight=2 WHERE id=$_POST[idUser]";
    $result_block= mysqli_query($link, $query_block);

    $query_blockAd = "UPDATE announcements SET block=1, reason_for_blocking='Блокировка администрацией' WHERE idUser=$_POST[idUser]";
    $result_blockAd= mysqli_query($link, $query_blockAd);
?>