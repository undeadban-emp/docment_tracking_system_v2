<?php

namespace App\Http\Controllers\admin;

use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Office;
use App\Models\Upload;
use App\Models\Service;
use App\Models\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ListOfTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $office = Office::get();
        return view('admin.list-of-transaction.index', compact('office'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

        public function listOfTransaction(string $officeCode = "*", $status)
        {
            if($status == 'process'){
                $documents = DB::table('user_service')
                ->leftJoin('services', 'services.id', '=', 'user_service.service_id')
                ->leftJoin('users', 'users.id', '=', 'user_service.user_id')
                ->leftJoin('offices', 'offices.code', '=', 'users.office')
                ->where('user_service.status', '!=', 'last')
                ->where('user_service.stage', '!=','passed');
            }else if($status == 'finished'){
                $documents = DB::table('user_service')
                ->leftJoin('services', 'services.id', '=', 'user_service.service_id')
                ->leftJoin('users', 'users.id', '=', 'user_service.user_id')
                ->leftJoin('offices', 'offices.code', '=', 'users.office')
                ->where('user_service.status', 'last')
                ->where('user_service.stage', 'passed');
            }

            if (request()->ajax()) {
                $listTransactionData = ($officeCode != '*') ? $documents->where('users.office', $officeCode)->get()->groupBy('tracking_number')
                : $documents->get()->groupBy('tracking_number');
                return Datatables::of($listTransactionData)
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
                ->addColumn('office', function($row){
                    $data = $row;
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


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($transactionCode, $documentID)
    {
        $userID = Auth::user()->id;
        $service = Service::with(['process'])->find($documentID);
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
            foreach($logDatetime as $key => $log){
                if($key == 0){
                    $previous_date = $log->updated_at;
                    if($count == 1){
                        array_push($resultAll, '');
                    }
                }else{
                    $dateConverted = Carbon::parse($log->updated_at);
                    $dateConverted2 = Carbon::parse($previous_date);
                    $diffSeconds = ($dateConverted)->diffInSeconds($dateConverted2);
                    array_push($result, $diffSeconds);
                    if($count == $key + 1){
                        foreach($result as $results){
                            $totalSec = $totalSec + $results;
                        }
                        array_push($resultAll, $totalSec);
                    }
                    $previous_date = $log->updated_at;
                }
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
        return view('admin.list-of-transaction.show', compact('service',  'userID','resultAll', 'transactionCode', 'description', 'serviceName', 'process', 'logs', 'check', 'logTime'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($trackingNumber)
    {
        UserService::where('tracking_number', $trackingNumber)->delete();
        Upload::where('transaction_code', $trackingNumber)->get()->each->delete();
        return response('success');
    }
}
