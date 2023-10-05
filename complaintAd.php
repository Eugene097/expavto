<?
	ob_start();
    include_once("link.php");
	include_once("active.php");
	include_once("examination.php");
	include_once("moderCheck.php");
    $id=$_GET["id"];
	$token=$_SESSION["Token"];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Жалобы</title>
		<link rel="stylesheet" href="cartCar.css">
		
		<!-- Bootstrap CSS (jsDelivr CDN) -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

  		<!-- Bootstrap Bundle JS (jsDelivr CDN) -->
  		<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
	
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">

		<!-- Подключаем CSS слайдера -->
		<link rel="stylesheet" href="https://itchief.ru/examples/libs/simple-adaptive-slider/simple-adaptive-slider.min.css">
		<!-- Подключаем JS слайдера -->
		<script defer src="https://itchief.ru/examples/libs/simple-adaptive-slider/simple-adaptive-slider.min.js"></script>

		<link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
	</head>
	<body>
			<?php 
				include_once("menu.php");

				$query_complaint="SELECT idAnnouncements FROM complaints WHERE id=$id";
				$result_complaint=mysqli_query($link, $query_complaint);
				$complaint=mysqli_fetch_assoc($result_complaint);

                $query="SELECT * FROM announcements WHERE id=$complaint[idAnnouncements]";
				$result=mysqli_query($link, $query);
                $ad=mysqli_fetch_assoc($result);

				$query_model="SELECT * FROM carmodels WHERE id=$ad[idModel]";
				$result_model=mysqli_query($link, $query_model);
				$model=mysqli_fetch_assoc($result_model);

				$query_brand="SELECT * FROM carbrands WHERE id=$model[idBrand]";
				$result_brand=mysqli_query($link, $query_brand);
				$brand=mysqli_fetch_assoc($result_brand);

				$query_owner="SELECT surname, name, patronymic, phone FROM users WHERE id=$ad[idUser]";
				$result_owner=mysqli_query($link, $query_owner);
				$owner=mysqli_fetch_assoc($result_owner);

                $query_complaint="SELECT * FROM complaints WHERE id=$_GET[id]";
				$result_complaint=mysqli_query($link, $query_complaint);
				$complaint=mysqli_fetch_assoc($result_complaint);

				$query_image="SELECT `image` FROM `image` WHERE idAnnouncements=$ad[id]";
				$result_image=mysqli_query($link, $query_image);

				$query_city="SELECT name_city FROM cities WHERE id=$ad[city]";
				$result_city=mysqli_query($link, $query_city);
				$city=mysqli_fetch_assoc($result_city);

				$query_user = "SELECT idAccessRight FROM users WHERE token='$_SESSION[Token]'";
				$result_user = mysqli_query($link, $query_user);
				$idAccessRight_user = mysqli_fetch_assoc($result_user);
			?>
		<main>
			<h2>
				<div class="headline">
					<span class="nameCar"> <? echo $brand["brand"]."  ".$model["model"]; ?></span>
					<span class="locationCar"><? echo $city["name_city"]; ?></span>
					<span class="price-span"> <? echo $ad["price"]; ?></span>
				</div>
			</h2>
			<script>
				document.addEventListener('DOMContentLoaded', function () {
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
								while($image=mysqli_fetch_assoc($result_image))
								{ ?>
									<div class="slider__item">
										<? 
											echo '<img class="img-fluid" src="data:image/jpeg;base64,'.base64_encode($image["image"]).'" loading="lazy">'; 
										?>
									</div>
								<? } ?>
						</div>
					</div>
					<a class="slider__control slider__control_prev" href="#" role="button" data-slide="prev"></a>
					<a class="slider__control slider__control_next" href="#" role="button" data-slide="next"></a>
				</div>
			</div>			
			<button class="btn" onclick="showOwner()" id="owner" data-bs-toggle="modal" data-bs-target="#exampleModal">Показать владельца</button>
			<script>
				function showOwner() 
				{
					document.getElementById("owner").classList.toggle('show');
				}
			</script>

		<? if ($idAccessRight_user["idAccessRight"] == 4) {	
		?>
			<button class="btn" onclick="deleteComfirmed()" id="deleteComfirmed" data-bs-toggle="modal" data-bs-target="#exampleModal1">Удалить</button>
		<? } ?>
		    <button class="btn" id="block" data-bs-toggle="modal" data-bs-target="#blockModal">Заблокировать объявление</button>
			<button class="btn" name="unblockAd" id="unblockAd" data-bs-toggle="modal" data-bs-target="#unblockAdModal">Удалить жалобу</button>
			<?
			

			$query_idUserSession="SELECT id FROM users WHERE token='$token'";
			$result_idUserSession=mysqli_query($link, $query_idUserSession);
			$idUserSession=mysqli_fetch_assoc($result_idUserSession);
			if((($idAccessRight_user["idAccessRight"] == 3) || ($idAccessRight_user["idAccessRight"] == 4)) && ($owner["idAccessRight"]!=2) && ($idUserSession["id"]!=$owner["id"])) { ?>
				<button class="btn" id="blockUser" data-bs-toggle="modal" data-bs-target="#blockUserModal">Заблокировать пользователя</button>
			<? } ?>

			<!-- Модальное окно данных продавца -->
			<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				    <div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Данные продавца</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="data">
								<span class="fio"><? echo $owner["surname"]." ".$owner["name"]." ".$owner["patronymic"] ?></span>
								<span class="phone"><? echo $owner["phone"]; ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Модальное окно данных продавца -->

			<!-- Модальное окно блокировки -->
			<div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<form method="post">
							<div class="modal-header">
								<h5 class="modal-title" id="blockModalLabel">Блокировка объявления</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<input type="hidden" name="report" value="<? echo $ad['id']; ?>">
							<div class="modal-body">
								<h4>Выберите причину</h4>
								<select class="cause form-select" name="selectCause" id="selectCause">
									<option selected="1" disabled="1">Выберите причину</option>
									<option>В объявлении обман</option>
									<option>Мошенничество</option>
									<option>Фотографии не соответствуют объявлению</option>
									<option>Неверные координаты продавца</option>
									<option>Это не автомобиль</option>
									<option>Баловство</option>
									<option>Неверно указана модель</option>
									<option>Другая причина</option>
								</select>
								<hr>
								<textarea name="description" placeholder="Опишите побробнее" cols="50" rows="5"></textarea>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
								<button type="submit" name="block" class="btn btn-primary">Заблокировать</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- Модальное окно блокировки -->

			<!-- Модальное окно удаления -->
			<div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<form method="post">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Подтверждение удаления</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<p>Подтвердите удаление этого объявления</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
								<button type="submit" name="delete" class="btn btn-primary">Удалить</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- Модальное окно удаления -->

			<!-- Модальное окно удаления жалобы -->
			<div class="modal fade" id="unblockAdModal" tabindex="-1" aria-labelledby="unblockAdModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<form method="post">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="unblockAdModalLabel">Подтверждение удаления жалобы</h5>
								<input type="hidden" name="report" value="<? echo $ad['id']; ?>">
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<p>Подтвердите удаление жалобы этого объявления</p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
								<button type="submit" name="unblockAd" class="btn btn-primary">Удалить жалобу</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<!-- Модальное окно удаления жалобы -->

			<?php
				if(isset($_POST["delete"]))
				{
					$query_delete="DELETE FROM announcements WHERE id=$id";
					mysqli_query($link, $query_delete);
					header("Location: index.php");
				}

				if(isset($_POST["unblockAd"]))
				{
					$idAnnouncements=$_POST["report"];;
					$query_unblock="DELETE FROM complaints WHERE idAnnouncements=$idAnnouncements";
					mysqli_query($link, $query_unblock);
					header("Location: complaints.php");
				}

				if(isset($_POST["block"]))
				{
					$name=$_POST["selectCause"];
					$description=$_POST["description"];
					$idAnnouncements=$_POST["report"];
					$query_block="UPDATE announcements SET block=1, reason_for_blocking='$name', block_description='$description' WHERE id=$idAnnouncements";
					mysqli_query($link, $query_block);
					$query_complaints="DELETE FROM complaints WHERE idAnnouncements=$idAnnouncements";
					mysqli_query($link, $query_complaints);
					header("Location: complaints.php");
				}
			?>
            <h3>Причина жалобы</h3>
            <div class="description">
				<p><? echo $complaint["nameComplaint"]; ?></p>
			</div>
			<? if(!empty($complaint["description"])) { ?>
            <h3>Описание жалобы</h3>
            <div class="description">
				<p><? $complaintAd=nl2br($complaint["description"]); echo $complaintAd ?></p>
			</div>
			<? } ?>

			<h3>Описание</h3>
			<div class="description">
				<p><? $description=nl2br($ad["description"]); echo $description ?></p>
			</div>
			<h3>Характеристики</h3>
			<div class="specifications">
				<div class="title-specifications">
					<p>Марка</p><br>
					<p>Модель</p><br>
					<p>Год</p><br>
					<p>Расположение руля</p><br> 
					<p>Кузов</p><br> 
					<p>Количество дверей</p><br>
					<p>Цвет</p><br>
					<p>Мощность, л.с.</p><br>
					<p>Тип двигателя</p><br>
					<p>Объем дивгателя</p><br>
					<p>Тип коробки передач</p><br>
					<p>Привод</p><br>
					<p>Пробег</p><br>
					<p>Состояние автомобиля</p><br>
					<p>Количество владельцев по ПТС</p><br>
				</div>
				<div class="data-specifications">
					<p><? echo $brand["brand"]; ?></p><br>
					<p><? echo $model["model"]; ?></p><br>
					<p><? echo $ad["yearOfIssue"]; ?></p><br>
					<p><? echo $ad["locationSteering"]; ?></p><br>
					<p><? echo $ad["carBody"]; ?></p><br>
					<p><? echo $ad["numberOfDoors"]; ?></p><br>
					<p><? echo $ad["color"]; ?></p><br>
					<p><? echo $ad["power"]; ?></p><br>
					<p><? echo $ad["typeEngine"]; ?></p><br>
					<p><? echo $ad["engineVolume"]." л"; ?></p><br>
					<p><? echo $ad["transmission"]; ?></p><br>
					<p><? echo $ad["driveUnit"]; ?></p><br>
					<p><? echo $ad["mileage"]; ?></p><br>
					<p><? echo $ad["condition"]; ?></p><br>
					<p><? echo $ad["numberOfOwners"]; ?></p><br>
				</div>
			</div>
        </main>
	</body>
</html>

<? 

	ob_end_flush(); 
?>