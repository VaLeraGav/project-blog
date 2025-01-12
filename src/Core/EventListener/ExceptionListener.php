<?php

namespace App\Core\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Exception;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse();

        if ($exception instanceof HttpExceptionInterface) {
            $response->headers->replace($exception->getHeaders());

            $message = $exception->getMessage();
            $code = $exception->getStatusCode();
        } else if ($exception instanceof Exception) {
            $message = $exception->getMessage();
            $code = $exception->getCode();
        } else {
            $message = $exception->getMessage();
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response->setStatusCode($code);
        $response->setContent(json_encode(
            [
                'message' => $message,
                'code' => $code,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ]
        ));

        $event->setResponse($response);
    }
}