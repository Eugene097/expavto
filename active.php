<?php
    session_start();    //Начало сессии
    include_once("link.php");   //Подключение БД
    $token=$_SESSION["Token"];
    $now = time();  //Текущее время
    if ($now > $_SESSION['Expire']) //Сравнение текущего времени и последней активности
    {
        $query="UPDATE users SET Token=NULL WHERE Token='$token'"; //Обнуление токена в БД
        mysqli_query($link, $query);
        $_SESSION["Token"]=""; //Обнуление токена
    }
    else $_SESSION['Expire'] = $now + (20 * 60);  //Время поледней активности
?>