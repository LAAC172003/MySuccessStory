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
	public static function create() : string
	{
		return ModelMain::printJsonValue(ModelNotes::createNote());
	}

	/**
	 * Read a note
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function read() : string
	{
		return ModelMain::printJsonValue(ModelNotes::readNote());
	}

	/**
	 * Update a note
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function update() : string
	{
		return ModelMain::printJsonValue(ModelNotes::updateNote());
	}

	/**
	 * Delete a note
	 * @return string
	 * @author Almeida Costa Lucas <lucas.almdc@eduge.ch>
	 */
	public static function delete() : string
	{
		return ModelMain::printJsonValue(ModelNotes::deleteNote());
	}
}