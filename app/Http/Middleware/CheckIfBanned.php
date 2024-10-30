<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user && $user->isBanned()) {
            Auth::logout();
            $request->session()->flush();
            session()->flash('filament.notifications', [
                Notification::make()
                    ->title('Account Suspended')
                    ->body('Your account has been banned. Please contact support.')
                    ->warning()
                    ->toArray()
            ]);
            
            return redirect()->back()->withErrors([
                'email' => 'Access restricted to admins only.',
            ]);
        }
        
        return $next($request);
    }
}
