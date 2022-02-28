<?php

namespace MySuccessStory\controllers;

use MySuccessStory\models\ModelUsers;

class ControllerUsers
{
	public static function Token()
	{
		return json_encode(ModelUsers::jwtGenerator());
	}
}