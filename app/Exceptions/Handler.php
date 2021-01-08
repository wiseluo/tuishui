<?php

namespace App\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use \UnexpectedValueException;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        if($request->is('api/*') || $request->is('erp/*') || $request->is('tax/*') || $request->is('external/*')){
            $response = [];
            $response['code'] = empty($exception->getCode()) ? 401 : $exception->getCode();
            $response['msg'] = empty($exception->getMessage()) ? 'token错误' : $exception->getMessage();
            return response()->json($response);
        }
        if($exception instanceof SignatureInvalidException || $exception instanceof UnexpectedValueException) {
            $result = [
                'code'=> 400,
                'msg'=> 'token错误'
            ];
            return response()->json($result);
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
        // dump($exception->guards());
        // if(in_array('member', $exception->guards())) {
        //     return redirect()->guest('/member/login');
        // }
        return redirect()->guest(route('login'));
    }
}
