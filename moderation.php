<?php
	ob_start();
	session_start();
	include_once("active.php");
	include_once("link.php");
	include_once("examination.php");
	include_once("moderCheck.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Модерация</title>
		<link rel="stylesheet" href="complaints.css">
		
		<!-- Bootstrap CSS (jsDelivr CDN) -->
  		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">

  		<!-- Bootstrap Bundle JS (jsDelivr CDN) -->
  		<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
	
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css">

		<link rel="shortcut icon" href="/img/logo.png" type="image/x-icon">
	</head>
	<body>
			<?php 
				include_once("menu.php");
			?>
		<main>
            <div class="main">
            <?
                $query_moderation="SELECT * FROM announcements WHERE block=1 AND reason_for_blocking='Модерация'";
                $result_moderation=mysqli_query($link, $query_moderation);
				$checkEmpty=mysqli_fetch_assoc($result_moderation);
				if(empty($checkEmpty))
				{
					echo "<h2>Объявления на модерацию не найдены</h2>";
				}
				else
				{
					$query_moderation="SELECT * FROM announcements WHERE block=1 AND reason_for_blocking='Модерация'";
                	$result_moderation=mysqli_query($link, $query_moderation);
					while($announcement=mysqli_fetch_assoc($result_moderation))
					{
						$query_model="SELECT * FROM carmodels WHERE id=$announcement[idModel]";
						$result_model=mysqli_query($link, $query_model);
						$model=mysqli_fetch_assoc($result_model);

						$query_brand="SELECT * FROM carbrands WHERE id=$model[idBrand]";
						$result_brand=mysqli_query($link, $query_brand);
						$brand=mysqli_fetch_assoc($result_brand);
						
						$query_image="SELECT image FROM image WHERE idAnnouncements=$announcement[id]";
						$result_image=mysqli_query($link, $query_image);
						$image=mysqli_fetch_assoc($result_image);
					?>
						
							<div class="car_card">
							<? 
								if(!empty($image["image"]))
									echo '<img src="data:image/jpeg;base64,'.base64_encode($image["image"]).'" class="car_img img-fluid">';			
								else echo '<img src="/img/noImage.png" class="car_img img-fluid">';
							?> 
								<div class="description">
									<a href="moderationAd.php?id=<? echo $announcement['id']; ?>" 
									class="brand"><? echo $brand["brand"]."  ".$model["model"]; ?></a>
									<p class="description_avto"><? for($i=0;$i<230;$i++) 
										echo $announcement["description"][$i]; 
										if(strlen($announcement["description"])>230) echo "...";?></p>
									<span class="date"><? echo $announcement["date"]; ?></span>
								</div>
								<div class="price_info">
									<span class="price"><? echo $announcement["price"]; ?></span>
								</div>
							</div>
					<? } ?>
				<? } ?>
            </div>
        </main>
    </body>
</html>

<? ob_end_flush(); ?>