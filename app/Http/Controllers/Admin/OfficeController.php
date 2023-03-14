<?php

namespace App\Http\Controllers\Admin;

use App\Models\Office;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OfficeStoreRequest;
use App\Http\Requests\OfficeUpdateRequest;
use DB;
use DataTables;

class OfficeController extends Controller
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
    public function index()
    {

        return view('admin.office.index');
    }



    public function indexOfficeData()
    {
        if (request()->ajax()) {
            $offices = Office::get();
            return Datatables::of($offices)
            ->addColumn('code', function($row){
                $data = $row->code;
                    return $data;
            })
            ->addColumn('description', function($row){
                $data = $row->description;
                    return $data;
            })
            ->addColumn('date_created', function($row){
                $data = $row->created_at;
                    return $data;
            })
            ->addColumn('date_updated', function($row){
                $data = $row->updated_at;
                    return $data;
            })
            ->addColumn('action', function($row){
                $data = $row->code;
                    return $data;
            })
                    ->make(true);
        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $latestCode = DB::table('offices')->max('code') + 1;
        return view('admin.office.create', compact('latestCode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfficeStoreRequest $request)
    {
        $office = Office::create([
            'code'        => $request->code,
            'description' => $request->description,
            // 'shortname'  => Str::upper($request->shortname),
        ]);

        return redirect()->route('admin.office.index')->with('success', $office->description . ' was successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        $office = Office::find($code);
        return view('admin.office.edit', compact('office'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function update(OfficeUpdateRequest $request, Office $office)
    {
        $office->code = $request->code;
        $office->description = $request->description;
        // $office->shortname = $request->shortname;
        $office->save();

        return redirect()->route('admin.office.index')->with('success', 'Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Office  $office
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $office = Office::find($code);
        $office->delete();
        return response('success');
    }
}
