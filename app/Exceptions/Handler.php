<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            if ($exception instanceof ModelNotFoundException) {
                $msg = 'Model Not Found';
            }

            if ($exception instanceof NotFoundHttpException) {
                $msg = 'Not Found Http';
            }

            if ($exception instanceof AuthenticationException) {
                return $this->unauthenticatedReturn();
            }

            if ($exception instanceof UnAuthrizedException) {
                return $this->unauthorizedReturn([]);
            }

            if ($exception instanceof ValidationException) {

                $errors = [];

                foreach ($exception->validator->errors()->toArray() as $key => $messages) {

                    if (str_contains($key, '.')) {
                        [$field, $lang] = explode('.', $key, 2);
                        $formattedKey = "{$field}[{$lang}]";
                    } else {
                        $formattedKey = $key;
                    }

                    $errors[$formattedKey] = $messages;
                }

                return response()->json([
                    'key'    => 'fail',
                    'msg'    => collect($errors)->first()[0],
                    'errors' => $errors,
                ]);
            }


            return $this->response(
                'fail',
                $msg ?? $exception->getMessage(),
                ['line' => $exception->getLine(), 'file' => $exception->getFile()]
            );
        }

        return parent::render($request, $exception);
    }
}
