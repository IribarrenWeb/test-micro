<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            return response()->json([
            'message' => 'No estas autenticado en el sistema.'
            ],401);
        }elseif($exception instanceof AuthorizationException){
            $msg=$exception->getMessage() ?? "No estas autorizado para realizar esta petición.";
            $code=$this->getCleanCode($exception);
            return response()->json([
            'message' => $msg
            ],$code);
        }elseif($exception instanceof RelationNotFoundException){
            return response()->json([
                'message' => 'No existe la relacion solicitada.'
                ],404);
        }elseif($exception instanceof ModelNotFoundException){
            return response()->json([
                'message' => 'No existe el recurso solicitado.'
                ],404);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {

	// Here you can return your own response or work with request
	// return response()->json(['status' : false], 401);

	// This is the default
        return response()->json(['message' => $exception->getMessage()], 401);
    }
}
