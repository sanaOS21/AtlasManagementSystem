<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // 【01】タイムアウトになった時の遷移先
        if (!$request->expectsJson()) {
            return route('loginView');
        }
        // 【01】上記書き込みで他のページも表示される！
    }
}
