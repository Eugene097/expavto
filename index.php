<?php
ob_start();
session_start();
include_once("active.php");
include_once("link.php");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Главная страница</title>
	<link rel="stylesheet" href="index.css">

	<link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">

	<!-- Bootstrap CSS (jsDelivr CDN) -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

	<!-- Bootstrap Bundle JS (jsDelivr CDN) -->
	<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body id="body">
	<?php
	include_once("menu.php");
	?>
	<main>
		<div class="sidenav" id="sidenav">
			
			<h2>Фильтры </h2><br>
			<div class="CarBrand">
				<center>
					<form method="post" name="ourForm">
						<h4>Марка автомобиля</h4>
						<select class="form-select" name="brand_select" id="brand_select" onchange="select_model()">
							<option value="" disabled selected>Выберите марку</option>
							<?
							$query_brand = "SELECT brand FROM carBrands";
							$result_brand = mysqli_query($link, $query_brand);
							while ($brand = mysqli_fetch_assoc($result_brand)) {
								if (($_POST["brand_select"] == $brand['brand']) || ($_GET["carBrand"] == $brand['brand'])) {
									echo "<option selected value='$brand[brand]'>" . $brand['brand'] . "</option>";
								} else echo "<option value='$brand[brand]'>" . $brand['brand'] . "</option>";
							}
							?>
						</select><br>

						<h4>Модель автомобиля</h4>
						<select class="form-select" id="model_select" name="model_select">
							<option disabled selected>Выберите модель</option>
						</select><br>

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
										var selectedModel = "<?
																if (!empty($_POST['model_select'])) echo $_POST["model_select"];
																else  if (!empty($_GET['carModel'])) echo $_GET["carModel"]; ?>";

										document.getElementById("model_select").options.text = "Выберите модель";
										let newOption = new Option("Выберите модель", "");
										document.getElementById("model_select").append(newOption);
										newOption.selected = true;
										newOption.disabled = true;

										for (i = 0; i < text.length; i++) {
											if (text[i] == ",") {
												let newOption = new Option(model, model);
												document.getElementById("model_select").append(newOption);
												if (selectedModel == model) newOption.selected = true;
												model = "";
											} else model = model + text[i];
										}

									}
								}
								xhr.send('brand=' + carBrand);
							}
						</script>

						<? if (!empty($_POST["brand_select"]) || !empty($_GET["carBrand"])) ?>
						<script>
							select_model();
						</script>

						<h4>Год выпуска</h4>
						<div class="year_auto">
							<div class="input-group mb-3">
								<input type="number" min="1950" max="2030" name="year_from" class="form-control" placeholder="от" value="<?
																													if (!empty($_POST['year_from'])) echo $_POST["year_from"];
																													else  if (!empty($_GET['yearFrom']) || $_GET['yearFrom'] != 0) echo $_GET["yearFrom"]; ?>">
								<input type="number" min="1950" max="2030" name="year_up_to" class="form-control" placeholder="до" value="<?
																													if (!empty($_POST['year_up_to'])) {
																														$query_year = "SELECT MAX(yearOfIssue) FROM announcements WHERE block=0";
																														$result_year = mysqli_query($link, $query_year);
																														$max_year = mysqli_fetch_array($result_year);
																														if ($_POST["year_up_to"] != $max_year[0]) echo $_POST["year_up_to"];
																													} else if (!empty($_GET['year_up_to'])) {
																														$query_year = "SELECT MAX(yearOfIssue) FROM announcements WHERE block=0";
																														$result_year = mysqli_query($link, $query_year);
																														$max_year = mysqli_fetch_array($result_year);
																														if ($_GET["year_up_to"] != $max_year[0]) echo $_GET["year_up_to"];
																													} ?>">
							</div>
						</div>

						<h4>Цена, тыс. руб</h4>
						<div class="price_auto">
							<div class="input-group mb-3">
								<input type="number" min="0" name="price_from" class="form-control" placeholder="от" value="<?
																													if (!empty($_POST['price_from'])) echo $_POST["price_from"];
																													else  if (!empty($_GET['priceFrom']) || $_GET['priceFrom'] != 0) echo $_GET["priceFrom"]; ?>">
								<input type="number" min="1" name="price_up_to" class="form-control" placeholder="до" value="<?
																														if (!empty($_POST['price_up_to'])) {
																															$query_price = "SELECT MAX(price) FROM announcements WHERE block=0";
																															$result_price = mysqli_query($link, $query_price);
																															$max_price = mysqli_fetch_array($result_price);
																															if ($_POST["price_up_to"] != $max_price[0] / 1000) echo $_POST["price_up_to"];
																														} else if (!empty($_GET['price_up_to'])) {
																															$query_price = "SELECT MAX(price) FROM announcements WHERE block=0";
																															$result_price = mysqli_query($link, $query_price);
																															$max_price = mysqli_fetch_array($result_price);
																															if ($_GET["price_up_to"] != $max_price[0] / 1000) echo $_GET["price_up_to"];
																														} ?>">
							</div>
						</div>

						<h4 style="margin-top: 10px;">Тип кузова</h4>
						<div class="form-check" id="carBody_div">
							<div>
								<input class="form-check-input" <? $i = 0;
																if ("Седан" == $_GET["carBody" . $i] || "Седан" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Седан" type="checkbox" id="sedan">
								<label class="form-check-label" for="sedan">Седан</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Хетчбэк" == $_GET["carBody" . $i] || "Хетчбэк" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Хетчбэк" type="checkbox" id="hatchback">
								<label class="form-check-label" for="hatchback">Хетчбэк</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Универсал" == $_GET["carBody" . $i] || "Универсал" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Универсал" type="checkbox" id="station-wagon">
								<label class="form-check-label" for="station-wagon">Универсал</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Кабриолет" == $_GET["carBody" . $i] || "Кабриолет" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Кабриолет" type="checkbox" id="cabriolet">
								<label class="form-check-label" for="cabriolet">Кабриолет</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Купе" == $_GET["carBody" . $i] || "Купе" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Купе" type="checkbox" id="cupe">
								<label class="form-check-label" for="cupe">Купе</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Внедорожник" == $_GET["carBody" . $i] || "Внедорожник" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Внедорожник" type="checkbox" id="offRoad">
								<label class="form-check-label" for="offRoad">Внедорожник</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Фургон" == $_GET["carBody" . $i] || "Фургон" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Фургон" type="checkbox" id="van">
								<label class="form-check-label" for="van">Фургон</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Миневэн" == $_GET["carBody" . $i] || "Миневэн" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Миневэн" type="checkbox" id="minivan">
								<label class="form-check-label" for="minivan">Миневэн</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Пикап" == $_GET["carBody" . $i] || "Пикап" == $_POST["carBody"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="carBody[]" value="Пикап" type="checkbox" id="pickup">
								<label class="form-check-label" for="pickup">Пикап</label>
							</div>
						</div>

						<h4 style="margin-top: 10px;">Тип двигателя</h4>
						<div class="form-check" id="typeEngine">
							<div>
								<input class="form-check-input" <? $i = 0;
																if ("Бензиновый" == $_GET["typeEngine" . $i] || "Бензиновый" == $_POST["typeEngine"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="typeEngine[]" value="Бензиновый" type="checkbox" id="petrol">
								<label class="form-check-label" for="petrol">Бензин</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Дизельный" == $_GET["typeEngine" . $i] || "Дизельный" == $_POST["typeEngine"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="typeEngine[]" value="Дизельный" type="checkbox" id="diesel">
								<label class="form-check-label" for="diesel">Дизель</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Газ" == $_GET["typeEngine" . $i] || "Газ" == $_POST["typeEngine"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="typeEngine[]" value="Газ" type="checkbox" id="gas">
								<label class="form-check-label" for="gas">Газ</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Электро" == $_GET["typeEngine" . $i] || "Электро" == $_POST["typeEngine"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="typeEngine[]" value="Электро" type="checkbox" id="electro">
								<label class="form-check-label" for="electro">Электро</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Гибрид" == $_GET["typeEngine" . $i] || "Гибрид" == $_POST["typeEngine"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="typeEngine[]" value="Гибрид" type="checkbox" id="hybrid">
								<label class="form-check-label" for="hybrid">Гибрид</label>
							</div>
						</div>

						<h4 style="margin-top: 10px;">Пробег, тыс. км.</h4>
						<div class="mileage_auto">
							<div class="input-group mb-3">
								<input type="number" min="0" name="mileage_from" class="form-control" placeholder="от" value="<?
																														if (!empty($_POST['mileage_from'])) echo $_POST["mileage_from"];
																														else  if (!empty($_GET['mileage_from']) || $_GET['mileage_from'] != 0) echo $_GET["mileage_from"]; ?>">
								<input type="number" min="1" name="mileage_up_to" class="form-control" placeholder="до" value="<?
																														if (!empty($_POST['mileage_up_to'])) {
																															$query_mileage = "SELECT MAX(mileage) FROM announcements WHERE block=0";
																															$result_mileage = mysqli_query($link, $query_mileage);
																															$max_mileage = mysqli_fetch_array($result_mileage);
																															if ($_POST["mileage_up_to"] != $max_mileage[0] / 1000) echo $_POST["mileage_up_to"];
																														} else if (!empty($_GET['mileage_up_to'])) {
																															$query_mileage = "SELECT MAX(mileage) FROM announcements WHERE block=0";
																															$result_mileage = mysqli_query($link, $query_mileage);
																															$max_mileage = mysqli_fetch_array($result_mileage);
																															if ($_GET["mileage_up_to"] != $max_mileage[0] / 1000) echo $_GET["mileage_up_to"];
																														} ?>">
							</div>
						</div>

						<h4>Тип привода</h4>
						<div class="form-check" id="typeDriveUnit">
							<div>
								<input class="form-check-input" <? $i = 0;
																if ("Передний" == $_GET["driveUnit" . $i] || "Передний" == $_POST["driveUnit"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="driveUnit[]" value="Передний" type="checkbox" id="front-wheel">
								<label class="form-check-label" for="front-wheel">Передний</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Задний" == $_GET["driveUnit" . $i] || "Задний" == $_POST["driveUnit"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="driveUnit[]" value="Задний" type="checkbox" id="rear">
								<label class="form-check-label" for="rear">Задний</label>
							</div>
							<div>
								<input class="form-check-input" <? if ("Полный" == $_GET["driveUnit" . $i] || "Полный" == $_POST["driveUnit"][$i]) {
																	echo "checked=1";
																	$i++;
																} ?> name="driveUnit[]" value="Полный" type="checkbox" id="four-wheel ">
								<label class="form-check-label" for="four-wheel ">Полный</label>
							</div>
						</div><br>

						<h4>Расположение руля</h4>
						<select class="form-select steeringWheelLocation" name="steeringWheelLocation" id="steeringWheelLocation">
							<option disabled>Выберите расположение руля</option>
							<option selected value="Любое">Любое</option>
							<option <? if (($_GET["locationSteering"] == "Левое") || ($_POST["steeringWheelLocation"] == "Левое")) echo "selected"; ?> value="Левое">Левое</option>
							<option <? if (($_GET["locationSteering"] == "Правое") || ($_POST["steeringWheelLocation"] == "Правое")) echo "selected"; ?> value="Правое">Правое</option>
						</select><br>

						<h4>Мощность, л.с.</h4>
						<div class="power_auto">
							<div class="input-group mb-3">
								<input type="number" name="power_from" min="0" class="form-control" placeholder="от" value="<?
																													if (!empty($_POST['power_from'])) echo $_POST["power_from"];
																													else  if (!empty($_GET['power_from']) || $_GET['power_from'] != 0) echo $_GET["power_from"]; ?>">
								<input type="number" name="power_up_to" min="1" class="form-control" placeholder="до" value="<?
																														if (!empty($_POST['power_up_to'])) {
																															$query_mileage = "SELECT MAX(mileage) FROM announcements WHERE block=0";
																															$result_mileage = mysqli_query($link, $query_mileage);
																															$max_mileage = mysqli_fetch_array($result_mileage);
																															if ($_POST["power_up_to"] != $max_mileage[0]) echo $_POST["power_up_to"];
																														} else if (!empty($_GET['power_up_to'])) {
																															$query_power = "SELECT MAX(power) FROM announcements WHERE block=0";
																															$result_power = mysqli_query($link, $query_power);
																															$max_power = mysqli_fetch_array($result_power);
																															if ($_GET["power_up_to"] != $max_power[0]) echo $_GET["power_up_to"];
																														} ?>">
							</div>
						</div>
						<input type="submit" name="accept" id="accept" class="accept" value="Применить"><br>
						<input type="submit" style="background-color: #AE223E;" name="reset" class="accept" value="Сбросить">
					</form>
				</center>
			</div>
		</div>
		<?
		if (isset($_POST["reset"])) {
			header("Location: index.php?page=1");
		}

		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}

		// Назначаем количество данных на одной странице
		$size_page = 7;
		// Вычисляем с какого объекта начать выводить
		$offset = ($page - 1) * $size_page;

		//Получение бренда
		if (!empty($_POST["brand_select"])) $brand = $_POST["brand_select"];
		else if (!empty($_GET["carBrand"])) $brand = $_GET["carBrand"];

		//Получение модели
		if (!empty($_POST["model_select"])) $model = $_POST["model_select"];
		else if (!empty($_GET["carModel"])) $model = $_GET["carModel"];

		$query = "SELECT * FROM announcements";
		$inquiry = " WHERE block=0";
		if (!empty($brand)) {
			$query_brand = "SELECT id FROM carbrands WHERE brand='$brand'";
			$result_brand = mysqli_query($link, $query_brand);
			$row_brand = mysqli_fetch_assoc($result_brand);

			$inquiry = $inquiry . " AND idBrand=$row_brand[id]";
			$link_filter = "&carBrand=" . $brand; //Добавление в ссылку
		}
		if (!empty($model)) {
			$query_brand = "SELECT id FROM carbrands WHERE brand='$brand'";
			$result_brand = mysqli_query($link, $query_brand);
			$row_brand = mysqli_fetch_assoc($result_brand);

			$query_model = "SELECT id FROM carmodels WHERE model='$model'";
			$result_model = mysqli_query($link, $query_model);
			$row_model = mysqli_fetch_assoc($result_model);

			$inquiry = $inquiry . " AND idModel=$row_model[id]";
			$link_filter = $link_filter . "&carModel=" . $model;
		}

		//Фильтрация года выпуска машины
		if (!empty($_POST["year_from"])) $year_from = $_POST["year_from"];
		else 
			if (!empty($_GET["yearFrom"])) $year_from = $_GET["yearFrom"];
		else $year_from = 0;
		$link_filter = $link_filter . "&yearFrom=" . $year_from;

		if (!empty($_POST["year_up_to"])) $year_up_to = $_POST["year_up_to"];
		else
			if (!empty($_GET["year_up_to"])) $year_up_to = $_GET["year_up_to"];
			else {
				$query_year = "SELECT MAX(yearOfIssue) FROM announcements";
				$result_year = mysqli_query($link, $query_year);
				$max_year = mysqli_fetch_array($result_year);
				$year_up_to = $max_year[0];
			}
		$link_filter = $link_filter . "&year_up_to=" . $year_up_to;

		$inquiry = $inquiry . " AND yearOfIssue BETWEEN $year_from AND $year_up_to";

		//Фильтрация цены
		if (!empty($_POST["price_from"])) $price_from = $_POST["price_from"] * 1000;
		else 
			if (!empty($_GET["priceFrom"])) $price_from = $_GET["priceFrom"] * 1000;
		else $price_from = 0;
		$link_filter = $link_filter . "&priceFrom=" . $price_from / 1000;

		if (!empty($_POST["price_up_to"])) $price_up_to = $_POST["price_up_to"] * 1000;
		else
			if (!empty($_GET["price_up_to"])) $price_up_to = $_GET["price_up_to"] * 1000;
		else {
			$query_price = "SELECT MAX(price) FROM announcements";
			$result_price = mysqli_query($link, $query_price);
			$max_price = mysqli_fetch_array($result_price);
			$price_up_to = $max_price[0];
		}
		$link_filter = $link_filter . "&price_up_to=" . $price_up_to / 1000;

		$inquiry = $inquiry . " AND price BETWEEN $price_from AND $price_up_to";

		//Кузов автомобиля
		if (!empty($_POST["carBody"])) $carBody = $_POST["carBody"];
		else {
			for ($i = 0; $i < 9; $i++) {
				if (!empty($_GET["carBody" . $i])) {
					$carBody[$i] = $_GET["carBody" . $i];
				}
			}
		}
		if (!empty($carBody)) {
			$count = count($carBody);
			if ($count == 1) {
				$inquiry = $inquiry . " AND carBody='$carBody[0]'";
				$link_filter = $link_filter . "&carBody0=" . $carBody[0];
			} else for ($i = 0; $i < $count; $i++) {
				if ($i == 0) {
					$inquiry = $inquiry . " AND (carBody='$carBody[$i]'";
				} else 
					if ($i + 1 == $count) $inquiry = $inquiry . " OR carBody='$carBody[$i]')";
					else $inquiry = $inquiry . " OR carBody='$carBody[$i]'";

				$link_filter = $link_filter . "&carBody" . $i . "=" . $carBody[$i];
			}
		}

		//Тип двигателя
		if (!empty($_POST["typeEngine"])) $typeEngine = $_POST["typeEngine"];
		else {
			for ($i = 0; $i < 5; $i++) {
				if (!empty($_GET["typeEngine" . $i])) {
					$typeEngine[$i] = $_GET["typeEngine" . $i];
				}
			}
		}
		if (!empty($typeEngine)) {
			$count = count($typeEngine);
			if ($count == 1) {
				$inquiry = $inquiry . " AND typeEngine='$typeEngine[0]'";
				$link_filter = $link_filter . "&typeEngine0=" . $typeEngine[0];
			} else for ($i = 0; $i < $count; $i++) {
				if ($i == 0) {
					$inquiry = $inquiry . " AND (typeEngine='$typeEngine[$i]'";
				} else 
							if ($i + 1 == $count) $inquiry = $inquiry . " OR typeEngine='$typeEngine[$i]')";
				else $inquiry = $inquiry . " OR typeEngine='$typeEngine[$i]'";

				$link_filter = $link_filter . "&typeEngine" . $i . "=" . $typeEngine[$i];
			}
		}

		//Километраж
		if (!empty($_POST["mileage_from"])) $mileage_from = $_POST["mileage_from"] * 1000;
		else 
					if (!empty($_GET["mileage_from"])) $mileage_from = $_GET["mileage_from"] * 1000;
		else $mileage_from = 0;
		$link_filter = $link_filter . "&mileage_from=" . $mileage_from / 1000;

		if (!empty($_POST["mileage_up_to"])) $mileage_up_to = $_POST["mileage_up_to"] * 1000;
		else
					if (!empty($_GET["mileage_up_to"])) $mileage_up_to = $_GET["mileage_up_to"] * 1000;
		else {
			$query_mileage = "SELECT MAX(mileage) FROM announcements";
			$result_mileage = mysqli_query($link, $query_mileage);
			$max_mileage = mysqli_fetch_array($result_mileage);
			$mileage_up_to = $max_mileage[0];
		}
		$link_filter = $link_filter . "&mileage_up_to=" . $mileage_up_to / 1000;

		$inquiry = $inquiry . " AND mileage BETWEEN $mileage_from AND $mileage_up_to";

		//Тип привода
		if (!empty($_POST["driveUnit"])) $driveUnit = $_POST["driveUnit"];
		else {
			for ($i = 0; $i < 3; $i++) {
				if (!empty($_GET["driveUnit" . $i])) {
					$driveUnit[$i] = $_GET["driveUnit" . $i];
				}
			}
		}
		if (!empty($driveUnit)) {
			$count = count($driveUnit);
			if ($count == 1) {
				$inquiry = $inquiry . " AND driveUnit='$driveUnit[0]'";
				$link_filter = $link_filter . "&driveUnit0=" . $driveUnit[0];
			} else for ($i = 0; $i < $count; $i++) {
				if ($i == 0) {
					$inquiry = $inquiry . " AND (driveUnit='$driveUnit[$i]'";
				} else 
							if ($i + 1 == $count) $inquiry = $inquiry . " OR driveUnit='$driveUnit[$i]')";
				else $inquiry = $inquiry . " OR driveUnit='$driveUnit[$i]'";

				$link_filter = $link_filter . "&driveUnit" . $i . "=" . $driveUnit[$i];
			}
		}

		//Расположение руля
		if (($_POST["steeringWheelLocation"] != "Любое"))
			if (!empty($_POST["steeringWheelLocation"])) {
				$steeringWheelLocation = $_POST["steeringWheelLocation"];
				$inquiry = $inquiry . " AND locationSteering='$steeringWheelLocation'";
				$link_filter = $link_filter . "&locationSteering=" . $steeringWheelLocation;
			} else
					if (!empty($_GET["locationSteering"])) {
				$steeringWheelLocation = $_GET["locationSteering"];
				$inquiry = $inquiry . " AND locationSteering='$steeringWheelLocation'";
				$link_filter = $link_filter . "&locationSteering=" . $steeringWheelLocation;
			}

		//Мощность автомобиля
		if (!empty($_POST["power_from"])) $power_from = $_POST["power_from"];
		else 
					if (!empty($_GET["power_from"]))	$power_from = $_GET["power_from"];
		else $power_from = 0;
		$link_filter = $link_filter . "&power_from=" . $power_from;

		if (!empty($_POST["power_up_to"])) $power_up_to = $_POST["power_up_to"];
		else 
					if (!empty($_GET["power_up_to"])) $power_up_to = $_GET["power_up_to"];
		else {
			$query_power = "SELECT MAX(power) FROM announcements";
			$result_power = mysqli_query($link, $query_power);
			$max_power = mysqli_fetch_array($result_power);
			$power_up_to = $max_power[0];
		}
		$link_filter = $link_filter . "&power_up_to=" . $power_up_to;

		$inquiry = $inquiry . " AND power BETWEEN $power_from AND $power_up_to";

		//Добавление города в фильтрацию
		if (!isset($_POST["allCities"])) {
			if (!empty($_POST["city"])) $city = $_POST["city"];
			else if (!empty($_POST["region"])) $region = $_POST["region"];
			else if (!empty($_COOKIE["city"]) && $_COOKIE["city"] != "Все города России") $city = $_COOKIE["city"];
			else if (!empty($_COOKIE["region"]) && $_COOKIE["region"] != "Все города России") $region = $_COOKIE["region"];

			if (!empty($city) && $city!="Выберите регион") {
				$query_city = "SELECT id FROM cities WHERE name_city='$city'";
				$result_city = mysqli_query($link, $query_city);
				$idCity = mysqli_fetch_assoc($result_city);
				$inquiry = $inquiry . " AND city=$idCity[id]";
			} else 
				if (!empty($region)) {
					$query_region = "SELECT id FROM regions WHERE name_region='$region'";
					$result_region = mysqli_query($link, $query_region);
					$idRegion = mysqli_fetch_assoc($result_region);
					$inquiry = $inquiry . " AND region=$idRegion[id]";
				}
		}

		$query_count = "SELECT COUNT(*) FROM announcements" . $inquiry;
		// Отправляем запрос для получения количества элементов
		$result_count = mysqli_query($link, $query_count);
		// Получаем результат
		$count_pages = mysqli_fetch_array($result_count)[0];
		// Вычисляем количество страниц
		$total_pages = ceil($count_pages / $size_page);
		if (($total_pages != 0) && ($total_pages < $page) || (isset($_POST["accept"]))) header("Location: index.php?page=1" . $link_filter);

		$inquiry = $inquiry . " ORDER BY id DESC LIMIT $offset, $size_page";
		$queryAd = $query . $inquiry;
		$result = mysqli_query($link, $queryAd);
		?>
		<div class="main" id="main">
			<div class="popup">
				<span class="popuptext" id="myPopup">Жалоба отправлена</span>
			</div>
			<?
			if (mysqli_num_rows($result) > 0) {
				while ($ad = mysqli_fetch_assoc($result)) {
					$query_model = "SELECT * FROM carmodels WHERE id=$ad[idModel]";
					$result_model = mysqli_query($link, $query_model);
					$model = mysqli_fetch_assoc($result_model);

					$query_brand = "SELECT * FROM carbrands WHERE id=$model[idBrand]";
					$result_brand = mysqli_query($link, $query_brand);
					$brand = mysqli_fetch_assoc($result_brand);

					$query_user = "SELECT idAccessRight FROM users WHERE token='$_SESSION[Token]'";
					$result_user = mysqli_query($link, $query_user);
					$user = mysqli_fetch_assoc($result_user);

					$query_image = "SELECT image FROM image WHERE idAnnouncements=$ad[id]";
					$result_image = mysqli_query($link, $query_image);
					$image = mysqli_fetch_assoc($result_image);
			?>
					<div class="car_card">
						<div class="car_img" onclick="window.location='cartCar.php?id=<? echo $ad['id']; ?>'"
							 style='background-image: url(data:image/jpeg;base64,<? echo base64_encode($image["image"]); ?>);'>
						</div>
						<div onclick="window.location='cartCar.php?id=<? echo $ad['id']; ?>'" class="description">
							<a href="cartCar.php?id=<? echo $ad['id']; ?>" class="brand"><? echo $brand["brand"] . "  " . $model["model"]; ?></a>
							<p class="description_avto"><? for ($i = 0; $i < 230; $i++) echo $ad["description"][$i];
														if (strlen($ad["description"]) > 230) echo "..."; ?></p>
							<span class="date"><? echo $ad["date"]; ?></span>
						</div>
						<div class="price_info">
							<span onclick="window.location='cartCar.php?id=<? echo $ad['id']; ?>'" class="price"><?
								for($i=strlen($ad["price"])-1;$i>=0;$i--)
								{
									$price=$price.$ad["price"][$i];
								}
								for($i=0;$i<strlen($price);$i++)
								{
									if(($i % 3==0)) $price2=$price2." ";
									$price2=$price2.$price[$i];
								}
								$price="";
								for($i=strlen($price2)-1;$i>=0;$i--)
								{
									$price=$price.$price2[$i];
								}

							echo $price; 
							$price="";
							$price2="";	
							?></span>
							<? if((!empty($_SESSION["Token"])) && ($user["idAccessRight"] != 2)) { ?> 
							<div class="dropdown">
								<button onmouseout="menuOut(<? echo $ad['id']; ?>)" onmouseover="menu(<? echo $ad['id']; ?>)" class="dropbtn fas fa-ellipsis-v">
									<div id="myDropdown<? echo $ad["id"]; ?>" class="dropdown-content">
										<a href="#" data-bs-toggle="modal" onclick="getIdAd(<? echo $ad['id']; ?>)" data-bs-target="#reportModal">Пожаловаться</a>
										<input type="hidden" id="report<? echo $ad["id"]; ?>" name="report" value="<? echo $ad["id"]; ?>">
									</div>
								</button>
							</div>
							<? } ?>
						</div>
					</div>
				<?
				} ?>

				<!-- Пагинация -->
				<ul class="pagination">
					<li class="list-group-item"><a href="?page=1<? echo $link_filter; ?>">Первая страница</a></li>
					<li class="list-group-item <?php if ($page <= 1) {
													echo ' disabled';
												} ?>">
						<a href="<?php if ($page <= 1) {
										echo '#';
									} else {
										echo "?page=" . ($page - 1);
										echo $link_filter;
									} ?>">Назад</a>
					</li>
					<li class="list-group-item <?php if ($page >= $total_pages) {
													echo ' disabled';
												} ?>">
						<a href="<?php if ($page >= $total_pages) {
										echo '#';
									} else {
										echo "?page=" . ($page + 1);
										echo $link_filter;
									} ?>">Вперёд</a>
					</li>
					<li class="list-group-item"><a href="?page=<?php echo $total_pages;
																echo $link_filter; ?>">Последняя страница</a></li>
				</ul>
				<!-- Пагинация -->

			<? } else {
			?>
				<div class="notFound">
					<p>Нет результатов</p>
				</div>
			<? } ?>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModallLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<form method="post" name="reportForm" id="reportForm">
						<div class="modal-header">
							<h5 class="modal-title" id="reportModallLabel">Это объявление некорректно?</h5>
							<button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
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
							<textarea name="description" id="description" placeholder="Опишите подробнее" cols="50" rows="5"></textarea>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
							<button type="button" name="complaint" onclick="send_report(), popUp()" class="btn btn-primary">Отправить</button>
						</div>
				</div>
				</form>
			</div>
		</div>
		<!-- Modal -->
	</main>
	<script>
		function openNav() {
			document.getElementById("sidenav").style.width = "250px";
			document.getElementById("sidenav").style.height = "1470px";
			document.getElementById("main").style.marginLeft = "50px";
			document.getElementById("main").style.gridTemplateColumns="23% 77%";
			document.getElementById("sidenav").style.padding = "10px";
			document.getElementById("NavMenu").setAttribute('onclick','closeNav()');
		}

		function closeNav() {
			document.getElementById("sidenav").style.width = "0";
			document.getElementById("main").style.marginLeft= "0";
			document.getElementById("main").style.gridTemplateColumns="0% 100%";
			document.getElementById("sidenav").style.padding = "0px";
			document.getElementById("sidenav").style.height = "0";
			document.getElementById("NavMenu").setAttribute('onclick','openNav()');
		}

		window.onresize = function () {
			var inner_width = $(window).innerWidth();
			if(inner_width>=1146)
			{
				document.getElementById("sidenav").style=null;
			}
		};

		$(document).ready(function() {
			var body = $("body");
			body.fadeIn(200);
			$(document).on("click", "a:not([href^='#']):not([href^='tel']):not([href^='mailto'])", function(e) {
				e.preventDefault();
				$("body").fadeOut(200);
				var self = this;
				setTimeout(function() {
					window.location.href = $(self).attr("href");
				}, 200);
			});
		});

		function popUp() {
			var popup = document.getElementById("myPopup");
			popup.classList.toggle("show");
			setTimeout(popUpClose, 4000);
		}

		function popUpClose() {
			var popup = document.getElementById("myPopup");
			popup.classList.toggle("close");
			setTimeout(() => popup.classList.remove("show"), 650);
			setTimeout(() => popup.classList.remove("close"), 700);
		}

		function menu(id) {
			var dropdown;
			dropdown = "myDropdown" + id;
			document.getElementById(dropdown).classList.toggle('show');
		}

		function menuOut(id) {
			var dropdown;
			dropdown = "myDropdown" + id;
			document.getElementById(dropdown).classList.remove('show');

		}
		window.onclick = function(event) {
			if (!event.target.matches('.dropbtn')) {
				var dropdowns = document.getElementsByClassName("dropdown-content");
				var i;
				for (i = 0; i < dropdowns.length; i++) {
					var openDropdown = dropdowns[i];
					if (openDropdown.classList.contains('show')) {
						openDropdown.classList.remove('show');
					}
				}
			}
		}

		function getIdAd(id) {
			idAd = id;
		}

		// Отправка жалобы
		function send_report() {
			var servResponse = document.querySelector('#MyForm');

			var couse = document.getElementById("selectCause").value;
			var description = document.getElementById("description").value;
			var report = idAd;

			if ((couse == "Выберите причину") || ((couse == "Другая причина") && (description == ""))) {
				if (couse == "Выберите причину") {
					var styleCouse = document.getElementById("selectCause");
					styleCouse.classList.toggle("failCouse");
					setTimeout(() => styleCouse.classList.remove("failCouse"), 1500);
				}
				if (couse == "Другая причина") {
					var styleDescription = document.getElementById("description");
					styleDescription.classList.toggle("failCouse");
					setTimeout(() => styleDescription.classList.remove("failCouse"), 1500);
				}
			} else {

				document.forms.reportForm.onsubmit = function(e) {
					e.preventDefault();
				}

				var xhr = new XMLHttpRequest();

				xhr.open('POST', 'sendReport.php');
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {}
				}
				xhr.send(`couse=${couse}` + `&description=${description}` + `&report=${report}`);

				var select = document.querySelector('#selectCause').getElementsByTagName('option');
				select[0].selected = true;

				document.getElementById("description").value = "";

				closeModal.click();
			}

		}
	</script>
	<footer width="100%">
		<section class="footer">
			<div class="container">
				<div class="row">
					<div class="col-md-3 col-6">
						<h4>Информация</h4>
						<ul class="list-unstyled">
							<li>Тел: 8(908)921-51-45</li>
							<li>Email: 3gt1@mail.ru</li>
						</ul>
					</div>

					<div class="col-md-3 col-6">
						<h4>Время работы</h4>
						<ul class="list-unstyled">
							<li>г. Екатеринбург, ул. Иванова, 10</li>
							<li>пн-пт: 9:00 - 18:00</li>
						</ul>
					</div>

					<div class="col-md-3 col-6">
						<h4>Ищите нас тут</h4>
						<div class="footer-icons">
							<a href="#"><i class="fab fa-vk"></i></a>
							<a href="#"><i class="fab fa-youtube"></i></a>
							<a href="#"><i class="fab fa-instagram"></i></a>
						</div>
					</div>
				</div>
			</div>
		</section>
	</footer>
</body>

</html>

<? ob_end_flush(); ?>