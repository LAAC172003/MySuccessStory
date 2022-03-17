<?php

namespace MySuccessStory\controllers;

use MySuccessStory\models\ModelSubjects;
use MySuccessStory\models\ModelMain;

class ControllerSubject
{
	/**
	 * Read all subjects
	 * @return bool|string
	 * @author Jordan Folly <ekoue-jordan.fllsd@eduge.ch>
	 */
	public static function read()
	{
		return ModelMain::printJsonValue(ModelSubjects::readSubjects());
	}
}