<?
	session_start();
	include_once("link.php");
?>
<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="menu.css">
	<script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
	<header class="sticky-top">
		<div class="logo">
			<span id="NavMenu" style="font-size:30px;cursor:pointer; color: white; display:none;" onclick="openNav()">☰</span>
			<a href="index.php"><img src="img/logo.png" class="img-logo"></a>
		</div>
		<div class="menu">
			<div class="location">
				<a href="#" data-bs-toggle="modal" id="location_link" data-bs-target="#locationModal">
					<?
						if (!empty($_POST["city"])) echo $_POST["city"];
						else if (!empty($_POST["region"])) echo $_POST["region"];
							else if (!empty($_COOKIE["city"])) echo $_COOKIE["city"];
								else if (!empty($_COOKIE["region"])) echo $_COOKIE["region"];
									else echo "Выберите местоположение";
					?>
				</a>
			</div>
			<?php
			if (empty($_SESSION["Token"])) {
			?>
				<div class="button">
					<button class="registration" onclick="window.location='registration.php'">Регистрация</button>
					<button class="entrance" onclick="window.location='login.php'">Вход</button>
				</div>
			<?php
			} else { 
				$query_rightAccess="SELECT idAccessRight FROM users WHERE Token='$_SESSION[Token]'";
				$result=mysqli_query($link, $query_rightAccess);
				$accessRight=mysqli_fetch_assoc($result);
				?>
				<div class="elemnts-dropdown-menu">
					<div class="button dropdown">
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
							<i class="fas fa-bars"></i>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
							<li><button class="dropdown-item" type="button" onclick="window.location='personArea.php'">Личный кабинет</button></li>
							<? if($accessRight["idAccessRight"]!=2) { ?><li><button class="dropdown-item" type="button" onclick="window.location='createAd.php'">Создать объявление</button></li><? } ?>
							<li><button class="dropdown-item" type="button" onclick="window.location='myads.php'">Мои объявления</button></li>
							<? if($accessRight["idAccessRight"]==3 || $accessRight["idAccessRight"]==4) { ?><li><button class="dropdown-item" type="button" onclick="window.location='complaints.php'">Жалобы</button></li><? } ?>
							<? if($accessRight["idAccessRight"]==3 || $accessRight["idAccessRight"]==4) { ?><li><button class="dropdown-item" type="button" onclick="window.location='moderation.php'">Модерация</button></li><? } ?>
							<? if($accessRight["idAccessRight"]==4) { ?><li><button class="dropdown-item" type="button" onclick="window.location='admin.php'">Админ.панель</button></li><? } ?>
							<li><button class="dropdown-item" type="button" onclick="window.location='exit.php'">Выход</button></li>
						</ul>
					</div>
				</div>
			<? }
			?>
		</div>

	</header>

	<!-- Modal -->
	<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form id="modal_form" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="locationModalLabel">Выбор города</h5>
						<button type="button" class="btn-close" id="locationModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div><input type="checkbox" <? if (($_COOKIE["city"] == "Все города России" && $_COOKIE["region"] == "Все города России") || isset($_POST["allCities"])) echo "checked=1"; ?> name="allCities" id="allCities" value="Все города" onchange="location_checkbox()"><label for="allCities">Все города России</label></div>
						<p>Выберите город, в котором хотите посмотреть автомобиль.</p>
						<div id="location_select" class="location_select">
							<div class="location">
								<select name="region_menu" id="region_menu" onchange="select_city_menu()">
									<option selected disabled value="">Выберите регион</option>
									<?
									$query_regions = "SELECT * FROM regions";
									$result_regions = mysqli_query($link, $query_regions);
									while ($regions = mysqli_fetch_assoc($result_regions)) {
										echo "<option value='$regions[name_region]'>" . $regions['name_region'] . "</option>";
									}
									?>
								</select>
								<select name="city_menu" id="city_menu">
									<option selected disabled value="">Выберите город</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
						<button type="button" name="location_menu" onclick="save_location()" class="btn btn-primary">Сохранить</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- Modal -->
	<script>
		function save_location() {
			var servResponse = document.querySelector('#modal_form');
			var region, city;

			if (!document.getElementById("allCities").checked) {
				region = document.getElementById("region_menu").value;
				city = document.getElementById("city_menu").value;
			} else {
				region = "Все города России";
				city = "Все города России";
			}

			var xhr = new XMLHttpRequest();

			xhr.open('POST', 'location.php');
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					servResponse.textContent = xhr.responseText;
					var text = servResponse.textContent;
					if(region=="Все города России" && city == "Все города России")
					{
						document.getElementById("location_link").innerText="Все города России";
					}
					else if(city!="") document.getElementById("location_link").innerText=city;
						else document.getElementById("location_link").innerText=region;
				}
			}
			xhr.send(`region=${region}` + `&city=${city}`);
			locationModalClose.click();
			if(document.location.pathname=="/index.php") accept.click();
			else location.reload();
		}

		function select_city_menu() {
			var servResponse = document.querySelector('#city_menu');

			var region = document.getElementById("region_menu").value;

			var xhr = new XMLHttpRequest();

			xhr.open('POST', 'getCity.php');
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					servResponse.textContent = xhr.responseText;
					var i;
					var text = servResponse.textContent;
					var city = "";
					document.getElementById("city_menu").options.text = "Выберите город";
					let newOption = new Option("Выберите город", "");
					document.getElementById("city_menu").append(newOption);
					newOption.selected = true;
					newOption.disabled = true;

					for (i = 0; i < text.length; i++) {
						if (text[i] == ",") {
							let newOption = new Option(city, city);
							document.getElementById("city_menu").append(newOption);
							city = "";
						} else city = city + text[i];
					}
				}
			}
			xhr.send('region=' + region);
		}

		if (document.getElementById("allCities").checked) {
			document.getElementById("location_select").style.pointerEvents = "none";
			document.getElementById("region_menu").disabled = true;
			document.getElementById("city_menu").disabled = true;
		}

		function location_checkbox() {
			if (document.getElementById("allCities").checked) {
				document.getElementById("location_select").style.pointerEvents = "none";
				document.getElementById("region_menu").disabled = true;
				document.getElementById("city_menu").disabled = true;
			} else {
				document.getElementById("location_select").style.pointerEvents = "visiblePainted";
				document.getElementById("region_menu").disabled = false;
				document.getElementById("city_menu").disabled = false;
			}
		}
	</script>
</body>

</html>