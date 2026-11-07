<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    public function show(string $filename)
    {
        $filename = basename(urldecode($filename));
        $path = storage_path('app/public/attachments/' . $filename);

        if (file_exists($path) && is_file($path)) {
            $mimeType = mime_content_type($path) ?: 'application/octet-stream';
            return response()->file($path, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        }

        // Return a clean user-friendly page instead of 403 Forbidden
        return response()->view('errors.document-missing', [
            'filename' => $filename,
        ], 404);
    }
}
