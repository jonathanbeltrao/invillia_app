<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class XmlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testXmlUpload()
    {
        $user = User::create([
            'name' => 'Jonathan',
            'email' => 'jonathan@gmail.com',
            'password' => '123mudar'
        ]);

        Storage::fake('public');

        $customerXml = File::create('people.xml', 800);
        $orderXml = File::create('order.xml', 800);

        $this->actingAs($user)
            ->postJson('/api/upload', [
                'customer_xml' => $customerXml,
                'order_xml' => $orderXml,
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function testFailXmlUploadMissingFiles()
    {
        $user = User::create([
            'name' => 'Jonathan',
            'email' => 'jonathan@gmail.com',
            'password' => '123mudar'
        ]);

        Storage::fake('public');

        $customerXml = File::create('people.xml', 800);
        $orderXml = File::create('order.xml', 800);

        $this->actingAs($user)
            ->postJson('/api/upload', [
                'customers_xml' => $customerXml,
                'orders_xml' => $orderXml,
            ])
            ->assertStatus(422)
            ->assertJson([
                'customer_xml' => ['The customer xml field is required.'],
                'order_xml' => ['The order xml field is required.']
            ]);
    }

    public function testFailXmlUploadMissingFileType()
    {
        $user = User::create([
            'name' => 'Jonathan',
            'email' => 'jonathan@gmail.com',
            'password' => '123mudar'
        ]);

        Storage::fake('public');

        $customerXml = File::create('people.jpg', 800);
        $orderXml = File::create('order.xml', 800);

        $response = $this->actingAs($user)
            ->postJson('/api/upload', [
                'customer_xml' => $customerXml,
                'order_xml' => $orderXml,
            ])
            ->assertStatus(422)
            ->assertJson([
                'customer_xml' => ['The customer xml must be a file of type: application/xml, xml.'],
            ]);
    }
}
