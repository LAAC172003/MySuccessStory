<?php

// DO NOT TOUCH !

require '../vendor/autoload.php';
require '../routes/routes.php';

use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\models\ApiValue;

// Registering the default namespace for controllers
SimpleRouter::setDefaultNamespace("\MySuccessStory\Controller");

// Launch of the router
try
{
	SimpleRouter::start();
}
catch (TokenMismatchException | NotFoundHttpException | HttpException | Exception $e)
{
	http_response_code(404);
	echo json_encode(new ApiValue(null, "Le chemin " . $_SERVER['PHP_SELF'] . " n'existe pas", 0));
}