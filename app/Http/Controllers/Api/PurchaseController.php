<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function buy($course_id)
    {
        $user = Auth::user();
        $alreadyBought = Purchase::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->exists();

        if ($alreadyBought) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki kursus ini.'
            ], 400);
        }

        Purchase::create([
            'user_id' => $user->id,
            'course_id' => $course_id,
            'status' => 'success'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembelian berhasil! Selamat belajar.'
        ]);
    }
}