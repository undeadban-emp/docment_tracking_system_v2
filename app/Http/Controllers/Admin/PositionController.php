<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Http\Requests\PositionStoreRequest;
use App\Http\Requests\PositionUpdateRequest;
use Illuminate\Http\Request;
use DataTables;

class PositionController extends Controller
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
        $positions = Position::get();
        return view('admin.position.index');
    }
    public function indexPositionData()
    {
        if (request()->ajax()) {
            $positions = Position::get();
            return Datatables::of($positions)
            ->addColumn('code', function($row){
                $data = $row->code;
                    return $data;
            })
            ->addColumn('name', function($row){
                $data = $row->position_name;
                    return $data;
            })
            ->addColumn('short_name', function($row){
                $data = $row->position_short_name;
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
        $position = Position::count();
        return view('admin.position.create', [
            'position' => $position,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionStoreRequest $request)
    {
        Position::create([
            'code'                  =>      $request->code,
            'position_name'         =>      $request->position_name,
            'position_short_name'   =>      $request->position_short_name,
        ]);

        return redirect()->route('admin.position.index')->with('success' , 'Position was successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit($code)
    {
        $position = Position::find($code);
        return  view('admin.position.edit', compact('position'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(PositionUpdateRequest $request, Position $position)
    {
        $position->code = $request->code;
        $position->position_name = $request->position_name;
        $position->position_short_name = $request->position_short_name;
        $position->save();

        return redirect()->route('admin.position.index')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        $position = Position::find($code);
        $position->delete();
        return response('success');
    }
}
