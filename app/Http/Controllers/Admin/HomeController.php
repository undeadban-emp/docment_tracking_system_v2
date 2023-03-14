<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $users = User::with(['userOffice', 'userPosition'])->where('status', 'pending')->get();
        return view('admin.home', compact('users'));
    }

    public function indexData()
    {
        if (request()->ajax()) {
            $users = User::where('status', 'pending')->get();
            return Datatables::of($users)
            ->addColumn('fullname', function($row){
                $data = $row->fullname;
                    return $data;
            })
            ->addColumn('username', function($row){
                $data = $row->username;
                    return $data;
            })
            ->addColumn('phone_number', function($row){
                $data = $row->phone_number;
                    return $data;
            })
            ->addColumn('position', function($row){
                $data = $row->userPosition->position_name;
                    return $data;
            })
            ->addColumn('office', function($row){
                $data = $row->userOffice->description;
                    return $data;
            })
            ->addColumn('status', function($row){
                $data = $row->status;
                    return $data;
            })
            ->addColumn('date_register', function($row){
                $data = $row->created_at;
                    return $data;
            })
            ->addColumn('action', function($row){
                $data = $row->id;
                    return $data;
            })
                    ->make(true);
        }
    }

}
