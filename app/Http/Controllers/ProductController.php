<?php

// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // GoldAPI'den canlı altın fiyatı çek
        $apiKey = config('services.goldapi.key');

        $response = Http::withHeaders([
            'x-access-token' => $apiKey,
            'Content-Type' => 'application/json',
        ])->get('https://www.goldapi.io/api/XAU/USD');

        if ($response->failed()) {
            return response()->json(['error' => 'Altın fiyatı alınamadı'], 500);
        }

        $data = $response->json();
        
        if (!isset($data['price'])) {
            return response()->json(['error' => 'Altın fiyatı bulunamadı'], 500);
        }

        $goldPricePerGram = $data['price'] / 31.1035;

        $json = file_get_contents(public_path('products.json'));
        $products = json_decode($json, true);

        foreach ($products as &$product) {
            $price = ($product['popularityScore'] + 1) * $product['weight'] * $goldPricePerGram;
            $product['price'] = round($price, 2);
            $product['rating'] = round($product['popularityScore'] * 5, 1);
        }

        return response()->json($products);
    }
}
