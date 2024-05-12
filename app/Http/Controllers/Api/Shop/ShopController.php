<?php

namespace App\Http\Controllers\Api\Shop;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShopRequest;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function store(StoreShopRequest $request)
    {
        $store = $request->user()->shops()->create([
            'name' => $request->name,
        ]);

        return response()->json($store, 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $shops = $user->shops;
        return response()->json($shops, 200);
    }
}
