<?
ob_start();
include_once("link.php");
include_once("active.php");
include_once("blockedUser.php");
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

	$query_owner = "SELECT surname, name, patronymic, phone, token FROM users WHERE id=$ad[idUser]";
	$result_owner = mysqli_query($link, $query_owner);
	$owner = mysqli_fetch_assoc($result_owner);

	$query_city = "SELECT name_city FROM cities WHERE id=$ad[city]";
	$result_city = mysqli_query($link, $query_city);
	$city = mysqli_fetch_assoc($result_city);

	$query_image = "SELECT image FROM image WHERE idAnnouncements=$ad[id]";
	$result_image = mysqli_query($link, $query_image);
	?>
	<main>
		<a class="back previous" onclick="javascript:history.back(); return false;">Назад</a>
		<hr>
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
								echo '<img class="img-fluid" src="data:image/jpeg;base64,' . base64_encode($image["image"]) . '" loading="lazy">';
								?>
							</div>
						<? } ?>
					</div>
				</div>
				<a class="slider__control slider__control_prev" href="#" role="button" data-slide="prev"></a>
				<a class="slider__control slider__control_next" href="#" role="button" data-slide="next"></a>
			</div>
		</div>

		<!-- Modal -->
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
		<!-- Modal -->

		<!-- Modal -->
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
		<!-- Modal -->

		<!-- Modal -->
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

			</div>
		</div>
		<!-- Modal -->

		<form method="post">
			<script>
				function showOwner() {
					document.getElementById("owner").classList.toggle('show');
				}
			</script>
			<?php
			$query_user = "SELECT idAccessRight FROM users WHERE token='$_SESSION[Token]'";
			$result_user = mysqli_query($link, $query_user);
			$idAccessRight_user = mysqli_fetch_assoc($result_user);
			if (($idAccessRight_user["idAccessRight"] == 2) || ($idAccessRight_user["idAccessRight"] == 3)) {
			?>
				<button class="btn" onclick="deleteComfirmed()" id="deleteComfirmed" data-bs-toggle="modal" data-bs-target="#exampleModal1">Удалить</button>
			<? }

			if (($ad["block"] == 1) && !($ad["reason_for_blocking"] == "Модерация")) { ?>
				<button class="btn" name="moderation" id="moderation" data-bs-toggle="modal" data-bs-target="#moderationModal">Отправить на модерацию</button>

			<?	}

			if (isset($_POST["moderation"])) {
				$query_moderation = "UPDATE announcements SET reason_for_blocking='Модерация' WHERE id=$id";
				mysqli_query($link, $query_moderation);
				header("Location: cartCar.php?id=" . $id);
			}
			?>

			<input class="btn" type="submit" name="saveChange" id="saveChange" value="Сохранить">
			<?php
			if (isset($_POST["delete"])) {
				$query_delete = "DELETE FROM announcements WHERE id=$id";
				mysqli_query($link, $query_delete);
				header("Location: index.php");
			}
			?>
			<?
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
				<textarea id="descriptionEdit" name="description"><? echo $ad["description"]; ?></textarea>
			</div>
			<h3>Изменение цены</h3>
			<div class="description">
				<input type="number" id="priceEdit" name="priceEdit" value="<? echo $ad["price"]; ?>"> Руб.
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
				<div class="data-specifications" id="editData">
					<p><? echo $brand["brand"]; ?></p><br>
					<p><? echo $model["model"]; ?></p><br>
					<p><input type="text" minlength="4" name="yearOfIssue" value="<? echo $ad["yearOfIssue"]; ?>"></p><br>
					<p>
						<select name="locationSteering">
							<option selected disabled>Выберите расположение руля</option>
							<option <? if ($ad["locationSteering"] == "Левое") echo "selected"; ?>>Левое</option>
							<option <? if ($ad["locationSteering"] == "Правое") echo "selected"; ?>>Правое</option>
						</select>
					</p><br>
					<p>
						<select name="carBody" id="carBody">
							<option selected disabled>Выберите кузов</option>
							<option <? if ($ad["carBody"] == "Седан") echo "selected"; ?>>Седан</option>
							<option <? if ($ad["carBody"] == "Хетчбэк") echo "selected"; ?>>Хетчбэк</option>
							<option <? if ($ad["carBody"] == "Универсал") echo "selected"; ?>>Универсал</option>
							<option <? if ($ad["carBody"] == "Кабриолет") echo "selected"; ?>>Кабриолет</option>
							<option <? if ($ad["carBody"] == "Купе") echo "selected"; ?>>Купе</option>
							<option <? if ($ad["carBody"] == "Внедорожник") echo "selected"; ?>>Внедорожник</option>
							<option <? if ($ad["carBody"] == "Фургон") echo "selected"; ?>>Фургон</option>
							<option <? if ($ad["carBody"] == "Миневэн") echo "selected"; ?>>Миневэн</option>
							<option <? if ($ad["carBody"] == "Пикап") echo "selected"; ?>>Пикап</option>
						</select>
					</p><br>
					<p><input type="text" name="numberOfDoors" value="<? echo $ad["numberOfDoors"]; ?>"></p><br>
					<p>
						<select name="color" id="color">
							<option selected disabled>Выберите цвет</option>
							<option <? if ($ad["color"] == "Черный") echo "selected"; ?>>Черный</option>
							<option <? if ($ad["color"] == "Серый") echo "selected"; ?>>Серый</option>
							<option <? if ($ad["color"] == "Серебряный") echo "selected"; ?>>Серебряный</option>
							<option <? if ($ad["color"] == "Белый") echo "selected"; ?>>Белый</option>
							<option <? if ($ad["color"] == "Желтый") echo "selected"; ?>>Желтый</option>
							<option <? if ($ad["color"] == "Оранжевый") echo "selected"; ?>>Оранжевый</option>
							<option <? if ($ad["color"] == "Коричневый") echo "selected"; ?>>Коричневый</option>
							<option <? if ($ad["color"] == "Красный") echo "selected"; ?>>Красный</option>
							<option <? if ($ad["color"] == "Фиолетовый") echo "selected"; ?>>Фиолетовый</option>
							<option <? if ($ad["color"] == "Синий") echo "selected"; ?>>Синий</option>
							<option <? if ($ad["color"] == "Голубой") echo "selected"; ?>>Голубой</option>
							<option <? if ($ad["color"] == "Зеленый") echo "selected"; ?>>Зеленый</option>
						</select>
					</p><br>
					<p><input type="text" name="power" value="<? echo $ad["power"]; ?>"></p><br>
					<p>
						<select id="typeEngine" name="typeEngine">
							<option selected disabled>Выберите тип двигателя</option>
							<option <? if ($ad["typeEngine"] == "Бензиновый") echo "selected"; ?>>Бензиновый</option>
							<option <? if ($ad["typeEngine"] == "Дизельный") echo "selected"; ?>>Дизельный</option>
							<option <? if ($ad["typeEngine"] == "Газ") echo "selected"; ?>>Газ</option>
							<option <? if ($ad["typeEngine"] == "Гибрид") echo "selected"; ?>>Гибрид</option>
							<option <? if ($ad["typeEngine"] == "Электро") echo "selected"; ?>>Электро</option>
						</select>
					</p><br>
					<p><input type="text" name="engineVolume" value="<? echo $ad["engineVolume"]; ?>"> л</p><br>
					<p>
						<select id="transmission" name="transmission">
							<option selected disabled>Выберите тип коробки передач</option>
							<option <? if ($ad["transmission"] == "Механическая") echo "selected"; ?>>Механическая</option>
							<option <? if ($ad["transmission"] == "Автомат") echo "selected"; ?>>Автомат</option>
							<option <? if ($ad["transmission"] == "Вариатор") echo "selected"; ?>>Вариатор</option>
							<option <? if ($ad["transmission"] == "Робот") echo "selected"; ?>>Робот</option>
						</select>
					</p><br>
					<p>
						<select id="driveUnit" name="driveUnit">
							<option selected disabled>Выберите привод</option>
							<option <? if ($ad["driveUnit"] == "Передний") echo "selected"; ?>>Передний</option>
							<option <? if ($ad["driveUnit"] == "Задний") echo "selected"; ?>>Задний</option>
							<option <? if ($ad["driveUnit"] == "Полный") echo "selected"; ?>>Полный</option>
							<option <? if ($ad["driveUnit"] == "Полный переключаемый") echo "selected"; ?>>Полный переключаемый</option>
						</select>
					</p><br>
					<p><input type="text" name="mileage" value="<? echo $ad["mileage"]; ?>"> км</p><br>
					<p>
						<select id="condition" name="condition">
							<option selected disabled>Выберите состояние автомобиля</option>
							<option <? if ($ad["condition"] == "Повреждён") echo "selected"; ?> value="Повреждён">Повреждён</option>
							<option <? if ($ad["condition"] == "Нет повреждений") echo "selected"; ?> value="Нет повреждений">Нет повреждений</option>
						</select>
					</p><br>
					<p><input type="text" name="numberOfOwners" value="<? echo $ad["numberOfOwners"]; ?>"></p><br>
		</form>
		</div>
		</div>
	</main>
