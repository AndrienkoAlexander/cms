<!DOCTYPE html>
<html lang="ru">
  <head>    
  	<meta http-equiv="Content-Type" content="text/html">
	<meta charset="utf-8">
    <title><?php echo htmlspecialchars( $results['pageTitle'] )?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/left-nav-style.css">
  </head>
  <body>
    <input type="checkbox" id="nav-toggle" hidden>
    <nav class="nav">
        <label for="nav-toggle" class="nav-toggle" onclick></label>
        <h2 class="logo"> 
            <a href="index.php">Car News</a> 
        </h2>
        <ul>
            <li><a href="index.php">Главная</a>
            <li><a href="index.php?action=signup">Регистрация</a>
            <li><a href="index.php?action=login">Вход</a>
            <li><a href="index.php?action=carsCategories">Каталог машин</a>
            <li><a href="admin.php">Админ панель</a>
            <?php if(isset($_SESSION['username'])) {?>
            <li><a href="admin.php?action=listArticles">Edit Articles</a>
            <li><a href="admin.php?action=listCategories">Edit Categories</a> 
            <li><a href="admin.php?action=listCars">Edit Cars</a>
            <li><a href="admin.php?action=listComments">Edit Comments</a>
            <li><a href="admin.php?action=listUsers">Users</a>
            <?php } ?>
        </ul>
    </nav>
    <div id="container">

      <a href="."><img id="logo" src="images/logo.jpg" alt="Cars News" /></a>

