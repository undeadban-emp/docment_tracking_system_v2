<?php

namespace App\Http\Controllers;

use App\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\DB;
class UserLogController extends Controller
{
    public function index()
    {
        $userID = Auth::user()->id;
        $userRole = Auth::user()->role;
        return view('user.logs',compact('userID','userRole'));
    }

    public function listChecker()
    {
        $userID = Auth::user()->id;
        $userRole = Auth::user()->role;
        if (request()->ajax()) {
            $logs = UserService::with(['forwarded_to_user', 'forwarded_by_user', 'avail_by', 'returnee'])->where('stage', 'passed')->orWhere('stage', 'current')->orWhere('stage', 'disapproved')->get()->filter(function ($row)  use($userID) {
                if($row->status == 'pending') {
                    return $row->forwarded_by == $userID;
                } elseif($row->status == 'received') {
                    return $row->received_by == $userID;
                } else if($row->status == 'forwarded') {
                    return $row->forwarded_by == $userID;
                } else if($row->status == 'disapproved') {
                    return $row->returned_by == $userID;
                } else if($row->status == 'last') {
                    return $row->forwarded_by == $userID;
                }
            });
            return Datatables::of($logs)
                    ->addColumn('tracking_number', function($row){
                        $tracking_number = $row->tracking_number;
                        return $tracking_number;
                    })
                    ->addColumn('office', function($row){
                        $office = $row->avail_by->userOffice->description;
                        return $office;
                    })
                    ->addColumn('service', function($row){
                        $service = $row->information->name;
                        return $service;
                    })
                    ->addColumn('forwarded_by_user', function($row){
                        $service = $row->forwarded_by_user;
                        return $service;
                    })
                    ->addColumn('date', function($row){
                        $date = $row->updated_at;
                        return $date;
                    })
                    ->make(true);
        }
    }
    public function listLiaison()
    {
        $userID = Auth::user()->id;
        if (request()->ajax()) {
            $documents = DB::table('user_service')->where('user_id', $userID)
                ->leftJoin('services', 'services.id', '=', 'user_service.service_id')
                ->select('user_service.*', 'services.id as service_id', 'services.name', 'services.description')
                ->where('status', 'last')
                ->where('stage', 'passed')
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
            ->addColumn('date_created', function($row){
                $data = $row[0]->created_at;
                    return $data;
            })
            ->addColumn('date_finish', function($row){
                $data = $row[0]->updated_at;
                    return $data;
            })
            ->addColumn('action', function($row){
                $data = $row;
                    return $data;
            })
                    ->make(true);
        }
    }
}
