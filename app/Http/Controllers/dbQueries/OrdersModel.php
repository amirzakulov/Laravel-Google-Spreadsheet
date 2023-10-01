<?php

namespace App\Http\Controllers\dbQueries;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrdersModel extends Controller
{
    public function getOrders()
    {
        return DB::table("orders as o")
            ->select("o.*")
            ->orderBy("o.created_at", 'desc')
            ->get();
    }

    public function getOrder($id)
    {
        return DB::table("orders as o")
            ->where("o.id", $id)
            ->first();
    }

    public function add($arr) {
        $order = Order::create($arr);

        return $this->getOrder($order->id);
    }

    public function update($id, $arr) {
        Order::where('id', $id)->update($arr);

        return $this->getOrder($id);
    }

//    public function delete($id)
//    {
//        return Client::where('id', $id)->delete();
//    }
}
