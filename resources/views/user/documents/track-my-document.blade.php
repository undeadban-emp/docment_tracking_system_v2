@extends('layouts.app')
@section('page-title', 'Track My Document')
@section('content')
@prepend('page-css')
     <link rel="shortcut icon" href="images/favicon.ico">
     <link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
     <link rel="stylesheet" href="{{ asset('/assets/css/timeline.css') }}">
@endprepend
@include('templates.success')
<div class="card">
    <div class="card-body">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h3 class="text-center">ONGOING</h3>
                    <h2 class="text-center">{{ $ongoing }}</h2>
                </div>
                 <div class="col-md-4">
                    <h3 class="text-center">RETURNED</h3>
                    <h2 class="text-center">{{ $returned }}</h2>
                 </div>
                 <div class="col-md-4">
                    <h3 class="text-center">COMPLETED</h3>
                    <h2 class="text-center">{{ $completed }}</h2>
                 </div>
            </div>
            <!--end row -->
       </div>
    </div>
</div>
<div class="card">
     <div class="card-body">

        <div class="container pb-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img style="width: 80%;" src="{{ asset('assets/images/widget-img.png') }}" class="hero-image width-100 margin-top-20" alt="pic">
                </div>
                 <div class="col-md-6">
                      <h2>Track your document</h1>
                           <div class="track-document">
                                <form id="" class="newsletter-form" action="{{ url('/track-my-document') }}" method="GET">
                                     <input class="form-control" type="text" name="tracking_id" placeholder="Enter your Tracking ID">
                                     <input class="btn btn-primary mt-3 float-end" type="submit" value="Track">
                                </form>
                           </div>
                 </div>
            </div>
            <!--end row -->
       </div>

        @isset($service)
          <!--begin section-white -->
          <section class="section-white" id="result">
               <!--begin container -->
               <div class="container">
                    <!--begin row -->
                    <div class="row justify-content-center">
                         @if ($service !== "no-result")
                         <!--begin col-md-12 -->
                         <div class="col-md-12 text-center">
                              <h2>{{ $service->name }} Document</h2>

                         </div>
                         <!--end col-md-12 -->
                         <div class="col-md-10">
                              <div class="timeline timeline-left mx-lg-10">
                                   <div style="border-radius:5px;" class="timeline-breaker">User</div>
                                   @foreach ($logs as $log)
                                    @if($log->status == 'pending')
                                        @if ($loop->first)
                                            <div class="timeline-item mt-3 row">
                                                <div class=" font-weight-bold text-md-right">
                                                    <li @class([ 'h6' , 'p-2' , 'bg-primary text-white'=> $log->stage === 'current',
                                                    'text-dark'
                                                    ])
                                                    >
                                                    <i class="fas fa-arrow-right"></i>
                                                    {{ $service->name }} {{ $log->request_description }} - <span class='text-uppercase font-size-15 fw-bold'>{{ Str::upper($log->status) }} </span>
                                                    on
                                                    {{ $log->updated_at->format('F d, Y h:i A') }}
                                                </li>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                                   <!--Timeline item 1-->
                                   @foreach($service->process as $process)
                                <div style="border-radius:5px;" class="timeline-breaker text-center timeline-breaker-middle">
                                    <div>{{ $process->responsible }}</div>
                                </div>

                                <div class="timeline-item mt-3 row">
                                    <div class=" font-weight-bold text-md-right">
                                        @foreach($logs as $document_logs)
                                        @if($document_logs->service_index === $process->index)
                                        @if($document_logs->status != 'pending')

                                        <li @class([ 'h6' , 'p-2' , 'bg-success text-white'=> $document_logs->stage === 'current',
                                            'text-dark'
                                            ])
                                            >


                                            <span class='fw-bold'>
                                                {{ $document_logs->stage === 'current' ? '(CURRENT)' : '' }}
                                            </span>


                                            <i class="fas fa-arrow-right"></i>

                                            {{ $service->name }} {{ $document_logs->request_description }} - <span class='text-uppercase font-size-15 fw-bold'>{{ Str::upper($document_logs->status) == 'LAST' ? 'Released' : $document_logs->status }} </span>


                                            @if(Str::upper($document_logs->status) === 'RECEIVED')
                                            <span class=''>by {{ Str::upper($document_logs->received_by_user->fullname) }}</span>
                                            @elseif(Str::upper($document_logs->status) === 'FORWARDED')
                                            <span class=''>by {{ Str::upper($document_logs->forwarded_by_user->fullname) }} to {{ $document_logs->forwarded_to_user->fullname }}</span>
                                            @elseif(Str::upper($document_logs->status) === 'RETURNED')
                                            <span class='fw-bold'>by {{ Str::upper($document_logs->returnee->fullname) }} <span class='fw-normal'>to</span>
                                                @if($document_logs->return_to->id == Auth::user()->id)
                                                (YOU)
                                                @else
                                                {{ Str::upper($document_logs->return_to->fullname) }}
                                                @endif
                                            </span>
                                            because of <span class='fw-bold'>{{ Str::upper($document_logs->reasons) }}</span>
                                            @endif
                                            on
                                            {{ $document_logs->updated_at->format('F d, Y h:i A') }}
                                    </li>

                                        @endif
                                        @endif
                                        @endforeach
                                        <li style="margin-bottom:-20px;" class="text-center text-dark p-2 fw-bold">
                                            <span class=''>{{ Str::upper($process->office?->description) }}</span>
                                        </li>
                                    </div>
                                </div>
                        @endforeach
                              </div>
                         </div>
                         @else
                         <div class="col-md-12 text-center">
                              <img src="/landing-page-theme/images/undraw_No_data_re_kwbl.png" style="width: 200px;">
                              <p class="text-danger">The tracking ID is invalid or might have expired.</p>
                         </div>
                         @endif
                    </div>
                    <!--end row -->
               </div>
               <!--end container -->
          </section>
          <!--end section-white -->
          @endisset

     </div>
</div>
@endsection
