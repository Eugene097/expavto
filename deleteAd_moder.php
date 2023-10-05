<?
	ob_start();
    include_once("link.php");
	include_once("active.php");
	include_once("examination.php");
    $id=$_GET["ad"];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Подтверждение удаления</title>
		<link rel="stylesheet" href="deleteAd.css">
		
		<!-- Bootstrap CSS (jsDelivr CDN) -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

  		<!-- Bootstrap Bundle JS (jsDelivr CDN) -->
  		<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
	
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">

		<link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
	</head>
	<body>
			<?php 
				include_once("menu.php");

                $query="SELECT * FROM announcements WHERE id=$id";
				$result=mysqli_query($link, $query);
                $ad=mysqli_fetch_assoc($result);
				$query_generation="SELECT idModel, generation FROM cargenerations WHERE id=$ad[idGeneration]";
				$result_generation=mysqli_query($link, $query_generation);
				$generation=mysqli_fetch_assoc($result_generation);

				$query_model="SELECT * FROM carmodels WHERE id=$generation[idModel]";
				$result_model=mysqli_query($link, $query_model);
				$model=mysqli_fetch_assoc($result_model);

				$query_brand="SELECT * FROM carbrands WHERE id=$model[idBrand]";
				$result_brand=mysqli_query($link, $query_brand);
				$brand=mysqli_fetch_assoc($result_brand);

                $query_owner="SELECT surname, name, patronymic, phone FROM users WHERE id=$ad[idUser]";
				$result_owner=mysqli_query($link, $query_owner);
				$owner=mysqli_fetch_assoc($result_owner);
			?>
		<main>
			<div class="main">
				
				<h2>Подтверждение удаления</h2><br>
				<? echo '<img src="data:image/jpeg;base64,'.base64_encode( $ad['image'] ).'" class="car_img">';?> 
                <p>Владелец: <? echo $owner["surname"]." ".$owner["name"]." ".$owner["patronymic"]." ( ".$owner["phone"]." )"; ?></p><br>
				<p>Марка: <? echo $brand["brand"]; ?></p><br>
				<p>Модель: <? echo $model["model"]; ?></p><br>
				<p>Поколение: <? echo $generation["generation"]; ?></p><br>
				<p>Год: <? echo $ad["yearOfIssue"]; ?></p><br>
				<form method="post"><input class="btn btn-secondary" type="submit" name="deleteAd" value="Удалить"></form>
			</div>
            
        </main>
	</body>
</html>

<? 
	if(isset($_POST["deleteAd"]))
	{
		$query_delete="DELETE FROM announcements WHERE id=$id";
		mysqli_query($link, $query_delete);
		header("Location: index.php");
	}

	ob_end_flush(); 
?>