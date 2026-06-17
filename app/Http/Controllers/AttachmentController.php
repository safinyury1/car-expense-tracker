<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function destroy(Attachment $attachment)
    {
        // Проверка прав
        $attachable = $attachment->attachable;
        
        if (method_exists($attachable, 'car')) {
            $ownerId = $attachable->car->user_id ?? null;
        } else {
            $ownerId = $attachable->user_id ?? null;
        }
        
        if ($ownerId !== auth()->id()) {
            abort(403);
        }
        
        // Удаляем файл
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
        
        return back()->with('success', 'Файл удалён!');
    }
}