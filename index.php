<?php

require( "config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['user'] ) ? $_SESSION['user'] : "";

switch ( $action ) {
  case 'archive':
    archive();
    break;
  case 'viewArticle':
    viewArticle();
    break;
  case 'viewCar':
    viewCar();
    break;
  case 'carsCategories':
    carsCategories();
    break;
  case 'viewCategory':
    viewCategory();
    break;
  case 'signup':
    signup();
    break;
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  default:
    homepage();
}

function archive() {
  $results = array();
  $data = Article::getList();
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Article Archive | Cars News";
  require( TEMPLATE_PATH . "/archive.php" );
}

function viewArticle() {
  if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
    homepage();
    return;
  }

  $results = array();
  $results['article'] = Article::getById( (int)$_GET["articleId"] );
  $results['pageTitle'] = $results['article']->title . " | Cars News";
  require( TEMPLATE_PATH . "/viewArticle.php" );
}

function homepage() {
  $results = array();
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "newUser" ) $results['statusMessage'] = "You have been successfully registered! Log in.";
  }

  $data = Article::getList( HOMEPAGE_NUM_ARTICLES );
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Cars News";
  require( TEMPLATE_PATH . "/homepage.php" );
}

function viewCar() {
  if ( !isset($_GET["carId"]) || !$_GET["carId"] ) {
    homepage();
    return;
  }

  $results = array();
  $results['cars'] = array();
  $results['cars'][0] = Car::getById( (int)$_GET["carId"] );
  $results['images'] = Image::getByCarId( (int)$_GET["carId"] );
  $results['category'] = Category::getById( $results['cars'][0]->categoryId );
  $results['pageTitle'] = $results['cars'][0]->name . " | Cars News";
  require( TEMPLATE_PATH . "/viewCar.php" );
}

function carsCategories() {
  $data = Category::getList();
  $results['pageTitle'] = "Категории машин";
  require( TEMPLATE_PATH . "/carsCategories.php");
}

function viewCategory() {
  $results = array();
  $categoryId = ( isset( $_GET['categoryId'] ) && $_GET['categoryId'] ) ? (int)$_GET['categoryId'] : null;
  $results['category'] = Category::getById( $categoryId );
  $data = Car::getList( 100000, $categoryId);
  $results['cars'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageHeading'] = $results['category'] ?  $results['category']->name : "Car Category";
  $results['pageTitle'] = $results['pageHeading'] . " | Cars News";
  $results['images'] = array();
  foreach ( $results['cars'] as $car ) {
    $temp = Image::getByCarId($car->id);
    if($temp['totalRows'] > 0)
      $results['images'][$car->id] = $temp['results'][0];
  }
  require( TEMPLATE_PATH . "/viewCar.php" );
}

function signup() {

  $results = array();
  $results['pageTitle'] = "User Login | Cars News";

  if ( isset( $_POST['signup'] ) ) {

    // Пользователь получает форму регистрации
    if( User::getByName($_POST['name']) ) {
      $results['errorMessage'] = "This name is already registered. Please try again.";
      require( TEMPLATE_PATH . "/signupForm.php" );
      return;
    }

    if( User::getByEmail($_POST['email']) ) {
      $results['errorMessage'] = "This email is already registered. Please try again.";
      require( TEMPLATE_PATH . "/signupForm.php" );
      return;
    }

    if($_POST['password1'] != $_POST['password2'] ) {
      $results['errorMessage'] = "Passwords do not match. Please try again.";
      require( TEMPLATE_PATH . "/signupForm.php" );
      return;
    }

    $user = new User;
    $user->storeFormValues( $_POST );
    $user->password = $_POST['password1'];
    $user->insert();
    header( "Location: index.php?status=newUser" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // Пользователь сбросид результаты: возвращаемся к списку статей
    header( "Location: index.php" );
  } else {
    // Пользователь еще не получил форму: выводим форму
    $results['user'] = new User;
    require( TEMPLATE_PATH . "/signupForm.php" );
  }

}

function login() {
  $results = array();
  $results['pageTitle'] = "User Login | Cars News";

  if ( isset( $_POST['login'] ) ) {

    $email = $_POST['email'];
    $user = User::getByEmail($email);
    // Пользователь получает форму входа: попытка авторизировать пользователя
    if ( $_POST['password'] == $user->password ) {
      // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора

      if(isset($_SESSION['username']))
      {
        unset($_SESSION['username']);
      }

      $user->startSession();
      header( "Location: index.php" );
    } else {
      // Ошибка входа: выводим сообщение об ошибке для пользователя
      $results['errorMessage'] = "Incorrect email or password. Please try again.";
      require( TEMPLATE_PATH . "/loginForm.php" );
    }

  } else {

    // Пользователь еще не получил форму: выводим форму
    require( TEMPLATE_PATH . "/loginForm.php" );
  }
}

function logout() {
  $name = $_SESSION['user'];
  $user = User::getByName($name);
  $user->endSession();
  header( "Location: index.php" );
}

?>
