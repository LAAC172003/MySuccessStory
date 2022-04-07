<?php

namespace MySuccessStory\controllers;

use MySuccessStory\models\ModelMain;
use MySuccessStory\models\ModelUsers;

class ControllerUsers
{
	/**
	 * Create a user
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 * @author Beaud Rémy <remy.bd@eduge.ch>
	 */
	public function login() : string
	{
		return ModelMain::printJsonValue(ModelMain::getToken());
	}

	/**
	 * Create a user
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function create() : string
	{
		return ModelMain::printJsonValue(ModelUsers::createUser());
	}

	/**
	 * Read a user
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function read() : string
	{
		return ModelMain::printJsonValue(ModelUsers::readUser());
	}

	/**
	 * Update a user
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function update() : string
	{
		return ModelMain::printJsonValue(ModelUsers::updateUser());
	}

	/**
	 * Delete a user
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function delete() : string
	{
		return ModelMain::printJsonValue(ModelUsers::deleteUser());
	}
}