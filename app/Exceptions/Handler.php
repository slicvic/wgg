<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->ajax()) {
             return $this->renderAjax($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('/');
    }

    /**
     * Render an AJAX exception into an HTTP json response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function renderAjax($request, Exception $exception)
    {
        $data = [
            'error' => [
                'status' => '',
                'message_format' => 'text',
                'message' => ''
            ]
        ];

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $data['error']['status'] = 422;
            $data['error']['message_format'] = 'html';
            $data['error']['message'] = view('flash-message')
                ->withErrors($exception->getResponse()->getData())
                ->render();
        }
        else if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            $data['error']['status'] = 403;
            $data['error']['message'] = $exception->getMessage();
        }
        else if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $data['error']['status'] = 404;
            $data['error']['message'] = trans('messages.system.not_found');
        }
        else {
            $data['error']['status'] = 500;
            $data['error']['message'] = trans('messages.system.something_went_wrong');
        }

        return response()->json($data, $data['error']['status']);
    }
}
