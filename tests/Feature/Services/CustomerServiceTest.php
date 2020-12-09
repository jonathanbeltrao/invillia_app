<?php

namespace Tests\Feature\Services;

use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerServiceTest extends TestCase
{
    use RefreshDatabase;

    private $service;
    private $payload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CustomerService::class);
        $xml = json_encode(simplexml_load_string(file_get_contents(__DIR__.'/../mocks/people.xml')));
        $this->payload = json_decode($xml);
    }

    public function testCreateCustomer()
    {
        $status = $this->service->create($this->payload);
        $customers = $this->service->all();

        $this->assertTrue($status);
        $this->assertCount(3, $customers);
        $this->assertEquals(1, $customers[0]->id);
        $this->assertEquals('Name 1', $customers[0]->name);
        $this->assertEquals(2, $customers[1]->id);
        $this->assertEquals('Name 2', $customers[1]->name);
        $this->assertEquals(3, $customers[2]->id);
        $this->assertEquals('Name 3', $customers[2]->name);

        $this->assertCount(2, $customers[0]->phones);
        $this->assertCount(1, $customers[1]->phones);
        $this->assertCount(2, $customers[2]->phones);
    }

    public function testAvoidingDuplicateCustomer()
    {
        $this->service->create($this->payload);
        $this->service->create($this->payload);

        $customers = $this->service->all();

        $this->assertCount(3, $customers);
    }

    public function testNotCreatingCustomer()
    {
        $status = $this->service->create(['teste']);
        $this->assertFalse($status);
    }

    public function testGettingCustomerById()
    {
        $this->service->create($this->payload);
        $customer = $this->service->get(1);

        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertEquals('Name 1', $customer->name);

        $this->assertCount(2, $customer->phones);
        $this->assertEquals('2345678', $customer->phones[0]->number);
        $this->assertEquals('1234567', $customer->phones[1]->number);
    }
}
