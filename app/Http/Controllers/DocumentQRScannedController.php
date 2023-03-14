<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentQRScannedController extends Controller
{
    public function index(string $transactionCode)
    {
        $document = UserService::where('tracking_number', $transactionCode)->first();
        if(Auth::user()->id == $document?->user_id) {
            $userID = Auth::user()->id;
            $service = Service::with(['process'])->find($document->service_id);

        $logs = UserService::with(['receiver', 'receiver.user', 'forwarded_by_user', 'forwarded_to_user'])
                        ->where('stage', 'passed')->orWhere('stage', 'current')->where('tracking_number', $transactionCode)->orderBy('id', 'ASC')->get()->filter(function ($row) use($transactionCode) {
                            return $row->tracking_number == $transactionCode;
                        }) ;
        $serviceName = $service->name;
        $description = $service->description;
        $process = $service->process;

        return view('user.documents.show', compact('service', 'serviceName', 'userID', 'transactionCode', 'description', 'process', 'logs'));
        } else {
            return redirect()->to(route('service.received', $transactionCode));
        }
    }
}
