<?php

use App\Models\NotifyMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('messages', function () {
    return NotifyMessage::where('status', 'pending')->get();
});

Route::post('message/update', function (Request $request) {
    $ids = explode(',', $request->ids);
    $ids = array_filter($ids);
    Log::info($ids);

    foreach ($ids as $id) {
        NotifyMessage::find($id)->update([
            'status' => 'SENT',
        ]);
    }

    return response()->json(['success' => true], 200);
});