</body>

</html>
<?
if (isset($_POST["saveChange"])) {
	$typeEngine = $_POST["typeEngine"];
	$engineVolume = $_POST["engineVolume"];
	for ($i = 0; $i < strlen($engineVolume); $i++) {
		if ($engineVolume[$i] == ",") $engineVolume[$i] = ".";
	}
	$power = $_POST["power"];
	$transmission = $_POST["transmission"];
	$driveUnit = $_POST["driveUnit"];
	$color = $_POST["color"];
	$carBody = $_POST["carBody"];
	$mileage = $_POST["mileage"];
	$locationSteering = $_POST["locationSteering"];
	$numberOfDoors = $_POST["numberOfDoors"];
	$condition = $_POST["condition"];
	$yearOfIssue = $_POST["yearOfIssue"];
	$numberOfOwners = $_POST["numberOfOwners"];
	$description = $_POST["description"];
	$price = $_POST["priceEdit"];

	$query_update = "UPDATE announcements SET typeEngine='$typeEngine', engineVolume='$engineVolume', power=$power, transmission='$transmission',
		driveUnit='$driveUnit', color='$color', carBody='$carBody', mileage=$mileage, locationSteering='$locationSteering', numberOfDoors=$numberOfDoors, 
		`condition`='$condition', yearOfIssue='$yearOfIssue', numberOfOwners=$numberOfOwners, 
		description='$description', price='$price', block=1, reason_for_blocking='Модерация' WHERE id=$id";
	if (mysqli_query($link, $query_update)) header("Location: myads.php");
}
?>

<?
ob_end_flush();
?>