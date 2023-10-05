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

		<!-- Подключаем CSS слайдера -->
	<link rel="stylesheet" href="https://itchief.ru/examples/libs/simple-adaptive-slider/simple-adaptive-slider.min.css">
	<!-- Подключаем JS слайдера -->
	<script defer src="https://itchief.ru/examples/libs/simple-adaptive-slider/simple-adaptive-slider.min.js"></script>
	</head>
	<body>
			<?php 
				include_once("menu.php");

                $query="SELECT * FROM announcements WHERE id=$id";
				$result=mysqli_query($link, $query);
                $ad=mysqli_fetch_assoc($result);

				$query_model="SELECT * FROM carmodels WHERE id=$ad[idModel]";
				$result_model=mysqli_query($link, $query_model);
				$model=mysqli_fetch_assoc($result_model);

				$query_brand="SELECT * FROM carbrands WHERE id=$ad[idBrand]";
				$result_brand=mysqli_query($link, $query_brand);
				$brand=mysqli_fetch_assoc($result_brand);

				$query_image = "SELECT image FROM image WHERE idAnnouncements=$ad[id]";
				$result_image = mysqli_query($link, $query_image);
			?>
		<main>
			<div class="main">
				
				<h2>Подтверждение удаления</h2><br>
				<script>
			document.addEventListener('DOMContentLoaded', function() {
				// инициализация слайдера
				var slider = new SimpleAdaptiveSlider('.slider', {
					loop: false,
					autoplay: false,
					interval: 5000,
					swipe: true,
				});
			});
		</script>
		<div class="car-image">
			<div class="slider">
				<div class="slider__wrapper">
					<div class="slider__items">
						<?
						while ($image = mysqli_fetch_assoc($result_image)) { ?>
							<div class="slider__item">
								<?
								echo '<img class="img-fluid" src="data:image/jpeg;base64,' 
								. base64_encode($image["image"]) . '" loading="lazy">';  //Вывод изображения
								?>
							</div>
						<? } ?>
					</div>
				</div>
				<a class="slider__control slider__control_prev" href="#" role="button" data-slide="prev"></a> 
				<a class="slider__control slider__control_next" href="#" role="button" data-slide="next"></a>
			</div>
		</div>
				<p style="margin-top: 20px;">Марка: <? echo $brand["brand"]; ?></p><br>
				<p>Модель: <? echo $model["model"]; ?></p><br>
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
		header("Location: myAds.php");
	}

	ob_end_flush(); 
?>