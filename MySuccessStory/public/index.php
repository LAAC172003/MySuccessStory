<?php

// DO NOT TOUCH !

require '../vendor/autoload.php';
require '../routes/routes.php';

use Pecee\Http\Middleware\Exceptions\TokenMismatchException;
use Pecee\SimpleRouter\Exceptions\HttpException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\SimpleRouter;
use MySuccessStory\models\ApiValue;
use MySuccessStory\models\ModelMain;

// Registering the default namespace for controllers
SimpleRouter::setDefaultNamespace("\MySuccessStory\Controller");

// Launch of the router
try
{
	SimpleRouter::start();
}
catch (TokenMismatchException | NotFoundHttpException | HttpException | Exception $e)
{
	echo json_encode(ModelMain::checkHttpCode(new ApiValue(null, "The path " . $_SERVER['PHP_SELF'] . " doesn't exist", $e->getCode())));
}