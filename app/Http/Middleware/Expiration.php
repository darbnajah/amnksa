<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class Expiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $company = Company::find(1);
        $expiration_dt = $company->expiration_dt;
        $sys_dt = date('Y-m-d');

        $expiration_before_month = \App\Helper\Helper::minusDays($expiration_dt, 30);

        if($expiration_dt > $expiration_before_month && $expiration_dt >= $sys_dt ){
            Session::put('expiration_before_month', $expiration_before_month);
            Session::put('expiration_date', $expiration_dt);

        } else {
            Session::forget('expiration_before_month');
            Session::forget('expiration_date');
        }

        if($sys_dt > $expiration_dt) {
            Session::put('expiration_dt', $expiration_dt);
        } else {
            Session::forget('expiration_dt');
        }
        if($expiration_before_month > $sys_dt ){
            Session::forget('expiration_before_month');
            Session::forget('expiration_date');
            Session::forget('expiration_dt');
        }


            /*dd($user);

            $route = url()->current();

            $route = explode('dashboard/', $route);
            $route = isset($route[1])? $route[1] : null;

            //dd($route);

            if($route && $route != 'contact') {
                if($sys_dt > $expiration_dt) {
                    return redirect('contact');
                }
                //return redirect('contact');
            }

            //dd($request->url(), $next);
            //return redirect('home');
            */
        return $next($request);

    }
}
