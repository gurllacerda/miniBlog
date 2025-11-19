<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // $sessionErrors = session('errors');
        // $erros = [
        //     'email' => $sessionErrors?->get('email') ?? [],
        //     'password' => $sessionErrors?->get('password') ?? [],
        // ];

        return view('auth.login');
    }
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        $request->authenticate();
        // $request->session()->regenerate(); token substitui isso

        if ($request->wantsJson()){// basically to check if the request is from the API and not from the web 
            // create a personal access token and return it in the response
            $token = $request->user()->createToken('api-token')->plainTextToken;
            return response()->json([
            'message' => 'Login com sucesso!',
            'token' => $token,
            'user' => $request->user()
            ]);

        }        

        return redirect()->intended(route('login', absolute: false));
        // return response()->json([
        //     'message' => 'Deu merda!',
        //     'user' => $request->user()
        // ]);
    }
    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
