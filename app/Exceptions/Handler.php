<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException)
        {
            $errors = [];

            foreach($exception->errors() as $errorField => $errorValue)
            {
                $errors[$errorField] = $errorValue[0];
            }

            return response()->json(['errors' => $errors], 400);
        }

        if($exception instanceof ThrottleRequestsException)
        {
            return response()->json(['errors' => 'too many requests'], 429);
        }

        if($exception instanceof ModelNotFoundException)
        {
            return response()->json(['errors' => 'not found'], 404);
        }
        
        return parent::render($request, $exception);
    }
}
