<?php

namespace MySuccessStory\controllers;

use MySuccessStory\models\ModelSubjects;
use MySuccessStory\models\ModelMain;

class ControllerSubject
{
	/**
	 * Create a subject
	 * @return string
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function create() : string
	{
		return ModelMain::printJsonValue(ModelSubjects::createSubject());
	}

	/**
	 * Read all subjects
	 * @return string
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function read() : string
	{
		return ModelMain::printJsonValue(ModelSubjects::readSubjects());
	}

	/**
	 * Update a subject
	 * @return string
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function update() : string
	{
		return ModelMain::printJsonValue(ModelSubjects::updateSubject());
	}

	/**
	 * Delete a subject
	 * @return string
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function delete() : string
	{
		return ModelMain::printJsonValue(ModelSubjects::deleteSubject());
	}
}