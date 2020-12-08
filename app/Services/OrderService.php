<?php


namespace App\Services;

use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function create($data)
    {
        try {
            DB::beginTransaction();
            foreach ($data->shiporder as $order) {
                Log::info($order->orderid);
                if (!Order::find($order->orderid)) {
                    Order::create([
                        'id' => $order->orderid,
                        'customer_id' => $order->orderperson,
                    ]);

                    OrderAddress::create([
                        'order_id' => $order->orderid,
                        'name' => $order->shipto->name,
                        'address' => $order->shipto->address,
                        'city' => $order->shipto->city,
                        'country' => $order->shipto->country
                    ]);

                    if (is_array($order->items->item)) {
                        foreach ($order->items->item as $item) {
                            OrderItems::create([
                                'order_id' => $order->orderid,
                                'title' => $item->title,
                                'note' => $item->note,
                                'quantity' => $item->quantity,
                                'price' => $item->price,
                            ]);
                        }
                    } else {
                        OrderItems::create([
                            'order_id' => $order->orderid,
                            'title' => $order->items->item->title,
                            'note' => $order->items->item->note,
                            'quantity' => $order->items->item->quantity,
                            'price' => $order->items->item->price,
                        ]);
                    }
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::info($e->getTraceAsString());
            DB::rollBack();
            return false;
        }
    }

    public function all()
    {
        return Order::all()->load(['items', 'address']);
    }
}
