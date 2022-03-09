<?php

namespace MySuccessStory\exception;

use MySuccessStory\models\ApiValue;

class ApiException extends \Exception
{
    protected $message = "";
    protected $code = 0;

    public function checkHttpCode(ApiValue $modelApiValue)
    {
        $value =$modelApiValue->value;
        var_dump($value);
    }
}