<?php

require( "config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
  login();
  exit;
}

switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newArticle':
    newArticle();
    break;
  case 'editArticle':
    editArticle();
    break;
  case 'deleteArticle':
    deleteArticle();
    break;
  case 'newCar':
    newCar();
    break;
  case 'editCar':
    editCar();
    break;
  case 'deleteCar':
    deleteCar();
    break;
  case 'listCars':
    listCars();
    break;
  case 'listCategories':
    listCategories();
    break;
  case 'newCategory':
    newCategory();
    break;
  case 'editCategory':
    editCategory();
    break;
  case 'deleteCategory':
    deleteCategory();
    break;
  case 'listUsers':
    listUsers();
    break;
  case 'deleteUser':
    deleteUser();
    break;
  case 'listComments':
    listComments();
    break;
  case 'shownComment':
    shownComment();
    break;
  default:
    listArticles();
}


function login() {

  $results = array();
  $results['pageTitle'] = "Admin Login | Cars News";

  if ( isset( $_POST['login'] ) ) {

    // Пользователь получает форму входа: попытка авторизировать пользователя

    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {

      // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
      $_SESSION['username'] = ADMIN_USERNAME;

      if(isset($_SESSION['user']))
      {
        unset($_SESSION['user']);
      }

      header( "Location: admin.php" );

    } else {

      // Ошибка входа: выводим сообщение об ошибке для пользователя
      $results['errorMessage'] = "Incorrect username or password. Please try again.";
      require( TEMPLATE_PATH . "/admin/loginForm.php" );
    }

  } else {

    // Пользователь еще не получил форму: выводим форму
    require( TEMPLATE_PATH . "/admin/loginForm.php" );
  }

}


function logout() {
  unset( $_SESSION['username'] );
  header( "Location: admin.php" );
}


function newArticle() {

  $results = array();
  $results['pageTitle'] = "New Article";
  $results['formAction'] = "newArticle";

  if ( isset( $_POST['saveChanges'] ) ) {

    // Пользователь получает форму редактирования статьи: сохраняем новую статью
    $article = new Article;
    $article->storeFormValues( $_POST );
    $article->insert();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // Пользователь сбросид результаты редактирования: возвращаемся к списку статей
    header( "Location: admin.php" );
  } else {

    // Пользователь еще не получил форму редактирования: выводим форму
    $results['article'] = new Article;
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }

}


function editArticle() {

  $results = array();
  $results['pageTitle'] = "Edit Article";
  $results['formAction'] = "editArticle";

  if ( isset( $_POST['saveChanges'] ) ) {

    // Пользователь получил форму редактирования статьи: сохраняем изменения

    if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
      header( "Location: admin.php?error=articleNotFound" );
      return;
    }

    $article->storeFormValues( $_POST );
    $article->update();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
    header( "Location: admin.php" );
  } else {

    // Пользвоатель еще не получил форму редактирования: выводим форму
    $results['article'] = Article::getById( (int)$_GET['articleId'] );
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }

}


function deleteArticle() {

  if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
    header( "Location: admin.php?error=articleNotFound" );
    return;
  }

  $article->delete();
  header( "Location: admin.php?status=articleDeleted" );
}


function listArticles() {
  $results = array();
  $data = Article::getList();
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "All Articles";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Error: Article not found.";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "articleDeleted" ) $results['statusMessage'] = "Article deleted.";
  }

  require( TEMPLATE_PATH . "/admin/listArticles.php" );
}

function newCar() {

  $results = array();
  $results['pageTitle'] = "New Car";
  $results['formAction'] = "newCar";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the car edit form: save the new car
    $car = new Car;
    $car->storeFormValues( $_POST );
    $car->insert();
    print_r($car);
    header( "Location: admin.php?action=listCars&status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the car list
    header( "Location: admin.php?action=listCars" );
  } else {

    // User has not posted the car edit form yet: display the form
    $results['car'] = new Car;
    $data = Category::getList();
    $results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editCar.php" );
  }

}


function editCar() {

  $results = array();
  $results['pageTitle'] = "Edit Car";
  $results['formAction'] = "editCar";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the car edit form: save the car changes

    if ( !$car = Car::getById( (int)$_POST['carId'] ) ) {
      header( "Location: admin.php?error=carNotFound" );
      return;
    }

    $car->storeFormValues( $_POST );
    $car->update();
    header( "Location: admin.php?status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the car list
    header( "Location: admin.php" );
  } else {

    // User has not posted the car edit form yet: display the form
    $results['car'] = Car::getById( (int)$_GET['carId'] );
    $data = Category::getList();
    $results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editCar.php" );
  }

}


