<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentTrackController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth:admin');
    }

    public function index()
    {
        $documents = UserService::get()->unique('tracking_number');
        return view('admin.trackings.document.index', compact('documents'));
    }

    public function show(string $transactionCode, string $documentID)
    {
        $service = Service::with(['process'])->find($documentID);

        $logs = UserService::with(['receiver', 'receiver.user', 'forwarded_by_user', 'forwarded_to_user'])
            ->where('stage', 'passed')->orWhere('stage', 'current')->where('tracking_number', $transactionCode)->orderBy('id', 'ASC')->get()->filter(function ($row) use ($transactionCode) {
                return $row->tracking_number == $transactionCode;
            });

        $serviceName = $service->name;
        $description = $service->description;
        $process = $service->process;

        return view('admin.trackings.document.show', compact('service', 'transactionCode', 'description', 'serviceName', 'process', 'logs'));
    }
}
