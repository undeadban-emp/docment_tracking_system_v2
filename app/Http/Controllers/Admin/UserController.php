<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Symfony\Component\HttpClient\CurlHttpClient;

class UserController extends Controller
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
        return view('admin.user.index');
    }

    public function indexOfficeData()
    {
        if (request()->ajax()) {
            $users = User::with('userOffice', 'userPosition')->where('status', 'rejected')->orWhere('status', 'approved')->get();
            return Datatables::of($users)
            ->addColumn('fullname', function($row){
                $data = $row->fullname;
                    return $data;
            })
            ->addColumn('username', function($row){
                $data = $row->username;
                    return $data;
            })
            ->addColumn('status', function($row){
                $data = $row->status;
                    return $data;
            })
            ->addColumn('office', function($row){
                $data = $row->userOffice->description;
                    return $data;
            })
            ->addColumn('position', function($row){
                $data = $row->userPosition->position_name;
                    return $data;
            })
            ->addColumn('phone_number', function($row){
                $data = $row->phone_number;
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
                $data = $row->id;
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
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        User::create([
            'firstname' => $request['firstname'],
            'middlename' => $request['middlename'],
            'lastname' => $request['lastname'],
            'suffix' => $request['suffix'],
            'position' => $request['position'],
            'office' => $request['office'],
            'phone_number' => $request['phone_number'],
            'username' => $request['username'],
            'password' => bcrypt($request['password']),
            'status'    =>  'approved',
            'role'    =>  $request['accountRole'],
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User was successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    public function reset($id)
    {
        $password = Hash::make('12345678');
        User::where('id', $id)->update(['password'=> $password]);
        return response('success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        if(\Str::upper($request->form_action) === 'CHANGE_PASSWORD') {
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
                $user->save();
                 return redirect()->route('admin.user.index')->with('success', 'Updated successfully');
            }


        }

                $user->firstname = $request->firstname;
                $user->middlename = $request->middlename;
                $user->lastname = $request->lastname;
                $user->suffix = $request->suffix;
                $user->phone_number = $request->phone_number;
                $user->position = $request->position;
                $user->office = $request->office;
                $user->username = $request->username;
                $user->role = $request->accountRole;
                $user->save();
 return redirect()->route('admin.user.index')->with('success', 'Updated successfully');







    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response('success');
    }

    public function approve(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $user->status = "approved";
        $user->save();

        // approved user
        $client = new CurlHttpClient(['verify_peer' => false, 'verify_host' => false]);
        $response = $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&t=N8hyd6w');
        $res = ltrim($response->getContent(), '0');
        $res = json_decode($res, true);
        $clientID = $res['sid'];

        $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, ['body' => '40']);
        $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID);
        $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, [
            'body' => '42["userApproved", "contact_number='.$user->phone_number.'"]'
        ]);

        return response('success');
    }

    public function reject(Request $request, $user_id)
    {
        $user = User::find($user_id);
        $user->status = "rejected";
        $user->save();
         // reject user
         $client = new CurlHttpClient(['verify_peer' => false, 'verify_host' => false]);
         $response = $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&t=N8hyd6w');
         $res = ltrim($response->getContent(), '0');
         $res = json_decode($res, true);
         $clientID = $res['sid'];

         $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, ['body' => '40']);
         $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID);
         $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, [
             'body' => '42["userReject", "contact_number='.$user->phone_number.'"]'
         ]);
        return response('success');
    }
}