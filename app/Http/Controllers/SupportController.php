<?php

namespace App\Http\Controllers;

use App\Models\SupportMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    // Пользователь: отправка сообщения
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        SupportMessage::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject ?? 'Обращение в поддержку',
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Ваше сообщение отправлено! Мы ответим в ближайшее время.');
    }

    // Пользователь: просмотр своих сообщений с пагинацией
    public function userIndex()
    {
        $messages = SupportMessage::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('support.user', compact('messages'));
    }

    // Пользователь: просмотр одного сообщения
    public function userShow($id)
    {
        $message = SupportMessage::where('user_id', Auth::id())->findOrFail($id);
        return view('support.user-show', compact('message'));
    }

    // Пользователь: удаление своего сообщения (только если отвечено или закрыто)
    public function userDestroy($id)
    {
        $message = SupportMessage::where('user_id', Auth::id())->findOrFail($id);
        
        // Разрешаем удалять только отвеченные или закрытые сообщения
        if ($message->status === 'pending') {
            return redirect()->back()->with('error', 'Нельзя удалить сообщение, пока оно ожидает ответа');
        }
        
        $message->delete();
        
        return redirect()->route('support.user.index')->with('success', 'Обращение удалено');
    }

    // Админ: список всех сообщений
    public function adminIndex()
    {
        $messages = SupportMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendingCount = SupportMessage::pending()->count();

        return view('admin.support.index', compact('messages', 'pendingCount'));
    }

    // Админ: просмотр сообщения
    public function adminShow($id)
    {
        $message = SupportMessage::with('user')->findOrFail($id);
        
        // Отмечаем как прочитанное
        if (!$message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('admin.support.show', compact('message'));
    }

    // Админ: ответ на сообщение
    public function adminReply(Request $request, $id)
    {
        $request->validate([
            'admin_reply' => 'required|string|min:5',
        ]);

        $message = SupportMessage::findOrFail($id);
        
        $message->update([
            'admin_id' => Auth::id(),
            'admin_reply' => $request->admin_reply,
            'status' => 'answered',
        ]);

        return redirect()->route('admin.support.show', $id)
            ->with('success', 'Ответ отправлен пользователю!');
    }

    // Админ: закрыть обращение
    public function adminClose($id)
    {
        $message = SupportMessage::findOrFail($id);
        $message->update(['status' => 'closed']);

        return redirect()->route('admin.support.index')
            ->with('success', 'Обращение закрыто.');
    }

    // Админ: удалить сообщение
    public function adminDestroy($id)
    {
        $message = SupportMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.support.index')
            ->with('success', 'Сообщение удалено.');
    }
}