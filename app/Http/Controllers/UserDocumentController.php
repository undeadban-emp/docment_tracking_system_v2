<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use Hashids\Hashids;
use App\Models\Upload;
use App\Models\Service;
use App\Models\UserService;
use Illuminate\Http\Request;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserDocumentController extends Controller
{
    public function index()
    {
        return view('user.documents.index');
    }

    public function listMyDocs()
    {
        $userID = Auth::user()->id;
        if (request()->ajax()) {
            $documents = DB::table('user_service')->where('user_id', $userID)
            ->leftJoin('services', 'services.id', '=', 'user_service.service_id')
            ->select('user_service.*', 'services.id as service_id', 'services.name', 'services.description')
            ->where('status', '!=', 'last')
            ->where('stage', '!=','passed')
            ->get()
            ->groupBy('tracking_number');
            return Datatables::of($documents)
            ->addColumn('tracking_number', function($row){
                $data = $row[0]->tracking_number;
                return $data;
            })
            ->addColumn('name', function($row){
                $data = $row[0]->name;
                    return $data;
            })
            ->addColumn('description', function($row){
                $data = $row[0]->request_description;
                    return $data;
            })
            ->addColumn('updated_at', function($row){
                $data = $row[0]->updated_at;
                    return $data;
            })
            ->addColumn('action', function($row){
                $data = $row;
                    return $data;
            })
            ->setRowAttr([
                'style' => function($documents){
                    $data = $documents->count();
                    for($i=0; $i < $data; $i++){
                        if($documents[$i]->status == 'disapproved' && $documents[$i]->stage == 'current'){
                            return 'background-color: red; color:white;';
                        }
                    }
                }
            ])
            ->make(true);
        }
    }

    public function show($transactionCode, $documentID)
    {
        $userID = Auth::user()->id;
        $service = Service::with(['process'])->find($documentID);
        // UserService::with(['receiver','receiver.user','forwarded_by_user','forwarded_to_user'])->where('stage','passed')->orWhere('stage','current')->where('tracking_number','SOD-I1125202271')->orderBy('id','ASC')->get()->filter(function($row){return $row->tracking_number=='SOD-I1125202271';});
        $logs = UserService::with(['receiver', 'receiver.user', 'forwarded_by_user', 'forwarded_to_user'])
            ->where('stage', 'passed')->orWhere('stage', 'current')->where('tracking_number', $transactionCode)->orderBy('updated_at', 'ASC')->get()->filter(function ($row) use ($transactionCode) {
                return $row->tracking_number == $transactionCode;
            });

            $logTime = UserService::with(['receiver', 'receiver.user', 'forwarded_by_user', 'forwarded_to_user'])
            ->where(function ($query) {
                $query
                    ->where('status', '!=','pending')
                    ->where('stage', 'passed')
                    ->orWhere('stage', 'current');
            })
            ->where('tracking_number', $transactionCode)
            ->orderBy('updated_at', 'ASC')
            ->get(['tracking_number','status', 'updated_at', 'service_index'])->groupBy('service_index');

        $resultAll = [];
        $asd = '';
        foreach($logTime as $keys => $logDatetime){
            $previous_date = '';
            $result = [];
            $count = count($logDatetime);
            $totalSec = 0;
            $keynodisapproved = '';
            foreach($logDatetime as $key => $log){
                if($key == 0){
                    $previous_date = $log->updated_at;
                    if($count == 1){
                        array_push($resultAll, '');
                    }
                }else{
                    if($log->status == 'returned'){
                        $keynodisapproved = $key + 1;
                    }else if($keynodisapproved == $key){
                        $keynodisapproved = '';
                    }else{
                        $dateConverted = Carbon::parse($log->updated_at);
                        $dateConverted2 = Carbon::parse($previous_date);
                        $diffSeconds = ($dateConverted)->diffInSeconds($dateConverted2);
                        array_push($result, $diffSeconds);
                    }
                    if($count == $key + 1){
                        foreach($result as $results){
                            $totalSec = $totalSec + $results;
                        }
                        array_push($resultAll, $totalSec);
                    }
                    $previous_date = $log->updated_at;
                }
                // if($keys == 2 && $key == 0){
                //     $asd = $result;
                // }
            }
        }
        $serviceName = $service->name;
        $description = $service->description;
        $process = $service->process;

        $check = UserService::select('tracking_number', 'service_id','status','stage')
        ->when($transactionCode, function ($query) use ($transactionCode) {
            return $query->where('tracking_number', $transactionCode);
        })
        ->when($documentID, function ($query) use ($documentID) {
            return $query->where('service_id', $documentID);
        })
        ->where('status', 'last')
        ->where('stage', 'passed')
        ->first();
        return view('user.documents.show', compact('service',  'resultAll', 'userID', 'transactionCode', 'description', 'serviceName', 'process', 'logs', 'check', 'logTime'));
    }

    public function edit(string $trackingNumber)
    {
        $service = UserService::with(['information', 'information.process', 'information.requirements'])->where('tracking_number', $trackingNumber)->where('stage', 'current')->first();
        $attachments = Upload::where('transaction_code', $trackingNumber)->get();
        return view('user.documents.edit', compact('service', 'attachments'));
    }

    public function delete(string $trackingNumber)
    {
        $data = UserService::where('tracking_number', $trackingNumber)->delete();

        Upload::where('transaction_code', $trackingNumber)->get()->each->delete();
        // return back()->with('success', 'Successfully cancel all the process of your document');
        return response('success');
    }
}
