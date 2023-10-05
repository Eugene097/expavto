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
    <link rel="stylesheet" href="models.css">
    <title>Модели автомобилей</title>

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
            <a href="brands.php">Марки автомобилей</a>
            <a href="models.php" style="background-color: rgb(232, 232, 232);">Модели автомобилей</a>
        </div>
        <div id="snackbar"></div>
        <form method="post" class="form_models" name="createModel" id="createModel">
            <h2>Создание модели</h2>
            <div class="entryField input-group">
                <select name="options" class="btn custom-select" id="inputGroupSelect01" onchange="selectReady()">
                    <option selected disabled>Марка</option>
                    <?
                    $query_brand = "SELECT * FROM carbrands";
                    $result_brand = mysqli_query($link, $query_brand);
                    while ($brands = mysqli_fetch_assoc($result_brand)) {
                        echo "<option value='$brands[id]'>$brands[brand]</option>";
                    }
                    ?>
                </select>
                <input type="text" class="form-control" required disabled name="text_createModel" id="text_createModel" placeholder="Название модели">
                <div class="input-group-append">
                    <button type="button" onclick="addModel()" id="button_createModel" name="button_createModel" class="btn btn-outline-secondary">Создать</button>
                </div>
            </div>

            <div>
                <h2>Модели автомобиля</h2>
                <label for="selece_getModel">Выберите марку для показа моделей</label>
                <select class="btn custom-select" name="select_getModel" id="select_getModel" onchange="brandReady()">
                    <option selected disabled>Выберите марку</option>
                    <?
                    $query_brand = "SELECT * FROM carbrands";
                    $result_brand = mysqli_query($link, $query_brand);
                    while ($brands = mysqli_fetch_assoc($result_brand)) {
                        if ($_POST["select_getModel"] == $brands["id"]) echo "<option selected value='$brands[id]'>$brands[brand]</option>";
                        else echo "<option value='$brands[id]'>$brands[brand]</option>";
                    }
                    ?>
                </select>
                <input style="display: none;" type="submit" disabled class="btn btn-outline-secondary" name="submit_getModel" id="submit_getModel">
            </div>
            <div id="div_getModel">
                <?
                if (isset($_POST["submit_getModel"])) { ?>

                    <table class="models">
                        <thead>
                            <th>№</th>
                            <th>Модель</th>
                            <th>Количество объявлений</th>
                            <th>Опубликованные объявления</th>
                            <th>Неопубликованные объявления</th>
                            <th>Действия</th>
                        </thead>
                        <tbody id="models_tbody">
                            <?
                            $query_model = "SELECT id, model FROM carmodels WHERE idBrand=$_POST[select_getModel]";
                            $result_model = mysqli_query($link, $query_model);
                            while ($models = mysqli_fetch_assoc($result_model)) {
                                $query_countAd = "SELECT COUNT(id) FROM announcements WHERE idModel=$models[id]";
                                $result_countAd = mysqli_query($link, $query_countAd);
                                $countAd = mysqli_fetch_array($result_countAd);

                                $query_countAdNonBlock = "SELECT COUNT(id) FROM announcements WHERE idModel=$models[id] AND block=0";
                                $result_countAdNonBlock = mysqli_query($link, $query_countAdNonBlock);
                                $countAdNonBlock = mysqli_fetch_array($result_countAdNonBlock);

                                $query_countAdBlock = "SELECT COUNT(id) FROM announcements WHERE idModel=$models[id] AND block=1";
                                $result_countAdBlock = mysqli_query($link, $query_countAdBlock);
                                $countAdBlock = mysqli_fetch_array($result_countAdBlock);
                            ?>
                                <tr id="<? echo $models["id"] ?>">
                                    <td id="idModel<? echo $models["id"] ?>"><? echo $models["id"] ?></td>
                                    <td id="nameModel<? echo $models["id"] ?>"><? echo $models["model"] ?></td>
                                    <td><? echo $countAd[0] ?></td>
                                    <td><? echo $countAdNonBlock[0] ?></td>
                                    <td><? echo $countAdBlock[0] ?></td>
                                    <td id="action">
                                        <div class="dropdown">
                                            <button class="dropbtn">
                                                <svg width="3em" height="1.5em" viewBox="0 0 16 16" class="bi bi-caret-down-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                                </svg>
                                            </button>
                                            <div class="dropdown-content">
                                                <a href="" data-bs-toggle="modal" onclick="changeModelForDelete(<? echo $models['id'] ?>)" data-bs-target="#deleteModelModal">Удалить</a>
                                                <a href="" data-bs-toggle="modal" onclick="changeModelForEdit(<? echo $models['id'] ?>)" data-bs-target="#editModelModal">Редактировать</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?
                            } ?>
                        </tbody>
                    </table>
                <?
                }
                ?>
            </div>
        </form>
        <!-- Модальное окно удаления -->
        <div class="modal fade" id="deleteModelModal" tabindex="-1" aria-labelledby="deleteModelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" name="formDeleteModel" id="formDeleteModel">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModelModalLabel">Подтверждение удаления модели</h5>
                            <button type="button" id="closeModalDeleteModel" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <input type="hidden" name="idModelForDelete" id="idModelForDelete">
                        <div class="modal-body">
                            <p>Удалить модель автомобиля: <span id="deleteModelName"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button name="button_deleteModel" onclick="deleteModel()" class="btn btn-primary">Удалить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Модальное окно удаления -->
        <!-- Модальное окно редактирования -->
        <div class="modal fade" id="editModelModal" tabindex="-1" aria-labelledby="editModelModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" name="formEditModel" id="formEditModel">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModelModalLabel">Изменение модели</h5>
                            <button type="button" id="closeModalEditModel" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <input type="hidden" name="idModelForEdit" id="idModelForEdit">
                        <div class="modal-body">
                            <p>Редактировать модель автомобиля: <span id="editModelName"></span></p>
                            <input type="text" name="newModel" id="newModel" placeholder="Новое название модели">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button name="button_editModel" onclick="editModel()" class="btn btn-primary">Изменить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Модальное окно редактирования -->
    </main>
    <script>
        function selectReady() {
            document.getElementById("text_createModel").disabled = 0;
        }

        function brandReady() {

            document.getElementById("text_createModel").disabled = 1;
            document.getElementById("submit_getModel").disabled = 0;
            document.getElementById("submit_getModel").click();
        }

        function addModel() {
            var servResponse = document.querySelector('#text_createModel');

            var model = document.getElementById("text_createModel").value;

            if (model == "") {
                alert("Введите название модели");
                return;
            }
            var brand = document.getElementById("inputGroupSelect01").value;

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'createModel.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    servResponse.textContent = xhr.responseText;
                    var text = servResponse.textContent;
                    var lastId = "",
                        bool = "",
                        znak;

                    for (i = 0; i < text.length - 1; i++) {
                        if (text[i] == ",") znak = true;
                        if (znak) lastId = lastId + text[i + 1];
                        else bool = bool + text[i];
                    }

                    if (Boolean(bool)) {
                        document.getElementById("snackbar").innerText = "Модель автомобиля создана";
                        var x = document.getElementById("snackbar");
                        x.className = "show";
                        setTimeout(function() {
                            x.className = x.className.replace("show", "");
                        }, 3000);

                        document.getElementById("text_createModel").value = "";

                        if (brand == document.getElementById("select_getModel").value) {
                            var tbody = document.getElementsByTagName("tbody")[0];
                            var row = document.createElement("tr");
                            row.id = lastId;
                            var td1 = document.createElement("td");
                            td1.appendChild(document.createTextNode(lastId));
                            var td2 = document.createElement("td");
                            td2.id = "nameModel" + lastId;
                            td2.appendChild(document.createTextNode(model));
                            row.appendChild(td1);
                            row.appendChild(td2);
                            for (i = 0; i < 3; i++) {
                                var td = document.createElement("td");
                                td.appendChild(document.createTextNode("0"));
                                row.appendChild(td);
                            }
                            var clonedNode = document.getElementById("action").cloneNode(true);
                            row.appendChild(clonedNode);
                            tbody.appendChild(row);
                            clonedNode.children[0].children[1].children[0].setAttribute('onclick', 'changeModelForDelete(' + lastId + ')');
                            clonedNode.children[0].children[1].children[1].setAttribute('onclick', 'changeModelForEdit(' + lastId + ')');
                            document.getElementById("text_createModel").disabled = 1;
                        }
                    } else {
                        document.getElementById("snackbar").innerText = "Такая модель автомобиля уже существует";
                        var x = document.getElementById("snackbar");
                        x.className = "showError";
                        setTimeout(function() {
                            x.className = x.className.replace("showError", "");
                        }, 3000);
                    }

                }
            }
            xhr.send(`model=${model}` + `&brand=${brand}`);
        }

        /* Удаление модели */
        function changeModelForDelete(id) {
            document.getElementById("idModelForDelete").value = id;
            document.getElementById("deleteModelName").innerText = document.getElementById("nameModel" + id).innerText;
        }

        function deleteModel() {
            var servResponse = document.querySelector('#idModelForDelete');

            var idModel = document.getElementById("idModelForDelete").value;

            document.forms.formDeleteModel.onsubmit = function(e) {
                e.preventDefault();
            }

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'deleteModel.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("snackbar").innerText = "Модель удалена";
                    var x = document.getElementById("snackbar");
                    x.className = "show";
                    setTimeout(function() {
                        x.className = x.className.replace("show", "");
                    }, 3000);

                    document.getElementById(idModel).closest("tr").remove();
                }
            }
            xhr.send(`idModel=` + idModel);
            closeModalDeleteModel.click();
        }
        /* Удаление модели */
        /* Редактирование марки */
        function changeModelForEdit(id) {
            document.getElementById("idModelForEdit").value = id;
            document.getElementById("editModelName").innerText = document.getElementById("nameModel" + id).innerText;
        }

        function editModel() {
            var servResponse = document.querySelector('#idModelForEdit');

            var idModel = document.getElementById("idModelForEdit").value;
            var model = document.getElementById("newModel").value;

            document.forms.formEditModel.onsubmit = function(e) {
                e.preventDefault();
            }

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'editModel.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    servResponse.textContent = xhr.responseText;
                    var text = servResponse.textContent;

                    if (Boolean(text)) {
                        document.getElementById("snackbar").innerText = "Модель изменена";
                        var x = document.getElementById("snackbar");
                        x.className = "show";
                        setTimeout(function() {
                            x.className = x.className.replace("show", "");
                        }, 3000);

                        document.getElementById("nameModel" + idModel).innerText = model;
                        document.getElementById("newModel").value = "";
                    } else {
                        document.getElementById("snackbar").innerText = "Произошла ошибка";
                        var x = document.getElementById("snackbar");
                        x.className = "showError";
                        setTimeout(function() {
                            x.className = x.className.replace("showError", "");
                        }, 3000);
                    }

                }
            }
            xhr.send(`idModel=${idModel}` + `&model=${model}`);
            closeModalEditModel.click();
        }
        /* Редактирование марки */
    </script>
</body>

</html>