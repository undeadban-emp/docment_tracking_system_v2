<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Office;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ServiceProcess;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceProcessStoreRequest;
use App\Models\UserService;
use App\Models\ManagerUserCount;
use App\Models\ManagerUser;
use Carbon\Carbon;

class ServiceProcessController extends Controller
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
        $service = Service::with('process')->find($request->id);
        return view('admin.service.process.index', compact('service'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $service = Service::findOrFail($request->service_id);
        $users = User::where('role', 'checker')->get();
        $offices = Office::get();
        return view('admin.service.process.create', compact('service', 'users', 'offices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceProcessStoreRequest $request)
    {
        if ($request->has('service_id')) {
            $manager_id = tap(ManagerUserCount::where('name', 'count')->first())->increment('value', 1)->value;
            $user = $request->additional_responsible;
            $service = Service::find($request->service_id);
            ServiceProcess::create([
                'code'             => $service->service_process_id,
                'action'           => $request->action,
                'location'         => $request->office,
                'responsible_user' => $request->responsible_user,
                'responsible'      => $request->responsible,
                'fees_to_paid'     => 0,
                'manager_id'       => $manager_id,
                // 'manager_id'       => $request->additional_responsible,
            ]);
            if($user == null){
                ManagerUser::create([
                    'manager_id' => $manager_id,
                    'user_id' => 0,
                ]);
            }else{
                foreach($user as $users){
                    ManagerUser::create([
                        'manager_id' => $manager_id,
                        'user_id' => $users,
                    ]);
                }
            }
           
            return redirect()->route('admin.service-process.index', ['id' => $service->id])->with('success', 'Process was successfully added to ' . $service->name);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceProcess  $serviceProcess
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceProcess $serviceProcess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceProcess  $serviceProcess
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceProcess $serviceProcess)
    {
        // dd($serviceProcess);
        $users = User::with('manager_user')->where('office', $serviceProcess->user->userOffice->code)->where('status', 'approved')->get();
        $usersAddition = User::with('manager_user')->where('id', '!=' , $serviceProcess->responsible_user)->where('office', $serviceProcess->user->userOffice->code)->where('status', 'approved')->get();
        $user = User::where('status', 'approved')->get();
        $offices = Office::get();
        return view('admin.service.process.edit', compact('serviceProcess', 'users', 'offices', 'user', 'usersAddition'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceProcess  $serviceProcess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceProcess $serviceProcess)
    {
        $serviceProcess->responsible = $request->responsible;
        $serviceProcess->action = $request->action;
        $serviceProcess->responsible_user = $request->responsible_user;
        $serviceProcess->location = $request->location;
        $serviceProcess->manager_id = $serviceProcess->manager_id;
        $serviceProcess->save();
        $user = $request->additional_responsible;
        ManagerUser::where('manager_id', $serviceProcess->manager_id)->delete();
        foreach($user as $users){
            ManagerUser::create([
                'manager_id' => $serviceProcess->manager_id,
                'user_id' => $users,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('admin.service-process.index', ['id' => $serviceProcess->service->id])->with('success', 'Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceProcess  $serviceProcess
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        ServiceProcess::find($id)->delete();
        return response()->json(['succecss' => true, 'message' => 'Deleted successfully.']);
    }

    public function changeProcessOrder($service_id)
    {
        $service  = Service::with(['process'])->find($service_id);
        return view('admin.service.process.change-process-order', compact('service'));
    }

    public function updateProcessOrder(Request $request, $service_id)
    {
        $service = Service::find($service_id);
        foreach ($request->service_process as $key => $processId) {
            $index = $key + 1;
            $process = ServiceProcess::find($processId);
            $process->index = $index;
            $process->save();
        }
        return redirect()->route('admin.service-process.index', ['id' => $service->id])->with('success', 'Updated successfully.');
    }


}
