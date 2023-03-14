@extends('admin.layouts.app')
@section('page-title', $serviceName . '-' . $description)
@prepend('page-css')
<link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
<link rel="stylesheet" href="{{ asset('/assets/css/timeline.css') }}">
@endprepend
@section('content')
<div class="card">
     <div class="card-body">
          <table class='table'>
               <tr>
                    <td class='font-size-17 text-dark text-uppercase' style='width : 15%;'>Tracking Number</td>
                    <td class='fw-medium font-size-17 text-dark text-uppercase w-10'>:</td>
                    <td class='fw-medium font-size-17 text-dark'>{{ $logs->first()->tracking_number }}</td>
               </tr>

               <tr>
                    <td class='font-size-17 text-dark text-uppercase' style='width : 15%;'>Avail By :</td>
                    <td class='fw-medium font-size-17 text-dark text-uppercase w-10'>:</td>
                    <td class='fw-medium font-size-17 text-dark'>{{ $logs->first()->avail_by->fullname }}</td>
               </tr>
               <tr>
                    <td class='font-size-17 text-dark text-uppercase' style='width : 15%;'>Description</td>
                    <td class='fw-medium font-size-17 text-dark text-uppercase w-10'>:</td>
                    <td class='fw-medium font-size-17 text-dark'>{{ $logs->first()->request_description ?? 'N/A' }}</td>
               </tr>
               <tr>
                    <td class='font-size-17 text-dark text-uppercase' style='width :15%;'>Date Applied</td>
                    <td class='fw-medium font-size-17 text-dark text-uppercase w-10'>:</td>
                    <td class='fw-medium font-size-17 text-dark'>{{ $logs->first()->created_at->format('F d, Y h:i A')  }}</td>
               </tr>
               <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
               </tr>


          </table>
     </div>
</div>
<div class="card">
     <div class="card-body">
          <div class="float-end mx-3">
               <p class='text-center'>
                    <div class="d-grid gap-2">
                         {{-- <a href="{{ route('print-document-qr', $service->id) }}" target="_blank" class='btn btn-info rounded-0 text-uppercase btn-block'>PRINT QR CODE</a> --}}
                    </div>
               </p>
          </div>
          <div class="clearfix"></div>
          <div class="container-fluid animated animated-done bootdey" data-animate="fadeIn" data-animate-delay="0.05" style="animation-delay: 0.05s;">
               <h5 class="text-center text-uppercase mt-0 text-dark">
                    {{ $serviceName }} Document
                    <br>
                    <small>({{ $description }})</small>
               </h5>
               <hr class="hr-lg mt-0 mb-2 w-10 mx-auto hr-primary">
               <div class="timeline timeline-left mx-lg-10">
                    <div class="timeline-breaker">User</div>
                    <!--Timeline item 1-->
                    @foreach($process as $process)
                    <div class="timeline-breaker text-center timeline-breaker-middle">
                         {{ $process->responsible }} {{ $process->action }}
                    </div>
                    <div class="timeline-item mt-3 row">
                         <div class=" font-weight-bold text-md-right">
                              @foreach($logs as $document_logs)
                              @if($document_logs->service_index === $process->index)
                              <li @class([ 'h6' , 'p-2' , 'bg-primary text-white'=> $document_logs->stage === 'current',
                                   'text-dark'
                                   ])
                                   >
                                   <span class='fw-bold'>
                                        {{ $document_logs->stage === 'current' ? '(CURRENT)' : '' }}
                                   </span>
                                   <i class="fas fa-arrow-right"></i>

                                   {{ $serviceName }} {{ $document_logs->request_description }} - <span class='text-uppercase font-size-15 fw-bold'>{{ Str::upper($document_logs->status) == 'LAST' ? 'Release' : $document_logs->status }}</span>
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
                              @endforeach
                         </div>
                    </div>
                    @endforeach
               </div>
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
