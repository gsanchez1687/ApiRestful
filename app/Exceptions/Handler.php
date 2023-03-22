<?php

namespace App\Exceptions;
use Exception;
use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    
    protected $levels = [
        //
    ];

    protected $dontReport = [
    
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected function unauthenticated($request, AuthenticationException $exception){
        return $this->errorResponse('No autenticado.', 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request){
        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);
    }

  

  
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    //Redefiniendo la funcion render
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        //retomando el error de modelo no encontrado
        if ($exception instanceof ModelNotFoundException) {
            $modelo =  strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe Ninguna Instancia del modelo {$modelo} con el id espeficico", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException ){
            return $this->errorResponse('No tienes permisos para ejecutar esta accion',403);
        }

        //Capturando cuando introducen una url equivocada
        if($exception instanceof NotFoundHttpException ){
            return $this->errorResponse('No se encontro la ruta',404);
        }


        return parent::render($request, $exception);//hacemos uso del render del framework
    }
}