function deleteCar() {

  if ( !$car = Car::getById( (int)$_GET['carId'] ) ) {
    header( "Location: admin.php?error=carNotFound" );
    return;
  }

  $car->delete();
  header( "Location: admin.php?action=listCars&status=carDeleted" );
}


function listCars() {
  $results = array();
  $data = Car::getList();
  $results['cars'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['images'] = array();
  foreach ( $results['cars'] as $car ) {
    $temp = Image::getByCarId($car->id);
    if($temp['totalRows'] > 0)
      $results['images'][$car->id] = $temp['results'][0];
  }

  $results['pageTitle'] = "All Cars";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "carNotFound" ) $results['errorMessage'] = "Error: Car not found.";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "carDeleted" ) $results['statusMessage'] = "Car deleted.";
  }

  require( TEMPLATE_PATH . "/admin/listCars.php" );
}


function listCategories() {
  $results = array();
  $data = Category::getList();
  $results['categories'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Cars Categories";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
    if ( $_GET['error'] == "categoryContainsCars" ) $results['errorMessage'] = "Error: Category contains carss. Delete the cars, or assign them to another category, before deleting this category.";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
  }

  require( TEMPLATE_PATH . "/admin/listCategories.php" );
}


function newCategory() {

  $results = array();
  $results['pageTitle'] = "New Car Category";
  $results['formAction'] = "newCategory";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the category edit form: save the new category
    $category = new Category;
    $category->storeFormValues( $_POST );
    $category->insert();
    header( "Location: admin.php?action=listCategories&status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listCategories" );
  } else {

    // User has not posted the category edit form yet: display the form
    $results['category'] = new Category;
    require( TEMPLATE_PATH . "/admin/editCategory.php" );
  }

}


function editCategory() {

  $results = array();
  $results['pageTitle'] = "Edit Car Category";
  $results['formAction'] = "editCategory";

  if ( isset( $_POST['saveChanges'] ) ) {

    // User has posted the category edit form: save the category changes

    if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
      header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
      return;
    }

    $category->storeFormValues( $_POST );
    $category->update();
    header( "Location: admin.php?action=listCategories&status=changesSaved" );

  } elseif ( isset( $_POST['cancel'] ) ) {

    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listCategories" );
  } else {

    // User has not posted the category edit form yet: display the form
    $results['category'] = Category::getById( (int)$_GET['categoryId'] );
    require( TEMPLATE_PATH . "/admin/editCategory.php" );
  }

}


function deleteCategory() {

  if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
    header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
    return;
  }

  $cars = Car::getList( 1000000, $category->id );

  if ( $cars['totalRows'] > 0 ) {
    header( "Location: admin.php?action=listCategories&error=categoryContainsCars" );
    return;
  }

  $category->delete();
  header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}

function listUsers() {
  $results = array();
  $data = User::getList();
  $results['users'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Users";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "userNotFound" ) $results['errorMessage'] = "Error: User not found.";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "userDeleted" ) $results['statusMessage'] = "User deleted.";
  }

  require( TEMPLATE_PATH . "/admin/listUsers.php" );
}

function listComments() {
  $results = array();
  $data = Comment::getList();
  $results['comments'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = "Comments";

  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "commentNotFound" ) $results['errorMessage'] = "Error: User not found.";
  }

  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "commentShown" ) $results['statusMessage'] = "Comment is disabled/enabled.";
  }

  require( TEMPLATE_PATH . "/admin/listComments.php" );
}

function deleteUser() {

  if ( !$user = User::getById( (int)$_GET['userId'] ) ) {
    header( "Location: admin.php?action=listUsers&error=userNotFound" );
    return;
  }

  Comment::deleteByUser($user->id);

  $user->delete();
  header( "Location: admin.php?action=listUsers&status=userDeleted" );
}

function shownComment() {

  if ( !$comment = Comment::getById( (int)$_GET['commentId'] ) ) {
    header( "Location: admin.php?action=listComments&error=comentNotFound" );
    return;
  }

  $comment->invertShown();
  header( "Location: admin.php?action=listComments&status=comentShown" );
}

?>
