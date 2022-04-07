<?php

namespace MySuccessStory\controllers;

use MySuccessStory\models\ModelNotes;
use MySuccessStory\models\ModelMain;

class ControllerNotes
{
	/**
	 * Create a note
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function create()
	{
		return ModelMain::printJsonValue(ModelNotes::createNote());
	}

	/**
	 * Read a note
	 * @return bool|string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function read() : bool|string
	{
		return ModelMain::printJsonValue(ModelNotes::readNote());
	}

	/**
	 * Update a note
	 * @return bool|string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function update() : bool|string
	{
		return ModelMain::printJsonValue(ModelNotes::updateNote());
	}

	/**
	 * Delete a note
	 * @return bool|string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function delete() : bool|string
	{
		return ModelMain::printJsonValue(ModelNotes::deleteNote());
	}
}