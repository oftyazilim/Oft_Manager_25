<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $documents = DB::table('documents')->get();
      return view('content.dokumantasyon.dokumanlar', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      if ($request->hasFile('pdf_file')) {
        $file = $request->file('pdf_file');
        $filePath = $file->store('public/documents');

        // Veritabanına kaydet
        DB::table('documents')->insert([
            'title' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      $document = DB::table('documents')->where('id', $id)->first();
      $filePath = storage_path('app/public/documents/' . $document->file_path);
      if (!file_exists($filePath)) {
          return response()->json(['error' => 'File not found'], 404);
      }

      return response()->file($filePath);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
