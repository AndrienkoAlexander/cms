<?php

ini_set( "display_errors", true );
date_default_timezone_set( "Europe/Kiev" );  // http://www.php.net/manual/en/timezones.php
define( "DB_DSN", "mysql:host=localhost;dbname=cms" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "" );
define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "admin" );
require( CLASS_PATH . "/Article.php" );
require( CLASS_PATH . "/Car.php" );
require( CLASS_PATH . "/Category.php" );
require( CLASS_PATH . "/Image.php" );
require( CLASS_PATH . "/User.php" );
require( CLASS_PATH . "/Comment.php" );

function handleException( $exception ) {
	echo "Sorry, a problem occurred. Please try later.<br>";
	echo $exception;
}

set_exception_handler( 'handleException' );

?>
