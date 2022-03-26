<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;


class Authenticate extends Middleware
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {

        if ($this->auth->guest())
        {
            if ($request->ajax())
            {
                return response('Unauthorized.', 401);
            }
            else
            {
                return redirect()->guest('/login');
            }
        }

        //dd($_SESSION['subdomain']);
        URL::defaults(['account' => $request->account]);


        $company = Company::find(1);
        Session::put('company', $company);

        /*
        if(auth()->user()->seller_id > 0) {
            $route = $request->route()->getName();
            if($route){
                $route = explode('.', $route);
                $route = isset($route[1])? $route[1] : null;
                if($route != 'price_offers') {
                    return redirect()->route('dashboard.price_offers.index');
                }
            }

        }
        */
        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
