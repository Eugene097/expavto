<?
ob_start();
include_once("link.php");
include_once("active.php");
$id = $_GET["id"];
$token = $_SESSION["Token"];

$query = "SELECT * FROM announcements WHERE id=$id";
$result = mysqli_query($link, $query);
$ad = mysqli_fetch_assoc($result);

$query_model = "SELECT * FROM carmodels WHERE id=$ad[idModel]";
$result_model = mysqli_query($link, $query_model);
$model = mysqli_fetch_assoc($result_model);

$query_brand = "SELECT * FROM carbrands WHERE id=$model[idBrand]";
$result_brand = mysqli_query($link, $query_brand);
$brand = mysqli_fetch_assoc($result_brand);
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title><? echo $brand["brand"] . "  " . $model["model"]; ?></title>
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

	$query = "SELECT * FROM announcements WHERE id=$id";
	$result = mysqli_query($link, $query);
	$ad = mysqli_fetch_assoc($result);

	$query_model = "SELECT * FROM carmodels WHERE id=$ad[idModel]";
	$result_model = mysqli_query($link, $query_model);
	$model = mysqli_fetch_assoc($result_model);

	$query_brand = "SELECT * FROM carbrands WHERE id=$model[idBrand]";
	$result_brand = mysqli_query($link, $query_brand);
	$brand = mysqli_fetch_assoc($result_brand);

	$query_owner = "SELECT id, surname, name, patronymic, phone, token, idAccessRight FROM users WHERE id=$ad[idUser]";
	$result_owner = mysqli_query($link, $query_owner);
	$owner = mysqli_fetch_assoc($result_owner);

	$query_city = "SELECT name_city FROM cities WHERE id=$ad[city]";
	$result_city = mysqli_query($link, $query_city);
	$city = mysqli_fetch_assoc($result_city);

	$query_image = "SELECT image FROM image WHERE idAnnouncements=$ad[id]";
	$result_image = mysqli_query($link, $query_image);
	?>
	<main>
		<div id="snackbar"></div>
		<a class="back previous" onclick="javascript:history.back(); return false;">Назад</a>
		<h2>
			<div class="headline">
				<span class="nameCar"> <? echo $brand["brand"] . "  " . $model["model"]; ?></span>
				<span class="locationCar"><? echo $city["name_city"]; ?></span>
				<span class="price-span"> <? echo $ad["price"]; ?></span>
			</div>
		</h2>
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
		<?php
		$query_idUserSession="SELECT id FROM users WHERE token='$token'";
		$result_idUserSession=mysqli_query($link, $query_idUserSession);
		$idUserSession=mysqli_fetch_assoc($result_idUserSession);

		$query_user = "SELECT id, idAccessRight FROM users WHERE token='$_SESSION[Token]'";
		$result_user = mysqli_query($link, $query_user);
		$idAccessRight_user = mysqli_fetch_assoc($result_user);
		if($idAccessRight_user["idAccessRight"] != 2 && ($idUserSession["id"]!=$owner["id"]))
		{
		?>
		<button class="btn" onclick="showOwner()" id="owner" data-bs-toggle="modal" data-bs-target="#exampleModal">Показать владельца</button>
		<?php
		}
		if (($idAccessRight_user["idAccessRight"] == 4)) {
		?>
			<button class="btn" onclick="deleteComfirmed()" id="deleteComfirmed" data-bs-toggle="modal" data-bs-target="#exampleModal1">Удалить</button>
		<? }
		
		if((($idAccessRight_user["idAccessRight"] == 3) || ($idAccessRight_user["idAccessRight"] == 4)) && ($owner["idAccessRight"]!=2) && ($owner["idAccessRight"]!=4) && ($idUserSession["id"]!=$owner["id"])) { ?>
			<button class="btn" id="blockUser" data-bs-toggle="modal" data-bs-target="#blockUserModal">Заблокировать пользователя</button>
		<? }

		if (($ad["block"] == 1) && !($ad["reason_for_blocking"] == "Объявление на модерации") && ($idUserSession["id"]==$owner["id"]) && $ad["reason_for_blocking"] != "Блокировка администрацией") { ?>
			<button class="btn" name="moderation" id="moderation" data-bs-toggle="modal" data-bs-target="#moderationModal">Отправить на модерацию</button>
			<?	}

		if (isset($_POST["moderation"])) {
			$query_moderation = "UPDATE announcements SET reason_for_blocking='Объявление на модерации' WHERE id=$id";
			mysqli_query($link, $query_moderation);
			header("Location: cartCar.php?id=" . $id);
		}
		if (!empty($_SESSION["Token"])) {
			$query_edit = "SELECT id, idAccessRight FROM users WHERE token='$_SESSION[Token]'";
			$result_edit = mysqli_query($link, $query_edit);
			$idUserForEdit = mysqli_fetch_assoc($result_edit);
			$query_getIdUserAd = "SELECT id FROM announcements WHERE idUser=$idUserForEdit[id]";
			$result_getIdUserAd = mysqli_query($link, $query_getIdUserAd);
			while ($idAdForEdit = mysqli_fetch_assoc($result_getIdUserAd)) {
				if ($id == $idAdForEdit["id"] && $idUserForEdit["idAccessRight"]!=2 && $ad["reason_for_blocking"] != "Блокировка администрацией") {
			?>
					<button class="btn" id="editComfirmed" data-bs-toggle="modal" data-bs-target="#editComfirmedModal">Изменить</button>
		<? 		}
			}
		} ?>

		<!-- Данные владельца -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<? if (empty($token)) { ?>
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Необходимо зарегистрироваться или авторизоваться</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<button class="registration" onclick="window.location='registration.php'">Регистрация</button>
							<button class="entrance" onclick="window.location='login.php'">Вход</button>
						</div>
					<?
					} else { ?>
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Данные продавца</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="data">
								<span class="fio"><? echo $owner["surname"] . " " . $owner["name"] . " " . $owner["patronymic"] ?></span>
								<span class="phone"><? echo $owner["phone"]; ?></span>
							</div>
						</div>
					<?
					} ?>

				</div>
			</div>
		</div>
		<!-- Данные владельца -->

		<!-- Модальное окно подтверждения редактирования -->
		<div class="modal fade" id="editComfirmedModal" tabindex="-1" aria-labelledby="editComfirmedModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form method="post">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editComfirmedModalLabel">Подтверждение редактирования объявления</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<p>После модерации объявление попадёт снова на модерацию для проверки.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
							<button type="button" name="edit" class="btn btn-primary"><a style="color: white; text-decoration: none;" href="editAd.php?id=<? echo $id ?>">Подтвердить</a></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- Модальное окно подтверждения редактирования -->

		<!-- Отправка на модерацию -->
		<div class="modal fade" id="moderationModal" tabindex="-1" aria-labelledby="moderationModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form method="post">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="moderationModalLabel">Подтверждение отправки на модерацию</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<p>Вы устранили причину блокировки и готовы опубликовать обликовать объявление?</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
							<button type="submit" name="moderation" class="btn btn-primary">Подтвердить</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- Отправка на модерацию -->

		<!-- Подтверждение удаления -->
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
		<!-- Подтверждение удаления -->

		<!-- Подтверждение блокировки пользователя -->
		<div class="modal fade" id="blockUserModal" tabindex="-1" aria-labelledby="blockUserModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form name="formBlockUser" method="post">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="blockUserModalLabel">Подтверждение блокировки пользователя</h5>
							<button type="button" id="closeModalBlockUser" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<p>Подтвердите блокировку пользователя</p>
							<input type="hidden" name="idUser" id="idUser" value="<? echo $ad["idUser"]; ?>">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
							<button type="button" onclick="blockUser()" name="blockUser-btn" class="btn btn-primary">Заблокировать</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- Подтверждение блокировки пользователя -->
		<?php
		if (isset($_POST["delete"])) {
			$query_delete = "DELETE FROM announcements WHERE id=$id";
			mysqli_query($link, $query_delete);
			header("Location: index.php");
		}
		if ($ad['block']) {
		?>
			<h3>Причина блокировки</h3>
			<div class="description">
				<p><? echo $ad["reason_for_blocking"]; ?></p>
			</div>
			<? if ($ad['reason_for_blocking' == ""]) {
			?>
				<h3>Описание жалобы</h3>
				<div class="description">
					<p><? echo $ad["block_description"]; ?></p>
				</div>
		<?
			}
		}
		?>
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
				<p>Объем двигателя</p><br>
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
				<p><? echo $ad["engineVolume"] . " л"; ?></p><br>
				<p><? echo $ad["transmission"]; ?></p><br>
				<p><? echo $ad["driveUnit"]; ?></p><br>
				<p><? echo $ad["mileage"] . " км"; ?></p><br>
				<p><? echo $ad["condition"]; ?></p><br>
				<p><? echo $ad["numberOfOwners"]; ?></p><br>
			</div>
		</div>
	</main>
	<script>
		function showOwner() {
			document.getElementById("owner").classList.toggle('show');
		}

		function blockUser()
		{
			var servResponse = document.querySelector('#idUser'); 

            var idUser = document.getElementById("idUser").value; //Запоминание id пользователя

            document.forms.formBlockUser.onsubmit = function(e) {
                e.preventDefault();
            }

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'blockUser.php'); //Открытие blockUser.php
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    //Вывод сообщения
                    document.getElementById("snackbar").innerText="Пользователь заблокирован"; 
                    var x = document.getElementById("snackbar");
                    x.className = "show";
                    setTimeout(function() {
                        x.className = x.className.replace("show", "");
                    }, 3000);
                }
            }
            xhr.send(`idUser=` + idUser); //Отправка данных в blockUser.php
            location.reload();
		}
	</script>
</body>

</html>

<?

ob_end_flush();
?>