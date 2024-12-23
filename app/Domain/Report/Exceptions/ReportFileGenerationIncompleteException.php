<?php

namespace App\Domain\Report\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ReportFileGenerationIncompleteException extends Exception
{
    protected $message = 'The file generation for this report is incomplete.';
    protected $code = Response::HTTP_UNPROCESSABLE_ENTITY;

    public function __construct(?string $message = null, ?int $code = null)
    {
        parent::__construct(
            (!$message) ? $this->message : $message,
            (!$code) ? $this->code : $code
        );
    }
}
