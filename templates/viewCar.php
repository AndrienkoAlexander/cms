<?php 
include "templates/include/header.php";

if(isset($_SESSION['user'])) 
	include "templates/include/userHeader.php"; 

if(count($results['cars']) == 0)
{
	echo "Данные отсутствуют!";
	echo '<div id="main_c">';
	echo '<a href=".?action=carsCategories">Категории</a>';
	echo '</div>';
}
else
{
	if(isset($results['totalRows']))
		echo '<a href=".?action=carsCategories">Категории</a>';
	else
		echo '<a href=".?action=viewCategory&categoryId=' . $results['cars'][0]->categoryId . '">Назад</a>';

	echo '<div class="products">';
	foreach ($results['cars'] as $car) {
		$car = (array)$car;
		echo '<div class="cars">';
		echo '<p class="car_name"><a href=".?action=viewCar&carId=' . $car['id'] . '">'. $car['name'] .'</a></p>';
		if(isset($results['images']['totalRows']))
		{
			foreach ($results['images']['results'] as $image => $val) {
				echo '<img src="./images/'. $val->name .'" alt="'. $car['name'] .'">';
			}
		}
		else
		{
			if(isset($results['images'][$car['id']]))
				echo '<img src="./images/'. $results['images'][$car['id']]->name .'" alt="'. $car['name'] .'">';
		}
		echo '<ul>';
		echo '<li>Цена: $'. $car['price'] .'</li>';
		echo '<li>Год: '. $car['year'] .'</li>';
		if(isset($results['images']['totalRows']))
		{
			echo '<li>Серийный номер: '. $car['serial_num'] .'</li>';
			echo '<li>Коробка передач: '. $car['gear_box'] .'</li>';
			echo '<li>Мощность: '. $car['power'] .' л.с.</li>';
		}
		echo '</ul>';
		echo '</div>';
	}
	echo '</div>';
}

include "templates/comments.php"; 
include "templates/include/footer.php";
?>