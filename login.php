<?php
	ob_start();

	ini_set('session.gc_maxlifetime', 40);
	ini_set('session.cookie_lifetime', 0);

	session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Авторизация</title>

		<link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">

		<link rel="stylesheet" href="login.css">

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
    			<label for="phone" class="form-label">Номер телефона</label>
    			<input type="tel" class="form-control jmp__input_tel" value="<? echo $_POST["phone"]; ?>"  id="phone" name="phone" required>
		  	</div>
		  	<div class="mb-3">
		   		<label for="password" class="form-label">Пароль</label>
		    	<input type="password" class="form-control" name="password" id="password" required>
		  	</div>
		  	<button type="submit" name="login" class="btn btn-primary">Войти</button>
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

            if(isset($_POST["login"]))
			{
				$phone=$_POST["phone"];
				$password=$_POST["password"];
		
				$query="SELECT phone, password FROM users WHERE phone=?";
				if ($stmt=mysqli_prepare($link, $query)) 
				{
					mysqli_stmt_bind_param($stmt, 's', $phone);
					mysqli_stmt_execute($stmt);
					$result=mysqli_stmt_get_result($stmt);
					if(mysqli_num_rows($result))
					{
						while ($row=mysqli_fetch_assoc($result))
						{
							if($row["phone"]==$phone)
							{
								if(password_verify($password, $row["password"]))
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
												mistake.innerHTML='Неверный логин или пароль';
												document.getElementById('mistake').classList.add('mistake');
											</script>";
							}
							else echo "<script>
											mistake.innerHTML='Неверный логин или пароль';
											document.getElementById('mistake').classList.add('mistake');
										</script>";
						}
					}
					else echo "<script>
									mistake.innerHTML='Неверный логин или пароль';
									document.getElementById('mistake').classList.add('mistake');
								</script>";
				}
			}
			ob_end_flush();
        ?>
    </body>
</html>