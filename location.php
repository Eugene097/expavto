<?
$region=$_POST["region"];
$city=$_POST["city"];
if ($city=="Все города России" && $region=="Все города России") {
    setcookie("city", "Все города России", strtotime("+60 days"));
    setcookie("region", "Все города России", strtotime("+60 days"));
} else
	if (!empty($city)) {
        setcookie("city", $city, strtotime("+60 days"));
    } else 
        if (!empty($region)) {
            setcookie("region", $region, strtotime("+60 days"));
            setcookie("city", "", strtotime("+60 days"));
        }
?>