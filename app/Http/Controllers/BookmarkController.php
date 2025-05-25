<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function bookmark()
    {
        return view('settings.bookmark');
    }

    /**
     * Menampilkan halaman detail untuk fashion item tertentu yang dibookmark.
     *
     * @param  int  $id ID dari fashion item
     * @return \Illuminate\View\View
     */
    public function showItem($id) // Metode ini sudah benar
    {
        // Di sini Anda akan mengambil data item dari database berdasarkan $id.
        // Untuk contoh ini, saya akan menggunakan data dummy.
        $item = $this->getDummyItemData($id);

        // Jika item tidak ditemukan, Anda bisa mengarahkan ke halaman 404 atau daftar item
        if (!$item) {
            abort(404, 'Item not found.');
        }

        // MENGUBAH NAMA VIEW DI SINI
        return view('settings.itemfashion', compact('item')); // <--- UBAH DARI item_detail MENJADI itemfashion
    }

    /**
     * Fungsi dummy untuk mendapatkan data item.
     * Dalam aplikasi nyata, ini akan berinteraksi dengan database (Eloquent).
     * @param int $id
     * @return array|null
     */
    private function getDummyItemData($id)
    {
        $items = [
            1 => [
                'name' => 'Forest Green Jacket',
                'image' => asset('greenjacket.png'), // Pastikan path ini benar di public folder
                'description' => 'Sometimes all you need is a green jacket and the evening breeze to feel grounded. Never thought this color would grow on me—but it\'s calm, fresh, and lifts the mood just right.',
                'hashtags' => ['#Outer', '#ChicStyle', '#Minimalist', '#GreenJacket', '#Timeless'],
                'user' => 'Blair',
                'user_avatar' => 'https://via.placeholder.com/30x30/FFA500/FFFFFF?text=B', // Placeholder for user avatar
            ],
            2 => [
                'name' => 'Brown Plaid Skirt',
                'image' => asset('skirt.png'),
                'description' => 'A versatile brown plaid skirt, perfect for a casual day out or a stylish evening.',
                'hashtags' => ['#Skirt', '#Plaid', '#CasualChic'],
                'user' => 'Jane Doe',
                'user_avatar' => 'https://via.placeholder.com/30x30/800080/FFFFFF?text=J',
            ],
            // Tambahkan data dummy untuk item lain sesuai dengan yang ada di grid Anda
            3 => [
                'name' => 'Olive Cargo Shorts',
                'image' => asset('cargoshorts.png'),
                'description' => 'Comfortable and trendy olive cargo shorts. A must-have for your summer wardrobe.',
                'hashtags' => ['#Shorts', '#Cargo', '#Summer'],
                'user' => 'Alex',
                'user_avatar' => 'https://via.placeholder.com/30x30/008080/FFFFFF?text=A',
            ],
            4 => [
                'name' => 'Blue Sneakers',
                'image' => asset('bluesneakers.png'),
                'description' => 'Classic blue high-top sneakers, perfect for any casual outfit.',
                'hashtags' => ['#Sneakers', '#Shoes', '#Casual'],
                'user' => 'Chris',
                'user_avatar' => 'https://via.placeholder.com/30x30/0000FF/FFFFFF?text=C',
            ],
            // ... dan seterusnya untuk item lainnya
        ];

        return $items[$id] ?? null;
    }
}
