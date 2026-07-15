<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->get();
        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100 MB max
        ]);

        $file = $request->file('file');
        
        $extension = strtolower($file->getClientOriginalExtension());
        $type = 'document';
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
            $type = 'image';
        } elseif (in_array($extension, ['mp4', 'avi', 'mov', 'wmv', 'mkv', 'webm'])) {
            $type = 'video';
        }

        $path = $file->store('media', 'public');

        Media::create([
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'type' => $type,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()->route('admin.media.index')->with('success', 'Fichier ajouté avec succès à la médiathèque.');
    }

    public function destroy(Media $media)
    {
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        $media->delete();

        return redirect()->route('admin.media.index')->with('success', 'Fichier supprimé.');
    }
}
