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
    
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $user->notify_reminders = $request->has('notify_reminders');
        $user->notify_expenses = $request->has('notify_expenses');
        $user->notify_refuelings = $request->has('notify_refuelings');
        $user->notify_summary = $request->has('notify_summary');
        $user->save();
        
        return redirect()->route('settings.index')->with('success', '✅ Настройки уведомлений обновлены!');
    }
}