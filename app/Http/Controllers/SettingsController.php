<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }
    
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark'
        ]);
        
        $user = Auth::user();
        $user->theme = $request->theme;
        $user->save();
        
        return response()->json(['success' => true]);
    }
    
    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:ru,en'
        ]);
        
        $user = Auth::user();
        $user->language = $request->language;
        $user->save();
        
        app()->setLocale($request->language);
        
        return response()->json(['success' => true]);
    }
}