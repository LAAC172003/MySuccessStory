<?php

namespace MySuccessStory\exception;

use MySuccessStory\models\ModelApiValue;

class ApiException extends \Exception
{
    protected $message = "";
    protected $code = 0;

    public function checkHttpCode(ModelApiValue $modelApiValue)
    {
        $value =$modelApiValue->value;
        var_dump($value);
    }
}