<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\VideoFashion;
use Illuminate\Support\Str;

class UploadVideoController extends Controller
{
    /**
     * Menangani upload file sementara (saat tombol 'Next' di halaman upload.blade.php diklik).
     * File fisik disimpan ke storage, data file disimpan di sesi.
     */
    public function uploadAndRedirect(Request $request)
    {
        // 1. Validasi File yang diupload
        $request->validate([
            'media' => 'required|file|mimes:mp4,mov,avi,jpeg,png,gif|max:2048000', // Max 2GB
        ]);

        $file = $request->file('media');
        $originalFileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        $fileFormat = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();

        // 2. Simpan FILE FISIKNYA ke Storage Server
        $path = $file->store('videos', 'public');

        // 3. Simpan semua data file di SESI
        $request->session()->put('temp_uploaded_file_data', [
            'fileName' => $originalFileName,
            'fileSize' => $fileSize,
            'fileFormat' => $fileFormat,
            'mimeType' => $mimeType,
            'filePath' => $path, // Ini adalah path relatif di storage
        ]);

        return response()->json([
            'success' => true,
            // Halaman detail tidak lagi memerlukan ID yang berarti,
            // karena data diambil dari sesi. ID 0 sudah cukup.
            'redirect_url' => route('upload.detail', ['id' => 0])
        ]);
    }

    /**
     * Menampilkan halaman detail video (setelah upload sementara, sebelum final post).
     * Data yang ditampilkan diambil dari sesi.
     */
    public function showVideoDetail($id) // $id di sini akan selalu 0
    {
        // Ambil data file sementara dari sesi
        $tempUploadData = session('temp_uploaded_file_data');

        if (!$tempUploadData) {
            return redirect()->route('upload')->with('error', 'Tidak ada file yang sedang diunggah untuk dilihat detailnya.');
        }

        // Buat objek VideoFashion dummy untuk mengisi view.
        // idVideoFashion di sini hanya placeholder, tidak ada di DB sampai finalPost.
        $videoFashion = (object)[
            'idVideoFashion' => $id, // ID dari URL (yang merupakan placeholder 0)
            'idPengguna' => Auth::id(), // ID pengguna yang sedang login
            'deskripsi' => $tempUploadData['fileName'], // Nama file asli sebagai deskripsi awal
            'tag' => null, // Default null untuk diisi di form
            'formatFile' => $tempUploadData['fileFormat'],
            'ukuranFile' => $tempUploadData['fileSize'],
            'pathFile' => $tempUploadData['filePath'], // Perlu ini untuk pratinjau di halaman detail
            'mimeType' => $tempUploadData['mimeType'], // Perlu ini
        ];

        return view('detailvideoupload', compact('videoFashion', 'tempUploadData'));
    }

    /**
     * Menangani penyimpanan data video dan detail final ke database
     * (saat tombol 'Post' di halaman detailvideoupload.blade.php diklik).
     */
    public function finalPost(Request $request, $id) // $id akan menjadi 0 (placeholder) di sini
    {
        // 1. Ambil data file sementara dari sesi
        $tempUploadData = $request->session()->get('temp_uploaded_file_data');

        if (!$tempUploadData) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi upload file kadaluarsa atau tidak ditemukan. Mohon ulangi proses upload dari awal.'
            ], 400);
        }

        // 2. Validasi data dari form halaman detail
        $request->validate([
            'description' => 'nullable|string|max:500',
            'hashtags' => 'nullable|string|max:255',
            'tagPeople' => 'nullable|string|max:255',
            'outfitLink' => 'nullable|url|max:255',
        ]);

        try {
            // 3. Buat entri baru di database untuk video ini
            $videoFashion = new VideoFashion();

            // 4. Isi data model dari input form dan data dari sesi
            $videoFashion->idPengguna = Auth::id(); // Ambil ID pengguna yang login
            $videoFashion->deskripsi = $request->input('description') ?? $tempUploadData['fileName']; // Ambil deskripsi dari form, fallback ke nama file
            $videoFashion->tag = $request->input('hashtags'); // Simpan hashtag ke kolom 'tag'
             $videoFashion->outfitLink = $request->input('outfitLink');

            $videoFashion->formatFile = $tempUploadData['fileFormat'];
            $videoFashion->ukuranFile = $tempUploadData['fileSize'];
            $videoFashion->pathFile = $tempUploadData['filePath'];     // Menyimpan pathFile ke DB
            $videoFashion->mimeType = $tempUploadData['mimeType'];     // Menyimpan mimeType ke DB

            $videoFashion->save(); // Simpan data ke database

            // 5. Hapus data file sementara dari sesi setelah berhasil disimpan ke DB
            $request->session()->forget('temp_uploaded_file_data');

            // 6. Kunci perubahan: Redirect ke halaman home setelah berhasil post
            return response()->json([
                'success' => true,
                'message' => 'Video dan detail berhasil di-post! Anda akan dialihkan ke beranda.',
                'redirect_url' => route('home') // <---- INI PERUBAHAN UTAMANYA
            ]);

        } catch (\Exception $e) {
            \Log::error("Final post to DB failed for user " . Auth::id() . ": " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());

            // Opsional: Hapus file fisik dari storage jika penyimpanan DB gagal
            if (isset($tempUploadData['filePath']) && Storage::disk('public')->exists($tempUploadData['filePath'])) {
                Storage::disk('public')->delete($tempUploadData['filePath']);
                \Log::warning("Physical file " . $tempUploadData['filePath'] . " was deleted due to DB save failure.");
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan detail video ke database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menangani aksi 'Discard' di halaman detail.
     * Akan menghapus file fisik sementara dari storage dan data dari sesi.
     */
    public function discardUpload(Request $request)
    {
        $tempUploadData = $request->session()->get('temp_uploaded_file_data');

        if ($tempUploadData && isset($tempUploadData['filePath']) && Storage::disk('public')->exists($tempUploadData['filePath'])) {
            Storage::disk('public')->delete($tempUploadData['filePath']);
            \Log::info("Discarded temporary file: " . $tempUploadData['filePath']);
        }
        $request->session()->forget('temp_uploaded_file_data');

        return response()->json(['success' => true, 'message' => 'Upload dibatalkan.', 'redirect_url' => route('upload')]);
    }
}
