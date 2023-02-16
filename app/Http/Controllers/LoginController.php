<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
 
class LoginController extends Controller
{
    /**
     * Traiter une tentative d'authentification.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        /* 
        * Méthode attempt utilisée pour gérer les tentatives 
        * d'authentification depuis le formulaire de connexion
        * elle attend un tableau de paires clé/valeur
        */
        if (Auth::attempt($credentials, $remember = true)) {
            /*
            * attempt réussi => true 
            * authentification réussit régénération de la session de l'utilisateur
            * pour empêcher la fixation de la session(les attaques)
            */
            $request->session()->regenerate();
            
            // redirection vers l'url demandée
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'Les informations d\'identification fournies ne correspondent pas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
{
    Auth::logout();
 
    $request->session()->invalidate();
 
    $request->session()->regenerateToken();
 
    return redirect('/');
}

}