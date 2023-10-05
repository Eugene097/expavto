<?php
ob_start();
session_start();
include_once("active.php");
include_once("link.php");
include_once("examination.php");
include_once("blockedUser.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Создание объявления</title>
	<link rel="stylesheet" href="createAd.css">

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
		<h2>Создание объявления</h2>
		<form class="ourForm" method="post" name="ourForm" enctype="multipart/form-data">
			<h3>Основные параметры</h3>
			<div class="mainParameters">
				<div class="brand">
					<label>Марка автомобиля</label>
					<select name="brand_select" id="brand_select" onchange="select_model()">
						<option disabled selected>Выберите марку</option>
						<?
						$query = "SELECT brand FROM carBrands";
						$result = mysqli_query($link, $query);
						while ($brand = mysqli_fetch_assoc($result)) {
							echo "<option value='$brand[brand]'>" . $brand['brand'] . "</option>";
						}
						?>
					</select>
				</div><br>

				<div class="divModel">
					<label class="model">Модель автомобиля</label>
					<select id="model_select" name="model_select">
						<option disabled selected>Выберите марку</option>
					</select>
				</div><br>
				<div class="yearOfIssue">
					<label>Год выпуска</label>
					<input type="number" min="1950" max="2030" id="yearOfIssue" name="yearOfIssue" maxlength="4">
				</div><br>

				<div class="locationSteering">
					<label>Расположение руля</label>
					<select id="locationSteering" name="locationSteering">
						<option selected disabled>Выберите расположение руля</option>
						<option>Левое</option>
						<option>Правое</option>
					</select>
				</div><br>

				<div class="carBody">
					<label>Кузов</label>
					<select name="carBody" id="carBody">
						<option selected disabled>Выберите кузов</option>
						<option>Седан</option>
						<option>Хетчбэк</option>
						<option>Универсал</option>
						<option>Кабриолет</option>
						<option>Купе</option>
						<option>Внедорожник</option>
						<option>Фургон</option>
						<option>Миневэн</option>
						<option>Пикап</option>
					</select>
				</div><br>

				<div class="numberOfDoors">
					<label>Количество дверей</label>
					<input min="1" type="number" id="numberOfDoors" name="numberOfDoors">
				</div><br>

				<div class="color">
					<label>Цвет автомобиля</label>
					<select name="color" id="color">
						<option selected disabled>Выберите цвет</option>
						<option>Черный</option>
						<option>Серый</option>
						<option>Серебряный</option>
						<option>Белый</option>
						<option>Желтый</option>
						<option>Оранжевый</option>
						<option>Коричневый</option>
						<option>Красный</option>
						<option>Фиолетовый</option>
						<option>Синий</option>
						<option>Голубой</option>
						<option>Зеленый</option>
					</select>
				</div>
			</div>
			<h3>Технические параметры</h3>
			<div class="technicalSpecifications">
				<div class="power">
					<label>Мощность, л.с.</label>
					<input min="0" type="number" id="power" name="power">
				</div><br>
				<div class="typeEngine">
					<label>Тип двигателя</label>
					<select id="typeEngine" name="typeEngine">
						<option selected disabled>Выберите тип двигателя</option>
						<option>Бензиновый</option>
						<option>Дизельный</option>
						<option>Газ</option>
						<option>Гибрид</option>
						<option>Электро</option>
					</select>
				</div><br>
				<div class="engineVolume">
					<label>Объем двигателя, л</label>
					<input min="0" type="text" id="engineVolume" name="engineVolume">
				</div><br>
				<div class="transmission">
					<label>Тип коробки передач</label>
					<select id="transmission" name="transmission">
						<option selected disabled>Выберите тип коробки передач</option>
						<option>Механическая</option>
						<option>Автомат</option>
						<option>Вариатор</option>
						<option>Робот</option>
					</select>
				</div><br>
				<div class="driveUnit">
					<label>Привод</label>
					<select id="driveUnit" name="driveUnit">
						<option selected disabled>Выберите привод</option>
						<option>Передний</option>
						<option>Задний</option>
						<option>Полный</option>
						<option>Полный переключаемый</option>
					</select>
				</div><br>
			</div>
			<h3>Состояние и цена</h3>
			<div class="conditionAndPrice">
				<div class="mileage">
					<label>Пробег, км</label>
					<input min="1" type="number" id="mileage" name="mileage">
				</div><br>
				<div class="condition">
					<label>Состояние автомобиля</label>
					<select id="condition" name="condition">
						<option selected disabled>Выберите состояние автомобиля</option>
						<option value="Нет повреждений">Нет повреждений</option>
						<option value="Повреждён">Повреждён</option>
					</select>
				</div><br>
				<div class="numberOfOwners">
					<label>Количество владельцев по ПТС</label>
					<input min="0" type="number" id="numberOfOwners" name="numberOfOwners">
				</div><br>
				<div class="vin">
					<label>VIN</label>
					<input type="text" id="vin" maxlength="17" name="vin">
				</div><br>
				<div class="description">
					<label>Описание</label>
					<textarea name="description" id="description" maxlength="1000"></textarea>
				</div><br>
				<div class="price">
					<label>Цена, ₽</label>
					<input min="0" type="number" id="price" name="price" class="priceInput">
				</div><br>
				<h3>Изображение автомобиля</h3>
				<div class="image" id="imageDiv">
					<input class="input-image" type="file" id="image1" onchange="getImage(1)" name="image[]" accept=".jpg, .jpeg, .png" title="Выберите изображение">
					<input class="input-image" style="display: none" type="file" id="image2" onchange="getImage(2)" name="image[]" accept=".jpg, .jpeg, .png">
					<input class="input-image" style="display: none" type="file" id="image3" onchange="getImage(3)" name="image[]" accept=".jpg, .jpeg, .png">
					<input class="input-image" style="display: none" type="file" id="image4" onchange="getImage(4)" name="image[]" accept=".jpg, .jpeg, .png">
					<input class="input-image" style="display: none" type="file" id="image5" onchange="getImage(5)" name="image[]" accept=".jpg, .jpeg, .png">
				</div><br>
				<h3>Местоположение автомобиля</h3>
				<div class="location">
					<select name="region" onchange="select_city()" id="region">
						<option selected disabled>Выберите регион</option>
						<?
						$query_regions = "SELECT * FROM regions";
						$result_regions = mysqli_query($link, $query_regions);
						while ($regions = mysqli_fetch_assoc($result_regions)) {
							echo "<option value='$regions[name_region]'>" . $regions['name_region'] . "</option>";
						}
						?>
					</select>
					<select name="city" id="city">
						<option selected disabled>Выберите регион</option>
					</select>
				</div>
			</div>
			<input type="submit" class="sendAdd" name="sendAdd">
			<?php
			if (isset($_POST["sendAdd"])) {
				$query_user = "SELECT id FROM users WHERE token='$_SESSION[Token]'";
				$result_user = mysqli_query($link, $query_user);
				$id = mysqli_fetch_assoc($result_user);

				$brand = $_POST["brand_select"];
				$query_idBrand = "SELECT id FROM carbrands WHERE brand='$brand'";
				$result_brand = mysqli_query($link, $query_idBrand);
				$idBrand = mysqli_fetch_assoc($result_brand);
				$brandId = $idBrand["id"];

				$model = $_POST["model_select"];
				$query_idModel = "SELECT id FROM carmodels WHERE model='$model'";
				$result_model = mysqli_query($link, $query_idModel);
				$idModel = mysqli_fetch_assoc($result_model);
				$modelId = $idModel["id"];

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
				$vin = $_POST["vin"];
				$description = $_POST["description"];
				$price = $_POST["price"];
				$date = date("d.m.Y");
				$location = $_POST["city"];

				$query_city = "SELECT id, idRegion FROM cities WHERE name_city='$location'";
				$result_city = mysqli_query($link, $query_city);
				$row_city = mysqli_fetch_assoc($result_city);
				$idCity = $row_city["id"];
				$idRegion = $row_city["idRegion"];

				$imagetmp = array();

				//Получаем содержимое изображения 
				for ($i = 0; $i < 5; $i++) {
					if (empty($_FILES["image"]['tmp_name'][$i])) {
						break;
					} else $imagetmp[$i] = addslashes(file_get_contents($_FILES["image"]['tmp_name'][$i]));
				}

				$query_ad = "INSERT INTO announcements(idUser,idBrand, idModel, typeEngine, engineVolume, power, transmission, driveUnit, 
						color, carBody, mileage, locationSteering, numberOfDoors, `condition`, yearOfIssue, numberOfOwners, vin, description, price, date, region, city, block, `reason_for_blocking`, `block_description`) 
						VALUES ($id[id], $brandId, $modelId, '$typeEngine', '$engineVolume', $power, '$transmission', 
						'$driveUnit', '$color', '$carBody', '$mileage', '$locationSteering', $numberOfDoors,  '$condition', '$yearOfIssue', $numberOfOwners, 
						'$vin', '$description', '$price', '$date', $idRegion, $idCity, 1, 'Объявление на модерации', '')";

				if (mysqli_query($link, $query_ad)) {
					$query_idAd = "SELECT id FROM announcements WHERE idUser=$id[id] ORDER BY id DESC";
					$result_idAd = mysqli_query($link, $query_idAd);
					$idAd = mysqli_fetch_assoc($result_idAd);
					for ($i = 0; $i < count($imagetmp); $i++) {
						$query_image = "INSERT INTO image(image, idAnnouncements) VALUES ('$imagetmp[$i]', $idAd[id])";
						mysqli_query($link, $query_image);
					}
					header("Location: myads.php");
				}
			}
			?>
		</form>
	</main>
