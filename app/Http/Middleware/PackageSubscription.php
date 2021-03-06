<?php

namespace Splate\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;
use Splate\PackageUser;

class PackageSubscription
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
        $exist = PackageUser::where('status', 1)
            ->where('user_id', Auth::user()->id)->first();

        if (empty($exist)) {
            return redirect()->route('packages.unsubscribed');
        }

        $timezone = !empty(config('app.timezone')) ? config('app.timezone') : 'Asia/Kuala_Lumpur';
        $today = Carbon::now($timezone);

        if ($today->diffInSeconds($exist->expired_at, false) < 0) {
            return redirect()->route('packages.expired');
        }

        return $next($request);
    }
}
