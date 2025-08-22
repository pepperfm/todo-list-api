<?php

declare(strict_types=1);

namespace App\Bootstrap;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WithExceptions
{
    public function __invoke(Exceptions $exceptions): void
    {
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Not found.'
                ], JsonResponse::HTTP_NOT_FOUND);
            }
        });
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                $errors = $e->errors();
                foreach ($errors as &$error) {
                    $error = $error[0];
                }

                return response()->json([
                    'errors' => $errors,
                    'message' => $e->getMessage(),
                ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            }
        });
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getStatusCode());
            }
        });
    }
}
