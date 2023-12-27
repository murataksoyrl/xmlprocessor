<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;


class ProcessXmlData extends Command
{
    protected $signature = 'process:xml';
    protected $description = 'Downloads and processes XML data from a specific URL';

    public function handle()
    {
        $url = env('APP_XML_URL');

        try {
            $xmlData = Http::get($url)->body();
            $xml = simplexml_load_string($xmlData);

            $productIds = collect($xml->product)->pluck('id')->toArray();

            $existingProducts = Product::whereIn('product_id', $productIds)->get();

            foreach ($xml->product as $product) {
                $productId = (string) $product->id;
                $price = (float) $product->price;
                $quantity = (int) $product->quantity;

                $existingProduct = $existingProducts->where('product_id', $productId)->first();

                if ($existingProduct) {
                    if ($existingProduct->price != $price || $existingProduct->quantity != $quantity) {
                        $existingProduct->price = $price;
                        $existingProduct->quantity = $quantity;
                        $existingProduct->save();
                    }
                } else {
                    Product::create([
                        'product_id' => $productId,
                        'price' => $price,
                        'quantity' => $quantity,
                    ]);
                }
            }

            $existingProducts->whereNotIn('product_id', $productIds)->each(function ($product) {
                $product->delete();
            });

            $this->info('XML data processed and saved successfully.');
        } catch (\Exception $e) {
            // Hata durumunda loglama
            Log::error('XML processing failed: ' . $e->getMessage());
            $this->error('XML processing failed. Check the logs for more details.');
        }
    }
}
