<?php

namespace App\Exceptions;
use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
            return $this->errorResponse('No tienes permisos para ejecutar esta acción',403);
        }

        //Capturando cuando introducen una url equivocada
        if($exception instanceof NotFoundHttpException ){
            return $this->errorResponse("No se encontro la ruta: ".$request->url(),404);
        }

         //Capturando cuando no existe el metodo
         if($exception instanceof MethodNotAllowedHttpException ){
            return $this->errorResponse("El método ".$request->method()." especificado en la petición no es válido",405);
        }

         //Controlando cualquier exception
         if($exception instanceof HttpException ){
            return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());
        }

        //Controlando los Query
        if($exception instanceof QueryException ){
            $code = $exception->errorInfo[1];
            if($code == 1451)
                return $this->errorResponse("No se puede eliminar porque el ID tiene relaciones con otras tablas",409);
        }




        return parent::render($request, $exception);//hacemos uso del render del framework
    }
}
