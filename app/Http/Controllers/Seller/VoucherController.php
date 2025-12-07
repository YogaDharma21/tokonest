<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\ResponseFormatter;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = auth()->user()->vouchers();

        if (request()->search) {
            $query->where('name', 'LIKE', '%' . request()->search . '%');
        }

        if (request()->status) {
            if (request()->status == 'ongoing') {
                $query->where('start_date', '<=', now())->where('end_date', '>=', now());
            } elseif (request()->status == 'coming_soon') {
                $query->where('start_date', '>', now());
            } elseif (request()->status == 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        $vouchers = $query->paginate(request()->per_page ?? 10);

        return ResponseFormatter::success($vouchers->through(function ($voucher) {
            return $voucher->api_response_seller;
        }));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
