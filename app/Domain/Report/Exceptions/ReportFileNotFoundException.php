<?php

namespace App\Domain\Report\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ReportFileNotFoundException extends Exception
{
    protected $message = 'The report file could not be found.';
    protected $code = Response::HTTP_NOT_FOUND;

    public function __construct(?string $message = null, ?int $code = null)
    {
        parent::__construct(
            (!$message) ? $this->message : $message,
            (!$code) ? $this->code : $code
        );
    }
}
