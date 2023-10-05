<?php
ob_start();
session_start();
include_once("active.php");
include_once("link.php");
include_once("examination.php");
include_once("adminCheck.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="admin.css">
    <title>Админ. панель</title>

    <!-- Bootstrap CSS (jsDelivr CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

    <!-- Bootstrap Bundle JS (jsDelivr CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">

    <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
</head>

<body>
    <?  include_once("menu.php"); 

		if (isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 1;
		}

		// Назначаем количество данных на одной странице
		$size_page = 7;
		// Вычисляем с какого объекта начать выводить
		$offset = ($page - 1) * $size_page;

        $query_count = "SELECT COUNT(*) FROM users";
		// Отправляем запрос для получения количества элементов
		$result_count = mysqli_query($link, $query_count);
		// Получаем результат
		$count_pages = mysqli_fetch_array($result_count)[0];
		// Вычисляем количество страниц
		$total_pages = ceil($count_pages / $size_page);

        if($_GET["order"]=="Право доступа") 
        {
            $inquiry=" ORDER BY idAccessRight";
            $order_link="&order=Право доступа";
        }
        if($_GET["orderDesc"]=="Право доступа")
        {
            $inquiry=" ORDER BY idAccessRight DESC";
            $order_link="&orderDesc=Право доступа";
        } 
    ?>
    <main>
        <div id="snackbar"></div>
        <div class="menuAdmin">
            <a href="admin.php" style="background-color: rgb(232, 232, 232);">Пользователи</a>
            <a href="searchUser.php">Поиск пользователей</a>
            <a href="brands.php">Марки автомобилей</a>
            <a href="models.php">Модели автомобилей</a>
        </div>
        <table class="users">
            <thead>
                <th>№</th>
                <th><a href="admin.php?page=<? echo $page; 
                    if(empty($_GET["order"]) && empty($_GET["orderDesc"])) { ?>&order=Право доступа <? } 
                    if($_GET["order"]=="Право доступа") { ?>&orderDesc=Право доступа<? } 
                    if($_GET["orderDesc"]=="Право доступа") { ?>&order=Право доступа<? } ?>">Право доступа</a></th>
                <th>ФИО</th>
                <th>Дата рождения</th>
                <th>Номер телефона</th>
                <th>Количество объявлений</th>
                <th>Действия</th>
            </thead>
            <tbody>
                <?
                    $query_users = "SELECT * FROM users". $inquiry ." LIMIT $offset, $size_page";
                    $result_users = mysqli_query($link, $query_users);
                    while ($users = mysqli_fetch_assoc($result_users)) {
                        $query_accessRight = "SELECT title FROM accessright WHERE id=$users[idAccessRight]";
                        $result_accessRight = mysqli_query($link, $query_accessRight);
                        $accessRight = mysqli_fetch_assoc($result_accessRight);

                        $query_countAd="SELECT COUNT(*) FROM announcements WHERE idUser=$users[id]";
                        $result_countAd = mysqli_query($link, $query_countAd);
                        $countAd = mysqli_fetch_array($result_countAd);
                ?>
                    <tr id="<? echo $users["id"] ?>">
                        <td><? echo $users["id"] ?></td>
                        <td id="accessRight<? echo $users["id"] ?>"><? echo $accessRight["title"] ?></td>
                        <td id="FIO<? echo $users["id"] ?>"><? echo $users["surname"] . " " . $users["name"] . " " . $users["patronymic"] ?></td>
                        <td><? echo $users["dateOfBirthday"] ?></td>
                        <td><? echo $users["phone"] ?></td>
                        <td><? echo $countAd[0] ?></td>
                        <td>
                            <? if ($users["token"] == $_SESSION["Token"]) { ?>
                                <svg width="3em" height="1.5em" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708-.708l7-7a.5.5 0 0 1 .708 0z" />
                                    <path fill-rule="evenodd" d="M4.146 4.146a.5.5 0 0 0 0 .708l7 7a.5.5 0 0 0 .708-.708l-7-7a.5.5 0 0 0-.708 0z" />
                                </svg>
                            <? } else { ?>
                                <div class="dropdown">
                                    <button class="dropbtn">
                                        <svg width="3em" height="1.5em" viewBox="0 0 16 16" class="bi bi-caret-down-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg>
                                    </button>
                                    <div class="dropdown-content">
                                        <a href="" data-bs-toggle="modal" onclick="changeUserForDelete(<? echo $users['id'] ?>)" data-bs-target="#deleteUserModal">Удалить</a>
                                        <a href="" data-bs-toggle="modal" onclick="changeUserForBlock(<? echo $users['id'] ?>)" data-bs-target="#blockUserModal">Заблокировать</a>
                                        <a href="" data-bs-toggle="modal" onclick="changeUserForAccessRight(<? echo $users['id'] ?>)" data-bs-target="#changeAccessRightUserModal">Изменить право доступа</a>
                                    </div>
                                </div>
                            <? } ?>
                        </td>
                    </tr>
                <? } ?>
            </tbody>
        </table>
        <!-- Пагинация -->
				<ul class="pagination">
					<li class="list-group-item"><a href="?page=1<?echo $order_link;?>">Первая страница</a></li>
					<li class="list-group-item <?php if ($page <= 1) {
													echo ' disabled';
												} ?>">
						<a href="<?php if ($page <= 1) {
										echo '#';
									} else {
										echo "?page=" . ($page - 1);
									} ?><?echo $order_link;?>">Назад</a>
					</li>
					<li class="list-group-item <?php if ($page >= $total_pages) {
													echo ' disabled';
												} ?>">
						<a href="<?php if ($page >= $total_pages) {
										echo '#';
									} else {
										echo "?page=" . ($page + 1);
									} ?><?echo $order_link;?>">Вперёд</a>
					</li>
					<li class="list-group-item"><a href="?page=<?php echo $total_pages;
																?><?echo $order_link;?>">Последняя страница</a></li>
				</ul>
				<!-- Пагинация -->
    </main>
    <!-- Модальное окно удаления -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" name="formDeleteUser" id="formDeleteUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Подтверждение удаления пользователя</h5>
                        <button type="button" id="closeModalDeleteUser" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="idUserForDelete" id="idUserForDelete">
                    <div class="modal-body">
                        <p>Удалить пользователя: <span id="deleteUserFIO"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button name="button_deleteUser" onclick="deleteUser()" class="btn btn-primary">Удалить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Модальное окно удаления -->

    <!-- Модальное окно блокировки -->
    <div class="modal fade" id="blockUserModal" tabindex="-1" aria-labelledby="blockUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" name="formBlockUser" id="formBlockUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="blockUserModalLabel">Подтверждение блокировки пользователя</h5>
                        <button type="button" id="closeModalBlockUser" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="idUserForBlock" id="idUserForBlock">
                    <div class="modal-body">
                        <p>Заблокировать пользователя: <span id="blockUserFIO"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button name="button_blockUser" onclick="blockUser()" class="btn btn-primary">Заблокировать</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Модальное окно блокировки -->

    <!-- Модальное окно смены права пользователя -->
    <div class="modal fade" id="changeAccessRightUserModal" tabindex="-1" aria-labelledby="changeAccessRightUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" name="formChangeAccessRightUser" id="formChangeAccessRightUser">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeAccessRightUserModalLabel">Подтверждение блокировки пользователя</h5>
                        <button type="button" id="closeModalChangeAccessRightUser" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="idUserForChangeAccessRight" id="idUserForChangeAccessRight">
                    <div class="modal-body">
                        <p>Пользователя: <span id="changeAccessRightUserFIO"></span></p>
                        <p>Текущее право доступа у пользователя: <span id="accessRight"></span></p>
                        <select id="select_accessRight"></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button name="button_changeAccessRight" onclick="changeAccessRightUser()" class="btn btn-primary">Сменить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Модальное окно смены права пользователя -->

    <script>
        /* Удаление пользователя */
        function changeUserForDelete(id) {
            document.getElementById("idUserForDelete").value = id;
            document.getElementById("deleteUserFIO").innerText = document.getElementById("FIO" + id).innerText;
        }

        function deleteUser() {
            var servResponse = document.querySelector('#idUserForDelete');

            var idUser = document.getElementById("idUserForDelete").value;

            document.forms.formDeleteUser.onsubmit = function(e) {
                e.preventDefault();
            }

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'deleteUser.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("snackbar").innerText="Пользователь удалён";
                    var x = document.getElementById("snackbar");
                    x.className = "show";
                    setTimeout(function() {
                        x.className = x.className.replace("show", "");
                    }, 3000);

                    document.getElementById(idUser).closest("tr").remove();
                }
            }
            xhr.send(`idUser=` + idUser);
            closeModalDeleteUser.click();
        }
        /* Удаление пользователя */

        /* Блокировка пользователя */
        function changeUserForBlock(id) {
            document.getElementById("idUserForBlock").value = id; //Запоминание id пользователя
            document.getElementById("blockUserFIO").innerText = document.getElementById("FIO" + id).innerText;
        }

        function blockUser() {
            var servResponse = document.querySelector('#idUserForBlock'); 

            var idUser = document.getElementById("idUserForBlock").value; //Запоминание id пользователя

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
                    document.getElementById("accessRight"+idUser).innerText="Заблокированный пользователь";
                }
            }
            xhr.send(`idUser=` + idUser); //Отправка данных в blockUser.php
            closeModalBlockUser.click(); //Закрытие модального окна
        }
        /* Блокировка пользователя */

        /* Смена право доступа у пользователя */
        function changeUserForAccessRight(id) {
            document.getElementById('select_accessRight').innerHTML='';
            document.getElementById("idUserForChangeAccessRight").value = id;
            document.getElementById("changeAccessRightUserFIO").innerText = document.getElementById("FIO" + id).innerText;
            document.getElementById("accessRight").innerText=document.getElementById("accessRight"+id).innerText

            let currentAccessRight=document.getElementById("accessRight"+id).innerText;
			let newOption = new Option(document.getElementById("accessRight"+id).innerText, document.getElementById("accessRight"+id).innerText);
			document.getElementById("select_accessRight").append(newOption);
			newOption.selected = true;
			newOption.disabled = true;
            let accessRight=["Пользователь", "Модератор", "Администратор"]

			for (i = 0; i < accessRight.length; i++) {
				if (accessRight[i]!=currentAccessRight) {
					let newOption = new Option(accessRight[i], accessRight[i]);
					document.getElementById("select_accessRight").append(newOption);
				}
			}
        }

        function changeAccessRightUser()
        {
            var servResponse = document.querySelector('#idUserForChangeAccessRight');

            var idUser = document.getElementById("idUserForChangeAccessRight").value;
            var accessRight=document.getElementById("select_accessRight").value;

            document.forms.formChangeAccessRightUser.onsubmit = function(e) {
                e.preventDefault();
            }

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'changeAccessRightUser.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("snackbar").innerText="Изменено право доступа";
                    var x = document.getElementById("snackbar");
                    x.className = "show";
                    setTimeout(function() {
                        x.className = x.className.replace("show", "");
                    }, 3000);

                    document.getElementById("accessRight"+idUser).innerText=accessRight;
                    document.getElementById('select_accessRight').innerHTML='';
                }
            }
            xhr.send(`accessRight=${accessRight}` + `&idUser=${idUser}`);
            closeModalChangeAccessRightUser.click();
        }
        /* Смена право доступа у пользователя */
    </script>
</body>

</html>