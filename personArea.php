<?php
session_start();
include_once("active.php");
include_once("link.php");
include_once("examination.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Личный кабинет</title>
	<link rel="stylesheet" href="personArea.css">

	<!-- Bootstrap CSS (jsDelivr CDN) -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

	<!-- Bootstrap Bundle JS (jsDelivr CDN) -->
	<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">

	<link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
</head>

<body>
	<? include_once("menu.php"); ?>
	<main>
		<div id="snackbar"></div>
		<form class="data" id="data" method="post">
			<h2>Личный кабинет <a href="editData.php">Редактировать данные</a></h2>
			<div class="title">
				<?
				$token = $_SESSION["Token"];
				$query = "SELECT * FROM users WHERE Token='$token'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_assoc($result);
				if ($row["idAccessRight"] == 2) {
				?>
					<div>
						<h2>Ваш аккаунт заблокирован</h2>
					</div>
				<?
				}
				?>
			</div>
			<div class="surname">
				<span>Фамилия:</span>
				<span><? echo $row['surname']; ?></span>
			</div>
			<div class="name">
				<span>Имя:</span>
				<span><? echo $row['name']; ?></span>
			</div>
			<div class="patronymic">
				<span>Отчество:</span>
				<span><? echo $row['patronymic']; ?></span>
			</div>
			<div class="phone">
				<span>Номер телефона:</span>
				<span><? echo $row['phone']; ?></span>
			</div>
			<div class="dateOfBirthday">
				<span>Дата рождения:</span>
				<span><? echo $row['dateOfBirthday']; ?></span>
			</div>
			</div>
			<h3 style="margin-top: 25px;">Смена пароля</h3>
			<div id="log"></div>
			<div class="changePassword" id="changePassword">
				<div class="oldPassword">
					<label for="oldPassword">Старый пароль</label>
					<input type="password" name="oldPassword" id="oldPassword" pattern="[A-Za-z0-9]+" maxlength="32" minlength="6">
				</div><br>
				<div class="newPassword">
					<label for="newPassword">Новый пароль</label>
					<input type="password" name="newPassword" id="newPassword" pattern="[A-Za-z0-9]+" maxlength="32" minlength="6">
				</div><br>
				<div class="repeatPassword">
					<label for="repeatPassword">Повтор нового пароля</label>
					<input type="password" name="repeatPassword" id="repeatPassword" pattern="[A-Za-z0-9]+" maxlength="32" minlength="6">
				</div><br>
				<input type="submit" class="btn btn-secondary savePassword" name="savePassword" value="Изменить">
			</div>
		</form>
	</main>
</body>

</html>

<?
$query = "SELECT `password` FROM users WHERE token='$token'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_assoc($result);
if (isset($_POST["savePassword"])) {
	if (!empty($_POST["oldPassword"])) {
		$oldPassword = $_POST["oldPassword"];
		if (!empty($_POST["newPassword"])) {
			$newPassword = $_POST["newPassword"];
			if (!empty($_POST["repeatPassword"])) {
				$repeatPassword = $_POST["repeatPassword"];
				if (password_verify($oldPassword, $row["password"])) {
					if ($newPassword == $repeatPassword) {
						$hash = password_hash($newPassword, PASSWORD_BCRYPT);
						$query = "UPDATE users SET Password='$hash' WHERE Token='$token'";
						if (mysqli_query($link, $query)) {
							echo "<script>
											log.innerHTML='Данные изменены';
											log.classList.add('success');
										</script>";
							$checkPassword = true;
						} else echo "<script>
											log.innerHTML='Изменения прошли неудачно';
											log.classList.add('fail');
										</script>";
					} else echo "<script>
											log.innerHTML='Пароли не совпадают';
											log.classList.add('fail');
										</script>";
				} else echo "<script>
										log.innerHTML='Текущий пароль неверен';
										log.classList.add('fail');
									</script>";
			} else echo "<script>
									log.innerHTML='Подтвердите новый пароль';
									log.classList.add('fail');
								</script>";
		} else echo "<script>
								log.innerHTML='Необходимо заполнить поле Новый пароль';
								log.classList.add('fail');
							</script>";
	} else echo "<script>
							log.innerHTML='Введите старый пароль';
							log.classList.add('fail');
						</script>";
}
?>