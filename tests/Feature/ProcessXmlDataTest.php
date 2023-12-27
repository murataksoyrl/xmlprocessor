<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessXmlDataTest extends TestCase
{
    use RefreshDatabase;

    public function testProcessXmlCommand()
    {
        // Mock HTTP isteği
        Http::fake([
            'https://www.zzgtech.com/demo_data/products_2022_06_01.xml' => Http::response('<?xml version="1.0" encoding="UTF-8"?>
                <products>
                    <product>
                        <id>1</id>
                        <price>10.5</price>
                        <quantity>100</quantity>
                    </product>
                    <product>
                        <id>2</id>
                        <price>15</price>
                        <quantity>50</quantity>
                    </product>
                </products>'
            )
        ]);

        // Mock loglama
        Log::shouldReceive('error')->andReturnNull();

        // Test komutunu çağır
        $this->artisan('process:xml')
            ->expectsOutput('XML data processed and saved successfully.')
            ->assertExitCode(0);

        // Veritabanındaki ürünleri kontrol et
        $this->assertDatabaseCount('products', 2);
        $this->assertDatabaseHas('products', ['product_id' => 1, 'price' => 10.5, 'quantity' => 100]);
        $this->assertDatabaseHas('products', ['product_id' => 2, 'price' => 15, 'quantity' => 50]);
    }
}
