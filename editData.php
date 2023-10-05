<?php
	session_start();
    ob_start();
	include_once("active.php");
	include_once("link.php");
	include_once("examination.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Редактирование данных</title>
		<link rel="stylesheet" href="personArea.css">

		<!-- Bootstrap CSS (jsDelivr CDN) -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

  		<!-- Bootstrap Bundle JS (jsDelivr CDN) -->
  		<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

  		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
	
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">
	</head>
	<body>
		<? include_once("menu.php"); ?>
		<main>
			<form class="data" method="post">
				<h2>Редактирование данных  <a href="personArea.php">Личный кабинет</a></h2>
				<div class="title">
					<?
						$token=$_SESSION["Token"];
						$query="SELECT * FROM users WHERE Token='$token'";
						$result=mysqli_query($link, $query);
						$row=mysqli_fetch_assoc($result);
					?>
					<div class="surname">
						<span>Фамилия:</span>
						<input type="text" name="surname" value="<? echo $row['surname']; ?>">
					</div>
					<div class="name">
						<span>Имя:</span>
						<input type="text" value="<? echo $row['name']; ?>" name="name">
					</div>
					<div class="patronymic">
						<span>Отчество:</span>
						<input type="text" value="<? echo $row['patronymic']; ?>" name="patronymic">
					</div>
					<div class="phone">
						<span>Номер телефона:</span>
						<span><? echo $row['phone']; ?></span>
					</div>
					<div class="dateOfBirthday">
						<span>Дата рождения:</span>
                        <? 
                            $date=$row['dateOfBirthday'];
                            for($i=0;$i<=strlen($date); $i++)
                            {
                                if($i<2) $day=$day.$date[$i];
                                if($i>2 && $i<5) $month=$month.$date[$i];
                                if($i>=6 && $i<10) $year=$year.$date[$i];
                            } 
                        ?>
                        <input type="date" value="<? echo $year."-".$month."-".$day; ?>" name="dateOfBirthday"><br>
					</div>
				</div>
                <input type="submit" value="Сохранить" name="save">
			</form>
		</main>
    </body>
</html>

<?
    if(isset($_POST["save"]))
    {
        $date=$_POST["dateOfBirthday"];
        $day="";
        $month="";
        $year="";
        for($i=0;$i<=strlen($date); $i++)
		{
			if($i<4) $year=$year.$date[$i];
			if($i>4 && $i<7) $month=$month.$date[$i];
			if($i>=8 && $i<11) $day=$day.$date[$i];
		}
		$editDate=$day.".".$month.".".$year;
        $query="UPDATE users SET surname='$_POST[surname]', name='$_POST[name]', patronymic='$_POST[patronymic]', dateOfBirthday='$editDate' 
			WHERE token='$_SESSION[Token]'";
        mysqli_query($link, $query);
        header("Location: personArea.php");
    }
    
    ob_end_flush();
?>