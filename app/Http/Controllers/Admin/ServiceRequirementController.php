<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequirementStoreRequest;
use App\Models\Requirement;
use App\Models\Service;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class ServiceRequirementController extends Controller
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
        if ($request->has('service_id')) {
            $service = Service::with('requirements')->find($request->service_id);
            return view('admin.service.requirement.index', compact('service'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->has('service_id')) {
            $service = Service::with('requirements')->find($request->service_id);
            return view('admin.service.requirement.create', compact('service'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequirementStoreRequest $request)
    {

        $request->validate([
            'description' => 'required'
        ]);

        if ($request->has('service_id')) {
            $service = Service::with('requirements')->find($request->service_id);

            $service->requirements()->create([
                'description'               =>          $request->description,
                'where_to_secure'           =>          $request->where_to_secure,
                'is_required'               => ($request->has('is_required')) ? 1 : 0,
            ]);

            return redirect()->route('admin.service-requirements.index', ['service_id'    =>  $service->id])->with('success', 'Requirement was added successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function show(Requirement $requirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if ($request->has('service_id')) {
            $requirement  = Requirement::find($id);
            $service = Service::with('requirements')->find($request->service_id);
            return view('admin.service.requirement.edit', compact('requirement', 'service'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requirement  = Requirement::find($id);
        $requirement->description = $request->description;
        $requirement->where_to_secure = $request->where_to_secure;
        if ($request->has('is_required')) {
            $requirement->is_required = 1;
        } else {
            $requirement->is_required = 0;
        }
        $requirement->save();

        return redirect()->route('admin.service-requirements.index', ['service_id'    =>  $request->service_id])->with('success', 'Requirement was updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Requirement  $requirement
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $requirementID, Request $request)
    {
        $requirement = Requirement::find($requirementID);
        $requirement->delete();
        return redirect()->back()->with('success', 'Requirement was deleted successfully');
    }
}
