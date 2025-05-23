<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\VideoFashion; // Pastikan model ini ada dan benar

class UploadVideoController extends Controller
{
    /**
     * Menangani upload file sementara (saat tombol 'Next' di halaman upload.blade.php diklik).
     * File fisik disimpan ke storage, data file disimpan di sesi.
     */
    public function uploadAndRedirect(Request $request)
    {
        // 1. Validasi File yang diupload
        // Memastikan ini adalah file yang diperlukan dan sesuai kriteria awal
        $request->validate([
            'media' => 'required|file|mimes:mp4,mov,avi,jpeg,png,gif|max:2048000', // Max 2GB (2048000 KB)
        ]);

        $file = $request->file('media');
        $originalFileName = $file->getClientOriginalName();
        $fileSize = $file->getSize(); // Ukuran dalam bytes
        $fileFormat = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType(); // Dapatkan MIME type untuk pratinjau sementara

        // 2. Simpan FILE FISIKNYA ke Storage Server
        // File akan tersimpan di storage/app/public/videos/nama_unik.ext
        // Ini adalah penyimpanan sementara file fisik, akan dihapus jika tidak jadi di-post
        $path = $file->store('videos', 'public');

        // 3. Simpan semua data file di SESI (untuk diteruskan ke halaman detail)
        $request->session()->put('temp_uploaded_file_data', [
            'fileName' => $originalFileName,
            'fileSize' => $fileSize,
            'fileFormat' => $fileFormat,
            'mimeType' => $mimeType,
            'filePath' => $path, // Ini adalah path relatif di storage
        ]);

        // Tidak ada interaksi database di sini (ini adalah titik perubahan utama)
        // Redirect ke halaman detail dengan ID placeholder (misalnya 0 atau ID sementara)
        // ID ini tidak merujuk ke entri database yang sebenarnya, hanya untuk memenuhi route
        return response()->json([
            'success' => true,
            'redirect_url' => route('upload.detail', ['id' => 0]) // Menggunakan ID placeholder 0
        ]);
    }

    /**
     * Menampilkan halaman detail video (setelah upload sementara, sebelum final post).
     * Data yang ditampilkan diambil dari sesi.
     */
    public function showVideoDetail($id)
    {
        // Ambil data file sementara dari sesi
        $tempUploadData = session('temp_uploaded_file_data');

        if (!$tempUploadData) {
            // Jika tidak ada data di sesi (misalnya user langsung akses URL detail),
            // redirect kembali ke halaman upload atau tampilkan pesan error
            return redirect()->route('upload')->with('error', 'Tidak ada file yang sedang diunggah untuk dilihat detailnya.');
        }

        // Buat objek VideoFashion dummy untuk mengisi view.
        // Ini diperlukan karena di Blade kita mengakses properti seperti $videoFashion->deskripsi
        // Objek ini tidak diambil dari database karena belum disimpan permanen.
        $videoFashion = (object)[
            'idVideoFashion' => $id, // ID dari URL (yang merupakan placeholder 0)
            'idPengguna' => Auth::id(), // ID pengguna yang sedang login
            'deskripsi' => $tempUploadData['fileName'], // Nama file asli sebagai deskripsi awal
            'tag' => null, // Default null untuk diisi di form
            'formatFile' => $tempUploadData['fileFormat'],
            'ukuranFile' => $tempUploadData['fileSize'],
            // Tidak ada pathFile atau mimeType di objek ini karena tidak disimpan di DB
        ];

        // Halaman detail akan menggunakan $videoFashion (objek dummy) dan $tempUploadData (dari sesi)
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
            // Jika tidak ada data file sementara di sesi, berarti alurnya salah atau sesi kadaluarsa
            return response()->json([
                'success' => false,
                'message' => 'Sesi upload file kadaluarsa atau tidak ditemukan. Mohon ulangi proses upload dari awal.'
            ], 400);
        }

        // 2. Validasi data dari form halaman detail
        $request->validate([
            'description' => 'nullable|string|max:500', // Sesuai dengan kolom 'deskripsi'
            'hashtags' => 'nullable|string|max:255',   // Sesuai dengan kolom 'tag'
            'tagPeople' => 'nullable|string|max:255',   // Ini tidak akan disimpan ke DB (tidak ada kolom)
            'outfitLink' => 'nullable|url|max:255',     // Ini tidak akan disimpan ke DB (tidak ada kolom)
        ]);

        try {
            // 3. Buat entri baru di database untuk video ini
            // Karena ID dari URL ($id) adalah placeholder (0), kita selalu membuat entri baru.
            $videoFashion = new VideoFashion();

            // 4. Isi data model dari input form dan data dari sesi
            $videoFashion->idPengguna = Auth::id(); // Ambil ID pengguna yang sedang login
            $videoFashion->deskripsi = $request->input('description') ?? $tempUploadData['fileName']; // Ambil deskripsi dari form, fallback ke nama file
            $videoFashion->tag = $request->input('hashtags'); // Simpan hashtag ke kolom 'tag'

            $videoFashion->formatFile = $tempUploadData['fileFormat']; // Data dari sesi
            $videoFashion->ukuranFile = $tempUploadData['fileSize'];   // Data dari sesi

            // --- PENTING: TIDAK MENYIMPAN pathFile atau mimeType ke database ---
            // Baris ini DIHAPUS agar tidak ada error 'Unknown column'
            // $videoFashion->pathFile = $tempUploadData['filePath']; // DIHAPUS
            // $videoFashion->mimeType = $tempUploadData['mimeType']; // DIHAPUS jika tidak ada kolom

            $videoFashion->save(); // Simpan data ke database

            // 5. Hapus data file sementara dari sesi setelah berhasil disimpan ke DB
            $request->session()->forget('temp_uploaded_file_data');

            // 6. Redirect ke halaman detail video yang baru disimpan
            // ID yang di-redirect sekarang adalah ID asli dari database
            return response()->json([
                'success' => true,
                'message' => 'Video dan detail berhasil di-post!',
                'redirect_url' => route('upload.detail', ['id' => $videoFashion->idVideoFashion])
            ]);

        } catch (\Exception $e) {
            // Jika terjadi error saat menyimpan ke DB:
            // - Catat error ke log Laravel (sangat penting untuk debugging)
            \Log::error("Final post to DB failed for user " . Auth::id() . ": " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());

            // - Opsional: Hapus file fisik dari storage jika penyimpanan DB gagal
            //   Ini lebih kompleks karena file sudah di-store saat 'Next' diklik.
            //   Jika file ini hanya 'temp_uploads' yang tidak jadi di-post,
            //   maka bisa dipertimbangkan untuk menghapusnya melalui cron job atau fitur discard.
            if (isset($tempUploadData['filePath']) && Storage::disk('public')->exists($tempUploadData['filePath'])) {
                Storage::disk('public')->delete($tempUploadData['filePath']);
                \Log::warning("Physical file " . $tempUploadData['filePath'] . " was deleted due to DB save failure.");
            }


            // Kembalikan respons error ke browser
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan detail video ke database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menangani aksi 'Discard' di halaman detail (opsional).
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

        return response()->json(['success' => true, 'message' => 'Upload dibatalkan.']);
    }
}