</body>
<script>
	function select_model() {
		var servResponse = document.querySelector('#model_select');

		var carBrand = document.getElementById("brand_select").value;

		var xhr = new XMLHttpRequest();

		xhr.open('POST', 'getModel.php');
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				servResponse.textContent = xhr.responseText;
				var i;
				var text = servResponse.textContent;
				var model = "";
				document.getElementById("model_select").options.text = "Выберите модель";
				let newOption = new Option("Выберите модель", "");
				document.getElementById("model_select").append(newOption);
				newOption.selected = true;
				newOption.disabled = true;

				for (i = 0; i < text.length; i++) {
					if (text[i] == ",") {
						let newOption = new Option(model, model);
						document.getElementById("model_select").append(newOption);
						model = "";
					} else model = model + text[i];
				}
			}
		}
		xhr.send('brand=' + carBrand);
	}

	function select_city() {
		var servResponse = document.querySelector('#city');

		var region = document.getElementById("region").value;

		var xhr = new XMLHttpRequest();

		xhr.open('POST', 'getCity.php');
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				servResponse.textContent = xhr.responseText;
				var i;
				var text = servResponse.textContent;
				var city = "";
				document.getElementById("city").options.text = "Выберите город";
				let newOption = new Option("Выберите город", "");
				document.getElementById("city").append(newOption);
				newOption.selected = true;
				newOption.disabled = true;

				for (i = 0; i < text.length; i++) {
					if (text[i] == ",") {
						let newOption = new Option(city, city);
						document.getElementById("city").append(newOption);
						city = "";
					} else city = city + text[i];
				}
			}
		}
		xhr.send('region=' + region);
	}

	function model_hidden_func() {
		let model = document.getElementById('model_select').value;
		document.getElementById('model_hidden').value = model;
		generation_hidden_func();
	}

	function brand_hidden_func() {
		let brand = document.getElementById('brand_select').value;
		document.getElementById('brand_hidden').value = brand;
	}

	function getImage(idImage) {
		f = document.getElementById("image" + idImage).files[0];

		var t = "";
		if (f) {
			t = URL.createObjectURL(f);
			localStorage.setItem('myImage', t);
		}
		t = localStorage.getItem('myImage');
		document.getElementById("image" + idImage).classList.remove("input-image");
		document.getElementById("image" + idImage).classList.add("ready-image");
		document.getElementById("image" + idImage).style.backgroundImage = "url(" + t + ")";
		document.getElementById("image" + idImage).style.backgroundRepeat = "no-repeat";
		document.getElementById("image" + idImage).style.backgroundSize = "contain";
		document.getElementById("image" + idImage).style.backgroundPosition = "center";

		let idNextImage = idImage + 1;
		if (idNextImage != 6) document.getElementById("image" + idNextImage).style.display = "inline-block";
	}
</script>

</html>

<?php

ob_end_flush();
?>