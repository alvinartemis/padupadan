<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KoleksiPakaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DigitalWardrobeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedSection = $request->input('section', 'Items'); // Default to 'Items'

        // Define available sections
        $sections = ['Items', 'Outfits'];

        // Define categories for each section
        $categoriesForItem = ['All', 'Tops', 'Bottoms', 'Footwears', 'Others'];
        $categoriesForOutfit = ['All', 'Casual', 'Formal', 'Party', 'Others']; // New categories for Outfits

        if ($selectedSection == 'Items') {
            $currentCategories = $categoriesForItem;
        } elseif ($selectedSection == 'Outfits') {
            $currentCategories = $categoriesForOutfit;
        } else {
            // Default to 'Items' categories if section is somehow invalid
            $selectedSection = 'Items';
            $currentCategories = $categoriesForItem;
        }

        // If category is passed from a section change, it might not be valid for the new section.
        // So, we check if the passed category is valid, otherwise default to 'All'.
        // Or, more simply, when a section is explicitly chosen (not the initial load), default category to 'All'.
        // For this implementation, we'll check if the current category is valid for the section.
        $selectedCategory = $request->input('category', 'All');
        if (!in_array($selectedCategory, $currentCategories)) {
            $selectedCategory = 'All'; // Reset to 'All' if category is not in the current section's list
        }
        
        // Ensure this correctly fetches the identifier for the 'idPengguna' column
        $userIdPengguna = $user->idPengguna; // Assuming User model PK is idPengguna
        // If User model PK is 'id', use: $userIdPengguna = $user->id;

        $query = KoleksiPakaian::where('idPengguna', $userIdPengguna)
                                 ->where('section', $selectedSection);

        if ($selectedCategory !== 'All') {
            $query->where('category', $selectedCategory);
        }

        $koleksiPakaian = $query->orderBy('idPakaian', 'desc')->get();

        return view('digital_wardrobe.index', [
            'koleksiPakaian' => $koleksiPakaian,
            'sections' => $sections,
            'categories' => $currentCategories, // Pass the dynamic list of categories
            'selectedSection' => $selectedSection,
            'selectedCategory' => $selectedCategory,
        ]);
    }
    public function create()
    {
        return view('digital_wardrobe.create', [
            'show_details_form' => false, // Awalnya hanya tampilkan form upload foto
            'uploaded_photo_path' => null,
            'uploaded_photo_url' => null,
        ]);
    }

    /**
     * Process the uploaded photo, store it temporarily, and show the details form.
     */
    public function processPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return redirect()->route('digital.wardrobe.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $path = $request->file('photo')->store('temp_uploads', 'public');
            $url = Storage::disk('public')->url($path); // Generate URL

            // ---- TAMBAHKAN INI UNTUK DEBUG ----
            // dd($path, $url); // Hentikan eksekusi dan tampilkan path & URL.
            // Setelah memeriksa, hapus atau komentari baris dd() ini.
            // ---- AKHIR BAGIAN DEBUG ----

            // Simpan path foto di session untuk digunakan jika validasi di tahap detail gagal
            session()->flash('uploaded_photo_path_temp', $path);

            return view('digital_wardrobe.create', [
                'show_details_form' => true,
                'uploaded_photo_path' => $path,
                'uploaded_photo_url' => $url, // Gunakan variabel $url di sini
                'old_input' => [] // Kosongkan old_input untuk form detail yang baru
            ]);
        }

        return redirect()->route('digital.wardrobe.create')
                    ->with('error', 'Photo upload failed. Please try again.')
                    ->withInput();
    }

    /**
     * Store a newly created clothing item in storage.
     */
    public function storeDetails(Request $request)
    {
        // Definisikan kategori yang valid berdasarkan section
        $categoriesForItem = ['Tops', 'Bottoms', 'Footwears', 'Others'];
        $categoriesForOutfit = ['Casual', 'Formal', 'Party', 'Others'];
        $validCategories = [];
        $inputSection = $request->input('section'); // Ambil section dari input

        if ($inputSection === 'Items') {
            $validCategories = $categoriesForItem;
        } elseif ($inputSection === 'Outfits') {
            $validCategories = $categoriesForOutfit;
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'linkItem' => 'nullable|url|max:255',
            'section' => 'required|in:Items,Outfits',
            'category' => 'required|in:' . implode(',', $validCategories),
            'visibility' => 'required|in:Public,Private',
            'uploaded_photo_path' => 'required|string',
        ]);

        if ($validator->fails()) {
            $photoPath = $request->input('uploaded_photo_path', session('uploaded_photo_path_temp'));
            $photoUrl = $photoPath ? Storage::disk('public')->url($photoPath) : null;

            return view('digital_wardrobe.create', [
                'show_details_form' => true,
                'uploaded_photo_path' => $photoPath,
                'uploaded_photo_url' => $photoUrl,
                'errors' => $validator->errors(),
                'old_input' => $request->all()
            ]);
        }

        $tempPath = $request->input('uploaded_photo_path');
        $permanentPath = null;

        if (Storage::disk('public')->exists($tempPath) && str_starts_with($tempPath, 'temp_uploads/')) {
            $fileName = basename($tempPath);
            $permanentPath = 'koleksi_fotos/' . $fileName;
             if (!Storage::disk('public')->exists('koleksi_fotos')) {
                Storage::disk('public')->makeDirectory('koleksi_fotos');
            }
            Storage::disk('public')->move($tempPath, $permanentPath);
        } else {
            return redirect()->route('digital.wardrobe.create')
                ->with('error', 'Processed photo not found. Please start by uploading the photo again.')
                ->withInput($request->except('uploaded_photo_path'));
        }
        
        $userId = Auth::id(); // Atau Auth::user()->idPengguna sesuai konfigurasi Anda

        KoleksiPakaian::create([
            'idPengguna' => $userId,
            'nama' => $request->input('nama'),
            'linkItem' => $request->input('linkItem'),
            'section' => $inputSection, // Gunakan $inputSection yang sudah diambil
            'category' => $request->input('category'),
            'visibility' => $request->input('visibility'),
            'foto' => $permanentPath,
        ]);
        
        session()->forget('uploaded_photo_path_temp');

        // Logika redirect yang disesuaikan
        $redirectParams = [];
        if ($inputSection === 'Outfits') {
            $redirectParams = ['section' => 'Outfits', 'category' => 'All'];
        }
        // Jika section adalah 'Items' atau default, akan redirect ke index default
        // (yang mana defaultnya adalah section 'Items' dan category 'All' berdasarkan method index)
        // atau Anda bisa tambahkan kondisi spesifik jika diperlukan:
        // else if ($inputSection === 'Items') {
        //     $redirectParams = ['section' => 'Items', 'category' => 'All'];
        // }


        return redirect()->route('digital.wardrobe.index', $redirectParams)
                         ->with('success', 'Clothing item added successfully!');
    }

    public function discardTemporaryPhoto(Request $request)
    {
        $tempPath = $request->input('uploaded_photo_path');

        // Validasi bahwa path adalah dari temp_uploads untuk keamanan
        if ($tempPath && Storage::disk('public')->exists($tempPath) && str_starts_with($tempPath, 'temp_uploads/')) {
            Storage::disk('public')->delete($tempPath);
            session()->forget('uploaded_photo_path_temp'); // Hapus juga dari session jika ada
            return redirect()->route('digital.wardrobe.index')->with('status', 'Upload discarded successfully.');
        }

        // Jika path tidak valid atau tidak ada, redirect saja
        return redirect()->route('digital.wardrobe.index');
    }

    public function show(KoleksiPakaian $koleksiPakaian)
    {
        // Pastikan item milik user yang sedang login
        // Sesuaikan Auth::id() dengan PK user Anda jika berbeda (misal Auth::user()->idPengguna)
        if ($koleksiPakaian->idPengguna !== Auth::id()) {
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        return view('digital_wardrobe.show', ['item' => $koleksiPakaian]);
    }

    public function edit(KoleksiPakaian $koleksiPakaian)
    {
        // Pastikan item milik user yang sedang login
        // Sesuaikan Auth::id() dengan PK user Anda jika berbeda (misal Auth::user()->idPengguna)
        if ($koleksiPakaian->idPengguna !== Auth::id()) {
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        // Kirim data item dan old_input (jika ada dari redirect validasi sebelumnya) ke view edit
        return view('digital_wardrobe.edit', [
            'item' => $koleksiPakaian,
            'uploaded_photo_url' => Storage::disk('public')->url($koleksiPakaian->foto), // URL foto yang sudah ada
            'old_input' => session()->getOldInput() // Untuk menangani jika ada redirect back with errors
        ]);
    }

    public function update(Request $request, KoleksiPakaian $koleksiPakaian)
    {
        // Pastikan item milik user yang sedang login
        if ($koleksiPakaian->idPengguna !== Auth::id()) {
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        $categoriesForItem = ['Tops', 'Bottoms', 'Footwears', 'Others'];
        $categoriesForOutfit = ['Casual', 'Formal', 'Party', 'Others'];
        $validCategories = [];
        $inputSection = $request->input('section');

        if ($inputSection === 'Items') {
            $validCategories = $categoriesForItem;
        } elseif ($inputSection === 'Outfits') {
            $validCategories = $categoriesForOutfit;
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'linkItem' => 'nullable|url|max:255',
            'section' => 'required|in:Items,Outfits',
            'category' => 'required|in:' . implode(',', $validCategories),
            'visibility' => 'required|in:Public,Private',
            // Validasi untuk foto baru jika ada (opsional untuk sekarang, fokus pada update detail)
            // 'new_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('digital.wardrobe.edit', $koleksiPakaian->idPakaian)
                        ->withErrors($validator)
                        ->withInput(); // Kirim kembali input lama
        }

        // Update data item
        $koleksiPakaian->nama = $request->input('nama');
        $koleksiPakaian->linkItem = $request->input('linkItem');
        $koleksiPakaian->section = $inputSection;
        $koleksiPakaian->category = $request->input('category');
        $koleksiPakaian->visibility = $request->input('visibility');

        // Logika untuk update foto jika diimplementasikan nanti:
        // if ($request->hasFile('new_photo')) {
        //     // 1. Hapus foto lama dari storage
        //     if ($koleksiPakaian->foto && Storage::disk('public')->exists($koleksiPakaian->foto)) {
        //         Storage::disk('public')->delete($koleksiPakaian->foto);
        //     }
        //     // 2. Simpan foto baru
        //     $newPhotoPath = $request->file('new_photo')->store('koleksi_fotos', 'public');
        //     $koleksiPakaian->foto = $newPhotoPath;
        // }

        $koleksiPakaian->save(); // Menyimpan perubahan

        // Redirect kembali ke halaman detail item atau halaman index
        $redirectParams = [];
        if ($inputSection === 'Outfits') {
            $redirectParams = ['section' => 'Outfits', 'category' => 'All'];
        }

        return redirect()->route('digital.wardrobe.show', $koleksiPakaian->idPakaian) // Redirect ke halaman show
                         ->with('success', 'Item details updated successfully!');
    }
    public function destroy(KoleksiPakaian $koleksiPakaian)
    {
        // Pastikan item milik user yang sedang login
        if ($koleksiPakaian->idPengguna !== Auth::id()) { // Sesuaikan dengan PK user Anda
            abort(403, 'THIS ACTION IS UNAUTHORIZED.');
        }

        $itemName = $koleksiPakaian->nama;
        $itemSection = $koleksiPakaian->section;

        // 1. Hapus file foto dari storage
        if ($koleksiPakaian->foto && Storage::disk('public')->exists($koleksiPakaian->foto)) {
            Storage::disk('public')->delete($koleksiPakaian->foto);
        }

        // 2. Hapus item dari database
        $koleksiPakaian->delete();

        $redirectParams = [];
        if ($itemSection === 'Outfits') {
            $redirectParams = ['section' => 'Outfits', 'category' => 'All'];
        }

        return redirect()->route('digital.wardrobe.index', $redirectParams)
                        ->with('success', "Item '" . $itemName . "' has been successfully deleted.");
    }
}