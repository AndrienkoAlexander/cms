<?php 
include "templates/include/header.php";
if(isset($_SESSION['user'])) 
	include "templates/include/userHeader.php"; 
$categories_tree = Category::map_tree($data['results']);
$categories_menu = Category::categories_to_string($categories_tree);
?>

<div class="catalog">
	<ul class="category"> 
		<?php  echo $categories_menu; ?>
	</ul>
</div>

<script src="js/jquery-1.4.3.min.js"></script>
<script src="js/jquery.accordion.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="js/categories.js"></script>

<?php include "templates/include/footer.php" ?>