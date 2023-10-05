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
    <link rel="stylesheet" href="brands.css">
    <title>Марки автомобилей</title>

    <!-- Bootstrap CSS (jsDelivr CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

    <!-- Bootstrap Bundle JS (jsDelivr CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">

    <script src="https://yastatic.net/jquery/3.3.1/jquery.min.js"></script>

    <link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
</head>

<body>
    <? include_once("menu.php"); ?>
    <main>
        <div class="menuAdmin">
            <a href="admin.php">Пользователи</a>
            <a href="searchUser.php">Поиск пользователей</a>
            <a href="brands.php" style="background-color: rgb(232, 232, 232);">Марки автомобилей</a>
            <a href="models.php">Модели автомобилей</a>
        </div>
        <div id="snackbar"></div>
        <form method="post" name="createBrand" id="createBrand">
            <h2>Создание марки</h2>
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="text_createBrand" placeholder="Название бренда" aria-label="Название бренда" aria-describedby="button-addon2">
                <div class="input-group-append">
                    <input type="button" class="btn btn-outline-secondary" onclick="addBrand()" name="button_createBrand" id="button_createBrand" value="Создать бренд">
                </div>
            </div>
        </form>
        <form method="post" class="form_brands" name="form_brands" id="form_brands">
            <h2>Марки</h2>
            <div id="div_brands">
            <table class="brands" id="table_brands">
                <thead>
                    <th>№</th>
                    <th>Марка</th>
                    <th>Количество моделей марки</th>
                    <th>Количество объявлений марки</th>
                    <th>Действия</th>
                </thead>
                <tbody>
                    <?
                    $query_brand = "SELECT * FROM carbrands ORDER BY brand";
                    $result_brand = mysqli_query($link, $query_brand);
                    while ($brands = mysqli_fetch_assoc($result_brand)) {
                        $query_countModel = "SELECT COUNT(*) FROM carmodels WHERE idBrand=$brands[id]";
                        $result_countModel = mysqli_query($link, $query_countModel);
                        $countModel = mysqli_fetch_array($result_countModel);

                        $query_countAd = "SELECT COUNT(*) FROM announcements WHERE idBrand=$brands[id]";
                        $result_countAd = mysqli_query($link, $query_countAd);
                        $countAd = mysqli_fetch_array($result_countAd);
                    ?>
                        <tr id="<? echo $brands["id"] ?>">
                            <td><? echo $brands["id"] ?></td>
                            <td id="nameBrand<? echo $brands["id"] ?>"><? echo $brands["brand"] ?></td>
                            <td><? echo $countModel[0] ?></td>
                            <td><? echo $countAd[0] ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="dropbtn">
                                        <svg width="3em" height="1.5em" viewBox="0 0 16 16" class="bi bi-caret-down-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg>
                                    </button>
                                    <div class="dropdown-content">
                                        <a href="" data-bs-toggle="modal" onclick="changeBrandForDelete(<? echo $brands['id'] ?>)" data-bs-target="#deleteBrandModal">Удалить</a>
                                        <a href="" data-bs-toggle="modal" onclick="changeBrandForEdit(<? echo $brands['id'] ?>)" data-bs-target="#editBrandModal">Редактировать</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?
                    }
                    ?>
                </tbody>
            </table>
            </div>
        </form>
        <!-- Модальное окно удаления -->
        <div class="modal fade" id="deleteBrandModal" tabindex="-1" aria-labelledby="deleteBrandModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" name="formDeleteBrand" id="formDeleteBrand">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteBrandModalLabel">Подтверждение удаления марки</h5>
                            <button type="button" id="closeModalDeleteBrand" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <input type="hidden" name="idBrandForDelete" id="idBrandForDelete">
                        <div class="modal-body">
                            <p>Удалить марку автомобиля: <span id="deleteBrandName"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button name="button_deleteBrand" onclick="deleteBrand()" class="btn btn-primary">Удалить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Модальное окно удаления -->
        <!-- Модальное окно редактирования -->
        <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" name="formEditBrand" id="formEditBrand">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBrandModalLabel">Изменение марки</h5>
                            <button type="button" id="closeModalEditBrand" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <input type="hidden" name="idBrandForEdit" id="idBrandForEdit">
                        <div class="modal-body">
                            <p>Редактировать марку автомобиля: <span id="editBrandName"></span></p>
                            <input type="text" name="newBrand" id="newBrand" placeholder="Новое название марки">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button name="button_editBrand" onclick="editBrand()" class="btn btn-primary">Изменить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Модальное окно редактирования -->
    </main>
    <script>
        function addBrand() {
            var servResponse = document.querySelector('#text_createBrand');

            var brand = document.getElementById("text_createBrand").value;

            document.forms.form_brands.onsubmit = function(e) {
                e.preventDefault();
            }

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'createBrand.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    servResponse.textContent = xhr.responseText;
                    var text = servResponse.textContent;

                    if (Boolean(text)) {
                        document.getElementById("snackbar").innerText = "Марка автомобиля создана";
                        var x = document.getElementById("snackbar");
                        x.className = "show";
                        setTimeout(function() {
                            x.className = x.className.replace("show", "");
                        }, 3000);

                        document.getElementById("text_createBrand").value = "";

                        $("#div_brands").load("brands.php #div_brands");
                    } 
                    else 
                    {
                        document.getElementById("snackbar").innerText = "Такая марка автомобиля уже существует";
                        var x = document.getElementById("snackbar");
                        x.className = "showError";
                        setTimeout(function() {
                            x.className = x.className.replace("showError", "");
                        }, 3000);
                    }
                    
                }
            }
            xhr.send(`brand=` + brand);
        }

        /* Удаление марки */
        function changeBrandForDelete(id) {
            document.getElementById("idBrandForDelete").value = id;
            document.getElementById("deleteBrandName").innerText = document.getElementById("nameBrand" + id).innerText;
        }

        function deleteBrand() {
            var servResponse = document.querySelector('#idBrandForDelete');

            var idBrand = document.getElementById("idBrandForDelete").value;

            document.forms.formDeleteBrand.onsubmit = function(e) {
                e.preventDefault();
            }

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'deleteBrand.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("snackbar").innerText = "Марка удалена";
                    var x = document.getElementById("snackbar");
                    x.className = "show";
                    setTimeout(function() {
                        x.className = x.className.replace("show", "");
                    }, 3000);

                    document.getElementById(idBrand).closest("tr").remove();
                }
            }
            xhr.send(`idBrand=` + idBrand);
            closeModalDeleteBrand.click();
        }
        /* Удаление марки */
        /* Редактирование марки */
        function changeBrandForEdit(id) {
            document.getElementById("idBrandForEdit").value = id;
            document.getElementById("editBrandName").innerText = document.getElementById("nameBrand" + id).innerText;
        }

        function editBrand() {
            var servResponse = document.querySelector('#idBrandForEdit');

            var idBrand = document.getElementById("idBrandForEdit").value;
            var brand=document.getElementById("newBrand").value;

            document.forms.formEditBrand.onsubmit = function(e) {
                e.preventDefault();
            }

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'editBrand.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    servResponse.textContent = xhr.responseText;
                    var text = servResponse.textContent;
                    
                    if (Boolean(text)) {
                        document.getElementById("snackbar").innerText = "Марка изменена";
                        var x = document.getElementById("snackbar");
                        x.className = "show";
                        setTimeout(function() {
                            x.className = x.className.replace("show", "");
                        }, 3000);

                        document.getElementById("nameBrand"+idBrand).innerText=brand;
                        document.getElementById("newBrand").value="";
                    } 
                    else 
                    {
                        document.getElementById("snackbar").innerText = "Такая марка автомобиля уже существует";
                        var x = document.getElementById("snackbar");
                        x.className = "showError";
                        setTimeout(function() {
                            x.className = x.className.replace("showError", "");
                        }, 3000);
                    }
                }
            }
            xhr.send(`idBrand=${idBrand}` + `&brand=${brand}`);
            closeModalEditBrand.click();
        }
        /* Редактирование марки */
    </script>
</body>

</html>