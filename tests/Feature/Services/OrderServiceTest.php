<?php

namespace Tests\Feature\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Services\CustomerService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    private $customerService;
    private $orderService;
    private $orderPayload;
    private $customerPayload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = app(OrderService::class);
        $this->customerService = app(CustomerService::class);

        $xmlCustomer = json_encode(simplexml_load_string(file_get_contents(__DIR__.'/../mocks/people.xml')));
        $xmlOrder = json_encode(simplexml_load_string(file_get_contents(__DIR__.'/../mocks/fixed-shiporders.xml')));
        $this->orderPayload = json_decode($xmlOrder);
        $this->customerPayload = json_decode($xmlCustomer);
    }

    public function testCreateOrder()
    {
        $this->customerService->create($this->customerPayload);
        $status = $this->orderService->create($this->orderPayload);

        $orders = $this->orderService->all();

        $this->assertTrue($status);
        $this->assertCount(3, $orders);

        $i = 0;
        foreach($this->orderPayload->shiporder as $order) {
            $this->assertEquals($order->orderid, $orders[$i]->id);
            $this->assertEquals($order->orderperson, $orders[$i]->customer_id);

            $this->assertEquals($order->shipto->name, $orders[$i]->address->name);
            $this->assertEquals($order->shipto->address, $orders[$i]->address->address);
            $this->assertEquals($order->shipto->city, $orders[$i]->address->city);
            $this->assertEquals($order->shipto->country, $orders[$i]->address->country);

            if(is_array($order->items->item)) {
                $j = 0;
                $this->assertCount(count($order->items->item), $orders[$i]->items);
                foreach($order->items->item as $item) {
                    $this->assertEquals($item->title, $orders[$i]->items[$j]->title);
                    $this->assertEquals($item->note, $orders[$i]->items[$j]->note);
                    $this->assertEquals($item->quantity, $orders[$i]->items[$j]->quantity);
                    $this->assertEquals($item->price, $orders[$i]->items[$j]->price);

                    $j++;
                }
            } else {
                $this->assertEquals($order->items->item->title, $orders[$i]->items[0]->title);
                $this->assertEquals($order->items->item->note, $orders[$i]->items[0]->note);
                $this->assertEquals($order->items->item->quantity, $orders[$i]->items[0]->quantity);
                $this->assertEquals($order->items->item->price, $orders[$i]->items[0]->price);
            }

            $i++;
        }
    }

    public function testAvoidingDuplicateOrder()
    {
        $this->customerService->create($this->customerPayload);

        $this->orderService->create($this->orderPayload);
        $this->orderService->create($this->orderPayload);

        $orders = $this->orderService->all();

        $this->assertCount(3, $orders);
    }

    public function testNotCreatingOrder()
    {
        $status = $this->orderService->create($this->orderPayload);
        $this->assertFalse($status);
    }

    public function testGettingOrderById()
    {
        $this->customerService->create($this->customerPayload);
        $this->orderService->create($this->orderPayload);

        $order = $this->orderService->get(1);


        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(1, $order->id);
        $this->assertEquals(1, $order->customer_id);

        $this->assertEquals('Name 1', $order->address->name);
        $this->assertEquals('Address 1', $order->address->address);
        $this->assertEquals('City 1', $order->address->city);
        $this->assertEquals('Country 1', $order->address->country);

        $this->assertCount(1, $order->items);
        $this->assertEquals('Title 1', $order->items[0]->title);
        $this->assertEquals('Note 1', $order->items[0]->note);
        $this->assertEquals(745, $order->items[0]->quantity);
        $this->assertEquals(123.45, $order->items[0]->price);
    }
}
