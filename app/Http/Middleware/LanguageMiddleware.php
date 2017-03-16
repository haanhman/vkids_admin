<?php

namespace App\Http\Middleware;

use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();
        if(!empty($user)) {
            $lang = $user->lang;
        } else {
            $lang = isset($_COOKIE['user_lang']) ? trim($_COOKIE['user_lang']) : config('app.locale');
        }
        \App::setLocale($lang);
        return $next($request);
    }
}
