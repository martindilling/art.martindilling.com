<?php

namespace App\Exceptions;

use Illuminate\Contracts\Support\Responsable;

class CheckoutFailed extends \RuntimeException implements Responsable
{
    public static function priceMismatchOnCharge() : CheckoutFailed
    {
        return new static("Something went wrong.\nIf the problem persists, please contact me [martindilling@gmail.com] with the error code: 1001.\nYour card has not been charged.");
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return response()->view('errors.checkout-failed', ['message' => nl2br($this->getMessage())]);
    }
}
