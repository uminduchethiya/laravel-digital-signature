<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function index()
    {
        return view('signature');
    }

    public function store(Request $request)
    {
        $request->validate([
            'signature' => 'required'
        ]);

        $signatureData = $request->input('signature');
        $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
        $signatureData = str_replace(' ', '+', $signatureData);
        $imageName = 'signatures/' . uniqid() . '.png';

        Storage::disk('public')->put($imageName, base64_decode($signatureData));

        return response()->json([
            'message' => 'Signature saved successfully!',
            'path' => asset('storage/' . $imageName)
        ]);
    }
}
