<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use KeycloakGuard\Exceptions\ResourceAccessNotAllowedException;
use KeycloakGuard\Exceptions\TokenException;
use KeycloakGuard\Exceptions\UserNotFoundException;
use Saloon\Exceptions\Request\ClientException;
use Saloon\Exceptions\Request\ServerException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response|JsonResponse
    {
        /**
         * Keycloak Guard Exceptions
         */
        if ($e instanceof TokenException) {
            return response()->json(
                ['error' => $e->getMessage()],
                ResponseAlias::HTTP_UNAUTHORIZED
            );
        }

        if ($e instanceof UserNotFoundException) {
            return response()->json(
                ['error' => $e->getMessage()],
                ResponseAlias::HTTP_NOT_FOUND
            );
        }

        if ($e instanceof ResourceAccessNotAllowedException) {
            return response()->json(
                ['error' => $e->getMessage()],
                ResponseAlias::HTTP_FORBIDDEN
            );
        }

        /**
         * Saloon Exceptions
         */
        if ($e instanceof ClientException) {
            return response()->json([
                'error_status' => $e->getStatus(),
                'error_message' => $e->getMessage()
            ], $e->getStatus());
        }

        if ($e instanceof ServerException) {
            return response()->json([
                'error_status' => $e->getStatus(),
                'error_message' => $e->getMessage()
            ], $e->getStatus());
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'error_message' => $e->getMessage()
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return parent::render($request, $e);


    }
}
