<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use Hashids\Hashids;
use App\Models\Office;
use App\Models\Upload;
use App\Models\Service;
use App\Jobs\SendEmailJob;
use App\Models\Requirement;
use App\Models\UserService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\NotifyMessage;
use App\Models\ServiceProcess;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Notifications\SendEmailNotification;
use Symfony\Component\HttpClient\CurlHttpClient;

class ServiceController extends Controller
{
     /**
      * Display a listing of the resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function index()
     {
        $offices = Office::has('services')
        ->withCount('services')
        ->with(['services', 'services.requirements', 'services.process'])
        ->get();
          return view('services.index', [
               'officesWithService' => $offices,
          ]);
     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
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
      * @param  \App\Models\Service  $service
      * @return \Illuminate\Http\Response
      */
        public function show($id)
        {
            $service = Service::with(['process', 'process.office'])
            ->find($id);
            $userID = Auth::user()->id;
            $hashids = new Hashids();
            $userID = $hashids->encode($userID);
            $firstLand = $hashids->encode(1);
            return view('services.show', compact('service', 'userID', 'firstLand'));
        }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  \App\Models\Service  $service
      * @return \Illuminate\Http\Response
      */
     public function edit(Service $service)
     {
          //
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  \App\Models\Service  $service
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, Service $service)
     {
          //
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  \App\Models\Service  $service
      * @return \Illuminate\Http\Response
      */
     public function destroy(Service $service)
     {
          //
     }

        //  open receive file
        public function received(Request $request, $transactionCode)
        {
            //   $service = UserService::with(['information', 'information.process', 'information.process.user'])->where('tracking_number', $transactionCode)->where('stage', 'current')->first();
            $service = UserService::with(['manager_users', 'information', 'information.process', 'information.requirements'])->where('tracking_number', $transactionCode)->where('stage', 'current')->first();


            if (is_null($service)) {
                return redirect()->to(route('home'));
            }

            foreach($service->manager_users as $services){

            if ($services->user_id != Auth::user()->id) {
                    $isAbort = true;
                    $isDoubleCheck = true;
            } else {
                    $isDoubleCheck = false;
            }
            }
            $isAbort = false;
            $isDoubleCheck = false;
            if ($isDoubleCheck) {
                if (!is_null($service->forward_to) && $service->forward_to != Auth::user()->id) {
                    $isAbort = true;
                } else {
                    $isAbort = false;
                }
        }




            if ($isAbort) {
                abort(404);
            }

            $dateApplied = $service->created_at;

            $trackingNumber = $transactionCode;

            $responsibles = $service->information->process->where('index', '<', $service->service_index);

            $attachedRequirements = Upload::where('transaction_code', $trackingNumber)->get();

            return view('services.received', compact('service', 'dateApplied', 'responsibles', 'attachedRequirements', 'trackingNumber'));

        }



    // function of receive forward release
     public function documentReceived(Request $request, $trackingNumber)
     {

          $action = strtolower($request->action);
          $currentRecord = UserService::where('tracking_number', $trackingNumber)->where('stage', 'current')->first();
          $trackings = UserService::where('tracking_number', $trackingNumber)->orderBy('id', 'ASC')->get();




          if ($currentRecord->status === 'pending') {
               // Look for the next received update the current to passed then change the status of next record to current.
               DB::transaction(function () use ($trackings, $currentRecord) {
                    $oldTime = $currentRecord->updated_at;
                    $nextRecord = $trackings->where('service_index', '=', $currentRecord->service_index)->where('status', 'received')->where('stage', 'incoming')->where('stage', 'incoming')->first();
                    $nextRecord->stage = 'current';
                    $nextRecord->forwarded_by = $currentRecord->forwarded_by;


                    $currentRecord->stage = 'passed';

                    $currentRecord->timestamps = false;

                    $currentRecord->save();
                    $nextRecord->received_by = Auth::user()->id;
                    $nextRecord->save(['timestamps' => false]);
               });

            //    NotifyMessage::create([
            //         'phone_number' => $currentRecord->avail_by->phone_number,
            //         'message' => 'Your document with tracking number ' . $request->tracking_number . ' has approved by ' . Auth::user()->fullname . "\n" . route('document-qr-scanned', $request->tracking_number),
            //         'status' => 'pending',
            //    ]);


            // receive docs with status of pending
            $client = new CurlHttpClient(['verify_peer' => false, 'verify_host' => false]);
            $response = $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&t=N8hyd6w');
            $res = ltrim($response->getContent(), '0');
            $res = json_decode($res, true);
            $clientID = $res['sid'];

            $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, ['body' => '40']);
            $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID);
            $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, [
                'body' => '42["docRecPending", "contact_number='.$currentRecord->avail_by->phone_number.'&trackingNumber=' .$request->tracking_number.'&userIncharge=' . Auth::user()->fullname . '"]'
            ]);



          } else if ($currentRecord->status === 'forwarded') {


               DB::transaction(function () use ($trackings, $currentRecord, $trackingNumber, $request) {
                    $nextRecord = $trackings->where('id', '>', $currentRecord->id)->first();
                    $lastTransaction = UserService::where('tracking_number', $trackingNumber)->orderBy('service_index', 'DESC')->first();
                    // if ($nextRecord->service_index == $lastTransaction->service_index) {
                    //      NotifyMessage::create([
                    //           'phone_number' => $currentRecord->avail_by->phone_number,
                    //           'message' => 'Your document with tracking number ' . $request->tracking_number . ' is ready for release ' . "\n" . route('document-qr-scanned', $request->tracking_number),
                    //           'status' => 'pending',
                    //      ]);
                    // } else {
                    //      NotifyMessage::create([
                    //           'phone_number' => $currentRecord->avail_by->phone_number,
                    //           'message' => 'Your document with tracking number ' . $request->tracking_number . ' has approved by ' . Auth::user()->fullname . "\n" . route('document-qr-scanned', $request->tracking_number),
                    //           'status' => 'pending',
                    //      ]);
                    // }

                    // receive docs with status of forwarded
                    $client = new CurlHttpClient(['verify_peer' => false, 'verify_host' => false]);
                    $response = $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&t=N8hyd6w');
                    $res = ltrim($response->getContent(), '0');
                    $res = json_decode($res, true);
                    $clientID = $res['sid'];

                    $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, ['body' => '40']);
                    $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID);
                    $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, [
                        'body' => '42["docRecForwarded", "contact_number='.$currentRecord->avail_by->phone_number.'&trackingNumber=' .$request->tracking_number.'&userIncharge=' . Auth::user()->fullname . '"]'
                    ]);

                    $nextRecord->stage = 'current';
                    $currentRecord->timestamps = false;
                    $currentRecord->stage = 'passed';
                    $nextRecord->received_by = Auth::user()->id;
                    $currentRecord->save();
                    $nextRecord->save();
               });
          }
          Log::info(Auth::user()->fullname . '('. Auth::user()->userOffice->description .')' . ' Received the Transaction with Tracking No. ' . $request->tracking_number);
          return redirect()->to(route('service.incoming'))->with('success', 'Successfully received the document');
     }

     public function forward(Request $request, $trackingNumber)
     {
          $action = $request->action;
          if (Str::upper($action) === 'DISAPPROVE') {
               $this->validate($request, [
                    'returnTo' => 'required',
                    'reasons' => 'required',
               ]);

               list($returnedTo, $forwardedBy, $serviceIndex) = explode("|", $request->returnTo);

               // $service = UserService::where('tracking_number', $request->tracking_number)->where('stage', 'current')->first()->update([
               //      'stage' => 'passed',
               //      'timestamps' => false
               //      // 'updated_at' => Carbon::now(),
               // ]);

               $service = UserService::where('tracking_number', $request->tracking_number)->where('stage', 'current')->first();

               DB::table('user_service')->where('tracking_number', $request->tracking_number)->where('stage', 'current')
               ->update(['stage'=> 'passed']);

               // Check if Avail by is equal to returned to
               if ($service->user_id == $returnedTo) {
                    // $data = UserService::create([
                    //      'tracking_number' => $trackingNumber,
                    //      'user_id' => $service->user_id,
                    //      'service_index' => $service->service_index,
                    //      'service_id' => $service->service_id,
                    //      'returned_by' => Auth::user()->id,
                    //      'returned_to' => $returnedTo,
                    //      'reasons' => $request->reasons,
                    //      'request_description' => $service->request_description,
                    //      'status' => 'disapproved',
                    //      'stage' => 'current',
                    // ]);
                    DB::table('user_service')->insert([
                         'tracking_number' => $trackingNumber,
                         'user_id' => $service->user_id,
                         'service_index' => $service->service_index,
                         'service_id' => $service->service_id,
                         'returned_by' => Auth::user()->id,
                         'returned_to' => $returnedTo,
                         'reasons' => $request->reasons,
                         'request_description' => $service->request_description,
                         'status' => 'disapproved',
                         'stage' => 'current',
                         'created_at' => $service->created_at,
                         'updated_at' => Carbon::now(),
                      ]);

                    $availBy = User::find($service->user_id);

                    $return = User::find($returnedTo);

                    // NotifyMessage::create([
                    //      'phone_number' => $availBy->phone_number,
                    //      'message' => 'Your document with tracking number ' . $request->tracking_number . ' has disapproved by ' . Auth::user()->fullname . " to " . $return->fullname . "\n Reason : \n" . $request->reasons . "\n" . route('document-qr-scanned', $request->tracking_number),
                    //      'status' => 'pending',
                    // ]);

                    // receive docs with status of forwarde d
                    $client = new CurlHttpClient(['verify_peer' => false, 'verify_host' => false]);
                    $response = $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&t=N8hyd6w');
                    $res = ltrim($response->getContent(), '0');
                    $res = json_decode($res, true);
                    $clientID = $res['sid'];

                    $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, ['body' => '40']);
                    $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID);
                    $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, [
                        'body' => '42["returned", "contact_number='.$availBy->phone_number.'&trackingNumber=' .$request->tracking_number.'&userIncharge=' . Auth::user()->fullname . '"]'
                    ]);

                    // UserService::where(['tracking_number' => $request->tracking_number, 'stage' => 'current'])->update([
                    //     'status' => 'disapproved',
                    //     'returned_by' => Auth::user()->id,
                    //     'returned_to' => $request->forwardTo,
                    //     'reasons' => $request->reasons,
                    //     'updated_at' => Carbon::now(),
                    // ]);
                    Log::info(Auth::user()->fullname . '('. Auth::user()->userOffice->description .')' . ' Returned the Transaction with Tracking No. ' . $request->tracking_number);
                    return redirect()->to(route('service.outgoing'))->with('success', 'Successfully returned the document');
               }

            //    else {
            //         $t = UserService::where('service_index', $serviceIndex)->where('forward_to', '!=', null)->where('status', 'received')->orWhere('status', 'pending')->first();

            //         $data = [
            //              "tracking_number" => $t->tracking_number,
            //              "user_id" => $t->user_id,
            //              "service_id" => $t->service_id,
            //              "service_index" => $t->service_index,
            //              "received_by" => $t->received_by,
            //              "forwarded_by" => $t->forwarded_by,
            //              'forward_to' => $t->forward_to,
            //              'status' => $t->status,
            //              'stage' => 'current',
            //         ];


            //         $disapprovedData = [
            //              "tracking_number" => $service->tracking_number,
            //              "user_id" => $service->user_id,
            //              "service_id" => $service->service_id,
            //              "service_index" => $service->service_index,
            //              "received_by" => $service->received_by,
            //              "forwarded_by" => $service->forwarded_by,
            //              'forward_to' => $service->forward_to,
            //              'returned_by' => Auth::user()->id,
            //              'returned_to' => $returnedTo,
            //              'request_description' => $service->request_description,
            //              'status' => 'disapproved',
            //              'stage' => 'passed',
            //         ];

            //         UserService::create($disapprovedData);
            //         UserService::create($data);
            //         return redirect()->to(route('service.outgoing'))->with('success', 'Successfully returned the document');
            //    }
          } else if (Str::upper($action) === 'RELEASE') {
              $process = UserService::with(['information', 'information.process'])->where('tracking_number', $trackingNumber)->get()->each(function ($record) use ($request) {
                   $record->stage = 'passed';
                    if ($record->information->process?->last()->index == $record->service_index && $record->status == 'last') {
                         $record->remarks = $request->remarks;
                    }
                    $record->timestamps = false;
                    $record->save();
               });

               $lastRecord = UserService::with(['information', 'information.process', 'forwarded_to_user', 'avail_by'])->where('tracking_number', $trackingNumber)->where('status', 'last')->first();
               $lastRecord->updated_at = Carbon::now();
               $lastRecord->save();
            //    $updateTime = $process->where

            //    $p = $process->first();
            //    $availBy = User::find($p->user_id);
            //    NotifyMessage::create([
            //         'phone_number' => $availBy->phone_number,
            //         'message' => "Your document with tracking number " . $request->$trackingNumber . " is released thank your for transacting.\n" . 'You can check your document history here ' . route('document-qr-scanned', $trackingNumber),
            //         'status' => 'pending',
            //    ]);

                // released docs
                $client = new CurlHttpClient(['verify_peer' => false, 'verify_host' => false]);
                $response = $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&t=N8hyd6w');
                $res = ltrim($response->getContent(), '0');
                $res = json_decode($res, true);
                $clientID = $res['sid'];

                $p = $process->first();
                $availBy = User::find($p->user_id);
                $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, ['body' => '40']);
                $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID);
                $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, [
                    'body' => '42["released", "contact_number='.$availBy->phone_number.'&trackingNumber=' .$request->tracking_number.'&userIncharge=' . Auth::user()->fullname . '"]'
                ]);
                Log::info(Auth::user()->fullname . '('. Auth::user()->userOffice->description .')' . ' Released the Transaction with Tracking No. ' . $trackingNumber);
               return redirect()->to(route('service.for-release'))->with('success', 'Successfully released the document');
          } else {
               $currentRecord = UserService::with(['information', 'information.process', 'forwarded_to_user', 'avail_by'])->where('tracking_number', $trackingNumber)->where('stage', 'current')->first();
               $trackings = UserService::where('tracking_number', $trackingNumber)->orderBy('id', 'ASC')->get();
               if ($currentRecord->status === 'received') {
                    $nextRecord = $trackings->where('service_index', '=', $currentRecord->service_index)->where('status', 'forwarded')->first();
                    $currentRecord->stage = 'passed';
                    $currentRecord->forwarded_by = Auth::user()->id;
                    $currentRecord->timestamps = false;
                    $nextRecord->stage = 'current';
                    $nextRecord->forwarded_by = Auth::user()->id;
                    $nextRecord->remarks = $request->remarks;
                    $currentRecord->save();
                    $nextRecord->save();

                    // NotifyMessage::create([
                    //      'phone_number' => $currentRecord->avail_by->phone_number,
                    //      'message' => 'Your document with tracking number ' . $request->tracking_number . ' has forwarded by ' . Auth::user()->fullname . ' to ' . $nextRecord->forwarded_to_user->fullname . "\n" . route('document-qr-scanned', $trackingNumber),
                    //      'status' => 'pending',
                    // ]);

                    // forward docs
                    $client = new CurlHttpClient(['verify_peer' => false, 'verify_host' => false]);
                    $response = $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&t=N8hyd6w');
                    $res = ltrim($response->getContent(), '0');
                    $res = json_decode($res, true);
                    $clientID = $res['sid'];

                    $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, ['body' => '40']);
                    $client->request('GET', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID);
                    $client->request('POST', 'https://surigaodelsur.ph:3030/socket.io/?EIO=4&transport=polling&sid='.$clientID, [
                        'body' => '42["forward", "contact_number='.$currentRecord->avail_by->phone_number.'&trackingNumber=' .$request->tracking_number.'&userIncharge=' . Auth::user()->fullname . '"]'
                    ]);

                    // NotifyMessage::create([
                    //      'phone_number' => $nextRecord->forwarded_to_user->phone_number,
                    //      'message' => 'A document with tracking number ' . $request->tracking_number . ' has forwarded to you by ' . Auth::user()->fullname . "\n" . route('document-qr-scanned', $trackingNumber),
                    //      'status' => 'pending',
                    // ]);
               }

            //    $messageEmailForWhoAvail = [
            //         'message' => 'Your document with tracking number ' . $request->tracking_number . ' has forwarded by ' . Auth::user()->fullname . ' to ' . $nextRecord->forwarded_to_user->fullname,
            //         'url'  =>  route('user.document.show', [$request->tracking_number, $currentRecord->service_id])
            //    ];

            //    $messageEmailForForwardedUser = [
            //         'message' => 'A document with tracking number ' . $request->tracking_number . ' has forwarded to you by ' . Auth::user()->fullname,
            //         'url' => route('user.document.show', [$request->tracking_number, $nextRecord->service_id])
            //    ];

               $availBy = User::find($currentRecord->avail_by->id);
            //    $forwaredTo = User::find($nextRecord->forwarded_to_user->id);
            //    $delay = now()->addSeconds(30);
               // $availBy->notify((new SendEmailNotification($messageEmailForWhoAvail))->delay($delay));
               // $forwaredTo->notify((new SendEmailNotification($messageEmailForForwardedUser))->delay($delay));
               Log::info(Auth::user()->fullname . '('. Auth::user()->userOffice->description .')' . ' Forwarded the Transaction with Tracking No. ' . $trackingNumber);
               return redirect()->to(route('service.outgoing'))->with('success', 'Successfully forwarded the document');
          }
     }

     public function apply(Request $request, $id)
     {
        $request->validate([
            'request_description' => 'required',
        ]);

          $userID = Auth::user()->id;

          $service = Service::with(['process', 'requirements' => function ($query) {
               $query->where('is_required', 1);
          }])->find($id);



          $attachedRequirements = Requirement::whereIn('id', array_keys($request->file('attachments') ?? []))->sum('is_required');

          $requiredRequirements = array_sum($service->requirements->pluck('is_required')->toArray());


          // Condition for checking if the user submit all the required requirements.
          if ($requiredRequirements > $attachedRequirements) {
               return back()->withErrors([
                    'message' => 'Please comply all the required requirements to proceed.'
               ]);
          }

          $trackingNumber = $service->service_process_id .  date('m') . date('d') . date('Y') . $userID . UserService::get()->groupBy('tracking_number')->count() + 1;

          $filenames = [];

          // attach the files here...
          if (!is_null($request->attachments)) {
               foreach ($request->attachments as $attachment) {
                    $filename = time() . '|' . $attachment->getClientOriginalName();

                    $filenames[] = $filename;
                    $attachment->move(base_path() . '/storage/app/files/', $attachment->getClientOriginalName());
               }
          }

          $user = User::find($userID);
          foreach ($service->process as $index => $process) {
          //   dd($service->process[$index + 1]->manager_id);
          // ($index + 1)  === $service->process->count() ? null : $service->process[$index + 1]->responsible_user
          //   dd(($index + 1) === $service->process->count() ? $process->manager_id : $service->process[$index + 1]->manager_id);
               $forwardStatus = 'forwarded';
            //    $manager_id =
               if ($index == 0) {
                    $user->documents()->attach($service, [
                         'tracking_number' => $trackingNumber,
                         'service_index' => $process->index,
                         'status' => 'pending',
                         'stage' => 'current',
                         'forward_to' => $process->responsible_user,
                         'forwarded_by' => Auth::user()->id,
                         'manager_id' => $process->manager_id,
                         'request_description'   =>  $request->request_description
                    ]);


                    // $sendTo = DB::table('users')->where('id', $process->responsible_user)->first();

                    // $availBy = Auth::user();

                    // NotifyMessage::create([
                    //      'phone_number' => $sendTo->phone_number,
                    //      'message' => $availBy->fullname . 'submitted a document with tracking number ' . $trackingNumber . "\n" . route('document-qr-scanned', $trackingNumber),
                    //      'status' => 'pending',
                    // ]);
               }


               $user->documents()->attach($service, [
                    'tracking_number' => $trackingNumber,
                    'service_index' => $process->index,
                    'status' => 'received',
                    'stage' => 'incoming',
                    'forwarded_by' => ($index == 0) ? $process->responsible_user : $service->process[$index - 1]->responsible_user,
                    'manager_id' => $process->manager_id,
                    'request_description'   =>  $request->request_description
               ]);
               if ($service->process->count() == ($index + 1)) {
                    $forwardStatus = 'last';
               }

               $user->documents()->attach($service, [
                    'tracking_number' => $trackingNumber,
                    'service_index' => $process->index,
                    'status' => $forwardStatus,
                    'stage' => 'incoming',
                    'forwarded_by' => $service->process[$index]->responsible_user,
                    'forward_to' => ($index + 1)  === $service->process->count() ? null : $service->process[$index + 1]->responsible_user,
                    'manager_id' => ($index + 1) === $service->process->count() ? $process->manager_id : $service->process[$index + 1]->manager_id,
                    'request_description'   =>  $request->request_description
               ]);
          }

          foreach ($filenames as $filename) {
               Upload::updateOrCreate([
                    'transaction_code' => $trackingNumber,
                    'file' => $filename,
               ]);
          }

        Session::put('tracking-number', $trackingNumber);
        Log::info(Auth::user()->fullname . '('. Auth::user()->userOffice->description .')' . ' Create New Transaction' . ' with Tracking No. ' . $trackingNumber);
        return redirect()->route('user.documents')->with('success', 'Successfully Created ' . $service->name . ' with tracking number : ' . $trackingNumber);
     }

     public function reapply(Request $request, string $trackingNumber)
     {
          $previousDocumentLanded = UserService::where('tracking_number', $trackingNumber)
               ->where('status', 'disapproved')
               ->where('stage', 'current')
               ->first();

        //   $service = Service::with(['process' => function ($query) {
        //        $query->where('responsible', 'like', '%PAC-D%');
        //   }])->find($previousDocumentLanded->service_id);


          $filenames = [];

          // attach the files here...
          if (!is_null($request->attachments)) {
               foreach ($request->attachments as $attachment) {
                    $filename = time() . '|' . $attachment->getClientOriginalName();

                    $filenames[] = $filename;
                    $attachment->move(base_path() . '/storage/app/files/', $attachment->getClientOriginalName());
               }
          }

          foreach ($filenames as $filename) {
               Upload::updateOrCreate([
                    'transaction_code' => $trackingNumber,
                    'file' => $filename,
               ]);
          }




    //       if (!is_null($service->process->first()) && $previousDocumentLanded->service_index > $service->process->first()->index) {
    //            // Get the user account in-charge in PAC-D
    //            $userOfPacD = UserService::where('tracking_number', $trackingNumber)->where('service_index', $service->process->first()->index)->where('received_by', '!=', null)->first();

    //            // Save the current document index as passed
    //            $previousDocumentLanded->stage = 'passed';

    //            // Get all the remaining process of the service.
    //            $procecssFromFinishToLast = UserService::where('tracking_number', $trackingNumber)->get()->unique('service_index')->sortByDesc('service_index');

    //            // Get the last process of the service
    //            $lastIndex = $procecssFromFinishToLast->first()->service_index;

    //            // Get all indexes start from the PAC-D to finish
    //            $remainingProcess = UserService::where('service_index', '>=', $service->process->first()->index)->where('service_index', '<=', $lastIndex)->get()->unique('service_index');

    //            // Since we already grab the process from PAC-D to finish we need to delete all incoming processes of the service
    //            UserService::where('tracking_number', $trackingNumber)->where('stage', 'incoming')->get()->each->delete();

    //            $iteration = 0;
    //            // Iterate then create a new process.
    //            foreach ($remainingProcess as $newProcess) {
    //                 if ($iteration == 0) {
    //                      // Mark this process as current or the PAC-D process.
    //                      UserService::create([
    //                           'tracking_number' => $trackingNumber,
    //                           'user_id' => $previousDocumentLanded->user_id,
    //                           'service_id' => $previousDocumentLanded->service_id,
    //                           'service_index' => $newProcess->service_index,
    //                           'received_by' => Auth::user()->id,
    //                           'forwarded_by' => Auth::user()->id,
    //                           'forward_to' => $userOfPacD->received_by,
    //                           'status' => 'pending',
    //                           'stage' => 'current',
    //                      ]);

    //                      UserService::create([
    //                           'tracking_number' => $trackingNumber,
    //                           'user_id' => $previousDocumentLanded->user_id,
    //                           'service_id' => $previousDocumentLanded->service_id,
    //                           'service_index' => $newProcess->service_index,
    //                           'status' => 'received',
    //                           'stage' => 'incoming',
    //                      ]);

    //                      UserService::create([
    //                           'tracking_number' => $trackingNumber,
    //                           'user_id' => $previousDocumentLanded->user_id,
    //                           'service_id' => $previousDocumentLanded->service_id,
    //                           'service_index' => $newProcess->service_index,
    //                           'status' => 'forwarded',
    //                           'stage' => 'incoming',
    //                      ]);
    //                 } else {
    //                      // Mark this process as incoming

    //                      // UserService::create([
    //                      //      'tracking_number' => $trackingNumber,
    //                      //      'user_id' => $previousDocumentLanded->user_id,
    //                      //      'service_id' => $previousDocumentLanded->service_id,
    //                      //      'service_index' => $newProcess->service_index,
    //                      //      'status' => 'received',
    //                      //      'stage' => 'incoming',
    //                      // ]);

    //                      UserService::create([
    //                           'tracking_number' => $trackingNumber,
    //                           'user_id' => $previousDocumentLanded->user_id,
    //                           'service_id' => $previousDocumentLanded->service_id,
    //                           'service_index' => $newProcess->service_index,
    //                           'status' => 'forwarded',
    //                           'stage' => 'incoming',
    //                      ]);
    //                 }
    //                 $iteration++;
    //            }
    //            $previousDocumentLanded->save();

    //            return redirect()->route('user.documents')->with('Successfully forwarded or continued the process of your document with tracking number ' . $trackingNumber);
    //       } else {
    //            UserService::create([
    //                 'tracking_number' => $trackingNumber,
    //                 'user_id' => $previousDocumentLanded->user_id,
    //                 'service_id' => $previousDocumentLanded->service_id,
    //                 'service_index' => $previousDocumentLanded->service_index,
    //                 'forwarded_by' => $previousDocumentLanded->returned_to,
    //                 'forward_to' => $previousDocumentLanded->returned_by,
    //                 'status' => 'pending',
    //                 'stage' => 'current',
    //            ]);

    //            UserService::create([
    //                 'tracking_number' => $trackingNumber,
    //                 'user_id' => $previousDocumentLanded->user_id,
    //                 'service_id' => $previousDocumentLanded->service_id,
    //                 'service_index' => $previousDocumentLanded->service_index,
    //                 'forwarded_by' => $previousDocumentLanded->returned_to,
    //                 'status' => 'received',
    //                 'stage' => 'incoming',
    //            ]);

    //            $previousDocumentLanded->stage = 'passed';
    //            $previousDocumentLanded->save();
    //       }

                //     UserService::create([
                //         'tracking_number' => $trackingNumber,
                //         'user_id' => $previousDocumentLanded->user_id,
                //         'service_id' => $previousDocumentLanded->service_id,
                //         'service_index' => $previousDocumentLanded->service_index,
                //         'forwarded_by' => $previousDocumentLanded->returned_to,
                //         'forward_to' => $previousDocumentLanded->returned_by,
                //         'status' => 'pending',
                //         'stage' => 'current',
                //    ]);

                //    UserService::create([
                //         'tracking_number' => $trackingNumber,
                //         'user_id' => $previousDocumentLanded->user_id,
                //         'service_id' => $previousDocumentLanded->service_id,
                //         'service_index' => $previousDocumentLanded->service_index,
                //         'forwarded_by' => $previousDocumentLanded->returned_to,
                //         'status' => 'received',
                //         'stage' => 'incoming',
                //    ]);

                   DB::table('user_service')->insert([
                         'tracking_number' => $trackingNumber,
                         'user_id' => $previousDocumentLanded->user_id,
                         'service_id' => $previousDocumentLanded->service_id,
                         'service_index' => $previousDocumentLanded->service_index,
                         'forwarded_by' => $previousDocumentLanded->returned_to,
                         'forward_to' => $previousDocumentLanded->returned_by,
                         'status' => 'pending',
                         'stage' => 'current',
                         'created_at' => $previousDocumentLanded->created_at,
                         'updated_at' => Carbon::now(),
                    ]);

                    DB::table('user_service')->insert([
                         'tracking_number' => $trackingNumber,
                         'user_id' => $previousDocumentLanded->user_id,
                         'service_id' => $previousDocumentLanded->service_id,
                         'service_index' => $previousDocumentLanded->service_index,
                         'forwarded_by' => $previousDocumentLanded->returned_to,
                         'status' => 'received',
                         'stage' => 'incoming',
                         'created_at' => $previousDocumentLanded->created_at,
                         'updated_at' => Carbon::now(),
                    ]);

                   $previousDocumentLanded->stage = 'passed';
                   $previousDocumentLanded->timestamps = false;
                   $previousDocumentLanded->save();
                   Log::info(Auth::user()->fullname . '('. Auth::user()->userOffice->description .')' . ' Reapply the Transaction with Tracking No. ' . $trackingNumber);
          return redirect()->route('user.documents')->with('success', 'Successfully forward or continue the process of your document with tracking number ' . $trackingNumber);
     }

     public function incoming()
     {
        //   $incoming = UserService::with(['avail_by', 'information'])->where('forward_to', Auth::user()->id)->where('stage', 'current')->get();
        //   if ($incoming->count() === 1) {
        //        return redirect()->to('/received/service/' . $incoming->first()->tracking_number);
        //   }
          $hash = new Hashids();

          return view('user.documents.incoming', [
               'pageTitle' => 'Incoming Documents',
               'hash' => $hash,
          ]);
     }

     public function incomingData()
     {
         if (request()->ajax()) {
            $incomingData = UserService::with(['avail_by', 'information', 'forwarded_by_user'])->where('forward_to', Auth::user()->id)->where('stage', 'current')->get();
            return Datatables::of($incomingData)
            ->addColumn('tracking_number', function($row){
                $data = $row->tracking_number;
                    return $data;
            })
            ->addColumn('name', function($row){
                $data = $row->information->name;
                    return $data;
            })
            ->addColumn('office', function($row){
                $office = $row->avail_by->userOffice->description;
                        return $office;
            })
            ->addColumn('description', function($row){
                $data = $row->request_description;
                    return $data;
            })->addColumn('forwarded_by', function($row){
                $data = $row->forwarded_by_user->fullname;
                    return $data;
            })
            ->addColumn('avail_by', function($row){
                $data = $row->avail_by->fullname;
                    return $data;
            })
            ->addColumn('action', function($row){
                $data = $row->tracking_number;
                    return $data;
            })
            ->make(true);
        }
     }

     public function outgoing()
     {
        //   $outgoing = UserService::where('received_by', Auth::user()->id)->where('status', 'received')->where('stage', 'current')->get();
        // $outgoing = UserService::where('received_by', Auth::user()->id)->where('stage', 'current')->where('status', 'received')->get()->filter(function ($record) {
        //     return $record->service_index != array_values($record->information->process->pluck('index')->reverse()->toArray())[0];
        // });
        // if ($outgoing->count() === 1) {
        //        return redirect()->to('/received/service/' . $outgoing->first()->tracking_number);
        //   }
          $hash = new Hashids();

          return view('user.documents.outgoing', [
               'pageTitle' => 'Out-going Documents',
            //    'user' => $outgoing,
               'hash' => $hash,
          ]);
     }
     public function outgoingData()
     {
         if (request()->ajax()) {
            $outgoingData = UserService::where('received_by', Auth::user()->id)->where('stage', 'current')->where('status', 'received')->get()->filter(function ($record) {
                return $record->service_index != array_values($record->information->process->pluck('index')->reverse()->toArray())[0];
            });
            // $outgoingData = UserService::where('received_by', Auth::user()->id)->where('status', 'received')->where('stage', 'current')->get();
            return Datatables::of($outgoingData)
            ->addColumn('tracking_number', function($row){
                $data = $row->tracking_number;
                    return $data;
            })
            ->addColumn('name', function($row){
                $data = $row->information->name;
                    return $data;
            })
            ->addColumn('description', function($row){
                $data = $row->request_description;
                    return $data;
            })->addColumn('forwarded_by', function($row){
                $data = $row->forwarded_by_user->fullname;
                    return $data;
            })
            ->addColumn('avail_by', function($row){
                $data = $row->avail_by->fullname;
                    return $data;
            })
            ->addColumn('action', function($row){
                $data = $row->tracking_number;
                    return $data;
            })
            ->make(true);
        }
     }

     public function forRelease()
     {
        // $forRelease = UserService::where('received_by', Auth::user()->id)->where('stage', 'current')->where('status', 'received')->get()->filter(function ($record) {
        //     return $record->service_index == array_values($record->information->process->pluck('index')->reverse()->toArray())[0];
        // });
        //   if ($forRelease->count() === 1) {
        //        return redirect()->to('/received/service/' . $forRelease->first()->tracking_number);
        //   }
          $hash = new Hashids();

          return view('user.documents.for-release', [
               'pageTitle' => 'For Release Documents',
            //    'user' => $outgoing,
               'hash' => $hash,
          ]);
     }
     public function forReleaseData()
     {
         if (request()->ajax()) {
            // $outgoingData = UserService::where('received_by', Auth::user()->id)->where('status', 'received')->where('stage', 'current')->get();
            $outgoingData = UserService::where('received_by', Auth::user()->id)->where('stage', 'current')->where('status', 'received')->get()->filter(function ($record) {
                return $record->service_index == array_values($record->information->process->pluck('index')->reverse()->toArray())[0];
            });
            return Datatables::of($outgoingData)
            ->addColumn('tracking_number', function($row){
                $data = $row->tracking_number;
                    return $data;
            })
            ->addColumn('name', function($row){
                $data = $row->information->name;
                    return $data;
            })
            ->addColumn('description', function($row){
                $data = $row->request_description;
                    return $data;
            })->addColumn('forwarded_by', function($row){
                $data = $row->forwarded_by_user->fullname;
                    return $data;
            })
            ->addColumn('avail_by', function($row){
                $data = $row->avail_by->fullname;
                    return $data;
            })
            ->addColumn('action', function($row){
                $data = $row->tracking_number;
                    return $data;
            })
            ->make(true);
        }
     }



     public function return()
     {
          $userID = Auth::user()->id;

          $returned = UserService::with(['information', 'information.process', 'information.requirements'])->where('returned_to', $userID)->where('stage', 'current')->get();

          $hash = new Hashids();

          return view('user.documents.returned', [
               'pageTitle' => 'Incoming Documents',
               'user' => $returned,
               'hash' => $hash,
          ]);
     }

     public function manage()
     {
        $userID = Auth::user()->id;
        //   $manage = UserService::with(['manager_users', 'information', 'information.process', 'information.requirements'])->where('manager_id', $userID)->where('stage', 'current')->get();
            $filter = UserService::with('manager_users')->whereHas('manager_users', function($q) {$q->where('user_id', Auth::user()->id);})->get();
            $count = UserService::with('manager_users')->whereHas('manager_users', function($q) {$q->where('user_id', Auth::user()->id);})->count();
            if($count != 0){
                foreach($filter as $filters){
                    if($filters->status == 'forwarded' && $filters->stage == 'current'){
                    $manage = UserService::with('manager_users','information', 'information.process', 'information.requirements')
                    ->whereHas('manager_users', function($q) use ($userID) {
                        $q->where('user_id', $userID);
                    })
                    ->where('received_by', '!=', null)
                    ->where('stage', 'current')
                    ->orWhere('stage', 'pending')
                    ->where('status', 'forwarded')
                    ->get();
                    }else if($filters->status == 'received' && $filters->stage == 'current'){
                            $manage = UserService::with('manager_users','information', 'information.process', 'information.requirements')
                            ->whereHas('manager_users', function($q) use ($userID) {
                                $q->where('user_id', $userID);
                            })
                            ->where('received_by', null)
                            ->where('stage', 'current')
                            ->orWhere('stage', 'pending')
                            ->where('status', 'forwarded')
                            ->get();
                    } else {
                        $manage = UserService::with('manager_users','information', 'information.process', 'information.requirements')
                            ->whereHas('manager_users', function($q) use ($userID) {
                                $q->where('user_id', $userID);
                            })
                            ->where('received_by', null)
                            ->where('stage', 'current')
                            ->where('status', '!=','disapproved')
                            ->orWhere('stage', 'pending')
                            ->where('status', 'forwarded')
                            ->get();
                    }
                }
            }else{
                $manage = UserService::with('manager_users','information', 'information.process', 'information.requirements')
                ->whereHas('manager_users', function($q) use ($userID) {
                    $q->where('user_id', $userID);
                })
                ->where('received_by', null)
                ->where('stage', 'current')
                ->where('status', '!=','disapproved')
                ->orWhere('stage', 'pending')
                ->where('status', 'forwarded')
                ->get();
            }
          return view('user.documents.manage', [
               'pageTitle' => 'Manage Documents',
               'user' => $manage,
          ]);
     }

     public function returned()
     {
          return view('user.documents.returned', [
               'pageTitle' => 'Returned Documents',
          ]);
     }



     public function trackMyDocument(Request $request){
        $ongoing = DB::table('user_service')->where('user_id', Auth::user()->id)
        ->select('id', 'tracking_number', 'user_id', 'status', 'stage', 'created_at', 'updated_at')
        ->where('status', '!=', 'last')
        ->where('stage', '!=','passed')
        ->get()
        ->groupBy('tracking_number')->count();
        $returned = UserService::with(['information', 'information.process', 'information.requirements'])
        ->where('returned_to', Auth::user()->id)
        ->where('stage', 'current')
        ->get()->count();
        $completed = DB::table('user_service')->where('user_id', Auth::user()->id)
        ->select('id', 'tracking_number', 'user_id', 'status', 'stage', 'created_at', 'updated_at')
        ->where('status', 'last')
        ->where('stage', 'passed')
        ->get()
        ->groupBy('tracking_number')->count();

         if ($request->has('tracking_id')) {
                $trackingID = $request->tracking_id;

                $userService = UserService::select('service_id')
                    ->where('tracking_number', $trackingID)->first();

                if (!is_null($userService)) {
                    $documentID = $userService->service_id;
                    // $service = Service::without(['requirements', 'office'])->with(['process', 'user_document' => function ($query) use ($trackingID) {
                    //     $query->where('stage', 'passed')->orWhere('stage', 'current')->where('tracking_number', $trackingID);
                    // }])->find($documentID);
                    $service = Service::with(['process'])->find($documentID);
                    $logs = UserService::with(['receiver', 'receiver.user', 'forwarded_by_user', 'forwarded_to_user'])->where('tracking_number', $trackingID)->where('stage', 'passed')->orWhere('stage', 'current')->orderBy('id', 'ASC')->get()->filter(function ($row) use ($trackingID) {
                        return $row->tracking_number == $trackingID;
                    });;
                } else {
                    $service = 'no-result';
                    $logs = [];
                }

            return view('user.documents.track-my-document')->with(['service' => $service, 'logs' => $logs]);
        }
        return view('user.documents.track-my-document', compact('completed', 'ongoing', 'returned'));
     }
}