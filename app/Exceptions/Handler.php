<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
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
        //return parent::render($request, $exception);
		if($exception instanceof ModelNotFoundException){

        if($request->ajax()){

            return response()->json(['ret'=>'ERROR','message'=>'Model Not Found'],404);

        }

        return response()->view('errors.'.'4044',[],404);

    }

    if($exception instanceof TokenMismatchException){

        if($request->ajax()){

            return response()->json(['ret'=>'ERROR','message'=>'Token Mismatch'],400);

        }

        \Flash::error('表单重复提交，请刷新页面再试！');

        return \Redirect::back()->withInput()->withErrors('表单重复提交，请刷新页面再试！');

    }
	
    return parent::render($request, $exception);
	
    }
}
