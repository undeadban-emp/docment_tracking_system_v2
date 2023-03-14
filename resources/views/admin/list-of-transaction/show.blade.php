@extends('admin.layouts.app')
@section('page-title', $serviceName . '-' . $description)
@prepend('page-css')
<link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
<link rel="stylesheet" href="{{ asset('/assets/css/timeline.css') }}">
@endprepend
@section('content')
<div class="card">
     <div class="card-body">
            <div class="float-end mx-3">
                <a href="{{ url('admin/list-of-transaction') }}"><button class="btn btn-success">Back</button></a>
            </div>
          <div class="clearfix"></div>
          <div class="container-fluid animated animated-done bootdey" data-animate="fadeIn" data-animate-delay="0.05" style="animation-delay: 0.05s;">
            @foreach ($logs as $log)
                @if ($loop->first)
                    <p style="font-weight: bold;">Name: {{ $log->avail_by->fullname }}</p>
                    <p style="font-weight: bold;">Office: {{ $log->avail_by->userOffice->description }}</p>
                @endif
            @endforeach   
            <h5 class="text-center text-uppercase mt-0 text-dark">
                    {{ $serviceName }} Document
                    <br>
                    <small>({{ $description }})</small>
               </h5>
               <hr class="hr-lg mt-0 mb-2 w-10 mx-auto hr-primary">
               <div class="timeline timeline-left mx-lg-10">
                    <div style="border-radius:5px;" class="timeline-breaker">User
                       
                    </div>
                    @foreach ($logs as $log)
                        @if($log->status == 'pending')
                            @if ($loop->first)
                                <div class="timeline-item mt-3 row">
                                    <div class=" font-weight-bold">
                                        <li class="text-dark">
                                        <i class="fas fa-arrow-right"></i>
                                        {{ $serviceName }} {{ $log->request_description }} - <span class='text-uppercase font-size-15 fw-bold'>{{ Str::upper($log->status) }} </span>
                                        on
                                        {{ $log->updated_at->format('F d, Y h:i A') }}
                                    </li>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <!--Timeline item 1-->

                    @foreach($process as $key => $process)
                              <div style="border-radius:5px;" class="timeline-breaker text-center timeline-breaker-middle">
                                   <div>{{ $process->responsible }}</div>
                              </div>
                    <div class="timeline-item mt-3 row">
                         <div class=" font-weight-bold">
                              {{ $previous = null; }}
                              <div class="d-none">{{ $first_array = true; }}</div>
                              @foreach($logs as $keys => $document_logs)
                              @if($document_logs->service_index === $process->index)
                              @if($first_array)
                                   @if(!empty($logs))
                                        <p style="font-weight:bold;" class="text-dark "> {{ secondsToTime($resultAll[$key]);  }}</p>
                                   @endif
                                   {{ $first_array = false; }}
                              @endif
                              @if($document_logs->status != 'pending')

                              <li @class([ 'h6' , 'p-2' , 'bg-success text-white'=> $document_logs->stage === 'current',
                                'text-dark'
                                ])
                                >


                                <span class='fw-bold'>
                                     {{ $document_logs->stage === 'current' ? '(CURRENT)' : '' }}
                                </span>


                                <i class="fas fa-arrow-right"></i>

                                {{ $serviceName }} {{ $document_logs->request_description }} - <span class='text-uppercase font-size-15 fw-bold'>{{ Str::upper($document_logs->status) == 'LAST' ? 'Released' : $document_logs->status }} </span>


                                @if(Str::upper($document_logs->status) === 'RECEIVED')
                                <span class=''>by {{ Str::upper($document_logs->received_by_user->fullname) }}</span>
                                @elseif(Str::upper($document_logs->status) === 'FORWARDED')
                                {{-- <span class=''>by {{ Str::upper($document_logs) }}</span> --}}
                                <span class=''>by {{ Str::upper($document_logs->forwarded_by_user->fullname) }}</span>
                                @elseif(Str::upper($document_logs->status) === 'RETURNED')
                                <span class='fw-bold'>by {{ Str::upper($document_logs->returnee->fullname) }} <span class='fw-normal'>to</span>
                                     {{ Str::upper($document_logs->return_to->fullname) }}
                                </span>
                                because of <span class='fw-bold'>{{ Str::upper($document_logs->reasons) }}</span>
                                @endif
                                on
                                   {{  $document_logs->updated_at->format('F d, Y h:i A') }}

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
                    @if($check != null)
                        <div class="timeline-breaker text-center timeline-breaker-middle">
                            <div>End</div>
                        </div>
                    @endif

               </>
          </div>
     </div>
</div>
<!-- end row -->
@push('page-scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
     $.ajaxSetup({
          headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
     });

</script>
@endpush
@endsection
