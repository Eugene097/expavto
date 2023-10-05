<?php
	ob_start();

	ini_set('session.gc_maxlifetime', 40);
	ini_set('session.cookie_lifetime', 0);

	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Регистрация</title>

		<link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">

		<link rel="stylesheet" href="registration.css">

		<!-- Bootstrap CSS (jsDelivr CDN) -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

  		<!-- Bootstrap Bundle JS (jsDelivr CDN) -->
  		<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

  		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
	</head>
	<body>
		<form method="post">
			<a class="back" onclick="javascript:history.back(); return false;">Назад</a>
			<div class="" id="mistake"></div>
			<div class="mb-3">
    			<label for="surname" class="form-label">Фамилия</label>
    			<input type="text" class="form-control" pattern="[А-Яа-я]+" id="surname" name="surname" value="<? echo $_POST["surname"]; ?>" required>
		  	</div>
			  <div class="mb-3">
    			<label for="name" class="form-label">Имя</label>
    			<input type="text" class="form-control" pattern="[А-Яа-я]+" id="name" name="name" value="<? echo $_POST["name"]; ?>"  required>
		  	</div>
			<div class="mb-3">
    			<label for="patronymic" class="form-label">Отчество</label>
    			<input type="text" class="form-control" pattern="[А-Яа-я]+" id="patronymic" name="patronymic" value="<? echo $_POST["patronymic"]; ?>"  required>
		  	</div>
			<div class="mb-3">
    			<label for="calendar" class="form-label">Дата рождения</label>
    			<input type="date" name="calendar" value="<? echo $_POST["calendar"]; ?>"  required>
		  	</div>
  			<div class="mb-3">
    			<label for="phone" class="form-label">Номер телефона</label>
    			<input type="tel" class="form-control jmp__input_tel" id="phone" value="<? echo $_POST["phone"]; ?>"  name="phone" required>
		  	</div>
		  	<div class="mb-3">
		   		<label for="password" class="form-label">Пароль</label>
		    	<input type="password" class="form-control" pattern="[A-Za-z0-9]+" maxlength="32" minlength="6" name="password" id="password" required>
		  	</div>
			  <div class="mb-3">
		   		<label for="passwordRepeat" class="form-label">Подтверждение пароля</label>
		    	<input type="password" class="form-control" pattern="[A-Za-z0-9]+" maxlength="32" minlength="6" name="passwordRepeat" id="passwordRepeat" required>
		  	</div>
		  	<div class="agreement">
				<input type="checkbox" name="agreement" required><label>Принимаю <a href="lic.html" target="_blank"> согласие на обработку персональных данных</a></label>
			</div>
		  	<button type="submit" name="register" class="btn btn-primary">Зарегистрироваться</button>
		</form>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<!-- jQuery Mask Plugin -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
		<script>
			$(function() 
			{
				$('.jmp__input_tel').mask('+7(000)000-00-00');
			});
		</script>  
			<!-- /jQuery Mask Plugin -->

		<?php
			include_once("link.php");

			if(isset($_POST["register"]))
			{
				$surname=$_POST["surname"];
				$name=$_POST["name"];
				$patronymic=$_POST["patronymic"];
				$date=$_POST["calendar"];
				if($date>date("Y-m-d"))
				{
					echo "<script>
							mistake.innerHTML='Неверная дата рождения'; 
							document.getElementById('mistake').classList.add('mistake');
						</script>";
				}
				else
				{
					for($i=0;$i<=strlen($date); $i++)
					{
						if($i<4) $year=$year.$date[$i];
						if($i>4 && $i<7) $month=$month.$date[$i];
						if($i>=8 && $i<11) $day=$day.$date[$i];
					}
					$dateCorrect=$day.".".$month.".".$year;
					$phone=$_POST["phone"];
					$password=$_POST["password"];
					$passwordRepeat=$_POST["passwordRepeat"];
					if($password==$passwordRepeat)
					{
						$hash=password_hash($password, PASSWORD_BCRYPT);
							
						$query="SELECT phone FROM users WHERE phone='$phone'";
						$result=mysqli_query($link, $query);
						$row=mysqli_fetch_row($result);
						if(!mysqli_num_rows($result))
						{
							$query="INSERT INTO users(surname, name, patronymic, phone, password, dateOfBirthday, idAccessRight) VALUES ('$surname', '$name', '$patronymic', '$phone', '$hash', '$dateCorrect', 1)";
							if(mysqli_query($link, $query))
							{
								$bytes=bin2hex(openssl_random_pseudo_bytes(20));
								$query="UPDATE users SET token='$bytes' WHERE phone='$phone'";
								mysqli_query($link, $query);
								$_SESSION["Token"]=$bytes;
								$_SESSION['Start'] = time();
								$_SESSION['Expire'] = $_SESSION['Start'] + (20 * 60);
								header("Location: index.php");
							}
							else echo "<script>
											mistake.innerHTML='Произошла ошибка'; 
											document.getElementById('mistake').classList.add('mistake');
										</script>";
						}
						else echo "<script>
										mistake.innerHTML='Такой номер телефона уже зарегистрирован';
										document.getElementById('mistake').classList.add('mistake');
									</script>";
					}
					else echo "<script>
									mistake.innerHTML='Пароли не совпадают';
									document.getElementById('mistake').classList.add('mistake');
								</script>";
				}
			}

			ob_end_flush();
		?>
	</body>
</html>