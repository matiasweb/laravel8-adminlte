<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    // Mostrar la página de ajustes generales
    public function index()
    {
        $user = Auth::user();
        $preferences = is_array($user->preferences) 
            ? $user->preferences 
            : json_decode($user->preferences, true) ?? [];
    
        return view('ajustes.ajustes', compact('user', 'preferences'));
    }

    // Actualizar perfil del usuario
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    // Actualizar preferencias del usuario
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:dark,light',
        ]);
    
        $user = Auth::user();
    
        // Asegúrate de que preferences es un arreglo
        $preferences = is_array($user->preferences) 
            ? $user->preferences 
            : json_decode($user->preferences, true) ?? [];
    
        // Actualiza las preferencias con el nuevo valor
        $preferences['theme'] = $request->theme;
    
        // Guarda las preferencias como JSON
        $user->preferences = json_encode($preferences);
        $user->save();
    
        return redirect()->route('ajustes.index')->with('success', 'Preferencias guardadas correctamente.');

    }
    
}
