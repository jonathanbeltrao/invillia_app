<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Services\CustomerService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    private $customerService;
    private $orderService;
    private $orderPayload;
    private $customerPayload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customerService = app(CustomerService::class);
        $this->orderService = app(OrderService::class);

        $this->mockData();
    }

    public function testGetAllCustomers()
    {
        $user = User::create([
            'name' => 'Jonathan',
            'email' => 'jonathan@gmail.com',
            'password' => '123mudar'
        ]);

        Passport::actingAs($user);

        $this->getJson('/api/customers')
            ->assertStatus(200)
            ->assertJson([
                    [
                        "id" => 1,
                        "name" => "Name 1",
                        "orders" => [
                            [
                                "id" => 1,
                                "customer_id" => 1,
                                "address" => [
                                    "id" => 1,
                                    "order_id" => 1,
                                    "name" => "Name 1",
                                    "address" => "Address 1",
                                    "city" => "City 1",
                                    "country" => "Country 1"
                                ],
                                "items" => [
                                    [
                                        "id" => 1,
                                        "order_id" => 1,
                                        "title" => "Title 1",
                                        "note" => "Note 1",
                                        "quantity" => 745,
                                        "price" => 123.45,
                                    ]
                                ]
                            ]
                        ],
                        "phones" => [
                            [
                                "id" => 1,
                                "customer_id" => 1,
                                "number" => "2345678",
                            ],
                            [
                                "id" => 2,
                                "customer_id" => 1,
                                "number" => "1234567"
                            ]
                        ]
                    ],
                    [
                        "id" => 2,
                        "name" => "Name 2",
                        "orders" => [
                            [
                                "id" => 2,
                                "customer_id" => 2,
                                "address" => [
                                    "id" => 2,
                                    "order_id" => 2,
                                    "name" => "Name 2",
                                    "address" => "Address 2",
                                    "city" => "City 2",
                                    "country" => "Country 2",
                                ],
                                "items" => [
                                    [
                                        "id" => 2,
                                        "order_id" => 2,
                                        "title" => "Title 2",
                                        "note" => "Note 2",
                                        "quantity" => 45,
                                        "price" => 13.45,
                                    ]
                                ]
                            ]
                        ],
                        "phones" => [
                            [
                                "id" => 3,
                                "customer_id" => 2,
                                "number" => "4444444",
                            ]
                        ]
                    ],
                    [
                        "id" => 3,
                        "name" => "Name 3",
                        "orders" => [
                            [
                                "id" => 3,
                                "customer_id" => 3,
                                "address" => [
                                    "id" => 3,
                                    "order_id" => 3,
                                    "name" => "Name 3",
                                    "address" => "Address 3",
                                    "city" => "City 3",
                                    "country" => "Country 3",
                                ],
                                "items" => [
                                    [
                                        "id" => 3,
                                        "order_id" => 3,
                                        "title" => "Title 3",
                                        "note" => "Note 3",
                                        "quantity" => 5,
                                        "price" => 1.12
                                    ],
                                    [
                                        "id" => 4,
                                        "order_id" => 3,
                                        "title" => "Title 4",
                                        "note" => "Note 4",
                                        "quantity" => 2,
                                        "price" => 77.12,
                                    ]
                                ]
                            ]
                        ],
                        "phones" => [
                            [
                                "id" => 4,
                                "customer_id" => 3,
                                "number" => "7777777",
                            ],
                            [
                                "id" => 5,
                                "customer_id" => 3,
                                "number" => "8888888",
                            ]
                        ]
                    ]
                ]
            );
    }

    public function testGetCustomer()
    {
        $user = User::create([
            'name' => 'Jonathan',
            'email' => 'jonathan@gmail.com',
            'password' => '123mudar'
        ]);

        Passport::actingAs($user);

        $this->getJson('/api/customer/1')
            ->assertStatus(200)
            ->assertJson(
                [
                    "id" => 1,
                    "name" => "Name 1",
                    "orders" => [
                        [
                            "id" => 1,
                            "customer_id" => 1,
                            "address" => [
                                "id" => 4,
                                "order_id" => 1,
                                "name" => "Name 1",
                                "address" => "Address 1",
                                "city" => "City 1",
                                "country" => "Country 1"
                            ],
                            "items" => [
                                [
                                    "id" => 5,
                                    "order_id" => 1,
                                    "title" => "Title 1",
                                    "note" => "Note 1",
                                    "quantity" => 745,
                                    "price" => 123.45,
                                ]
                            ]
                        ]
                    ],
                    "phones" => [
                        [
                            "id" => 6,
                            "customer_id" => 1,
                            "number" => "2345678",
                        ],
                        [
                            "id" => 7,
                            "customer_id" => 1,
                            "number" => "1234567"
                        ]
                    ]
                ]
            );
    }

    public function mockData()
    {
        $xmlCustomer = json_encode(simplexml_load_string(file_get_contents(__DIR__ . '/../mocks/people.xml')));
        $xmlOrder = json_encode(simplexml_load_string(file_get_contents(__DIR__ . '/../mocks/fixed-shiporders.xml')));

        $this->orderPayload = json_decode($xmlOrder);
        $this->customerPayload = json_decode($xmlCustomer);

        $this->customerService->create($this->customerPayload);
        $this->orderService->create($this->orderPayload);
    }
}
