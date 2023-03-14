@extends('layouts.app')
@section('page-title', 'Return Document : ' . $service->information->name)
@prepend('page-css')
<link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
<link rel="stylesheet" href="{{ asset('/assets/css/timeline.css') }}">
@endprepend
@section('content')
@include('templates.success')
@include('templates.errors')
<div class="card">
     <div class="card-body">

          <h3 class="text-center">{{ $service->information->name }}</h3>
          <p style="font-size:16px;" class="text-center">{{ $service->request_description }}</p>
          <div class="accordion" id="accordionExample">
               <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                         <button style="background-color: #283D92;" class="text-white accordion-button collapsed text-dark font-size-15" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                              Process of {{ $service->information->name }}
                         </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                         <div class="accordion-body">
                              <div class="container-fluid animated animated-done bootdey" data-animate="fadeIn" data-animate-delay="0.05" style="animation-delay: 0.05s;">
                                   <h5 class="text-center text-uppercase mt-0">
                                        {{ $service->description }} Process
                                   </h5>
                                   <hr class="hr-lg mt-0 mb-2 w-10 mx-auto hr-primary">
                                   <div class="timeline timeline-left mx-lg-10">
                                        <div class="timeline-breaker">User</div>
                                        <!--Timeline item 1-->
                                        @foreach($service->information->process as $process)
                                        <div class="timeline-breaker text-center timeline-breaker-middle">
                                             {{ $process->responsible }}</div>
                                        <div class="timeline-item mt-3 row text-center p-2">
                                             <div class="col font-weight-bold h6 text-md-right">
                                                  {{ $process->office?->description }} &nbsp;</div>
                                        </div>
                                        @endforeach
                                   </div>
                              </div>
                         </div>
                    </div>
               </div>
               <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                         <button style="background-color: #283D92;" class="text-white accordion-button collapsed text-dark font-size-15" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              Checklist Requirements - {{ $service->information->requirements?->count() }}
                         </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                         <div class="accordion-body">
                              <table class='table'>
                                   <thead>
                                        <tr>
                                             <th class='text-dark font-size-16'>Requirements</th>
                                             <th class='text-dark font-size-16'>Where to secure</th>
                                        </tr>
                                   </thead>
                                   <tbody>
                                        @foreach($service->information->requirements as $requirement)
                                        <tr>
                                             <td class='text-dark font-size-15'>{{ $requirement->description }}</td>
                                             <td class='text-dark font-size-15'>{{ $requirement->where_to_secure }}</td>
                                        </tr>
                                        @endforeach
                                   </tbody>
                              </table>

                         </div>
                    </div>
               </div>
          </div>
          <hr>
          <div class="row">
               <div class="col-6">
                    <p class='text-dark font-size-16 fw-medium'>
                         Returnee : {{ $service->returnee->fullname }}
                    </p>
                    <p class='text-dark font-size-16 fw-medium'>
                         Tracking Number : {{ $service->tracking_number }}
                    </p>
                    <p class='text-dark font-size-16 fw-medium'>
                         Date return : {{ $service->returnee->updated_at->format('F d, Y h:i A') }}
                    </p>
                    <p class='text-dark font-size-16 fw-medium'>
                         Reason/s : <span class='text-danger fw-bold'>{{ $service->reasons }}</span>
                    </p>
                    <hr>
          
                    
               </div>
               <div class="col-6">
                    <p class='text-dark font-size-16 fw-medium'>Attachments: </p>
                    <ol>
                         @foreach($attachments as $upload)
                         @php
                         list($timestamp, $file) = explode("|", $upload->file)
                         @endphp
                         <li class='view-document mx-3 font-size-17 text-primary text-decoration-underline' data-file-name="{{ $file }}" style="cursor:pointer;">
                              <span style="pointer-events:none;">{{ $file }}</span>
                         </li>
                         @endforeach
                    </ol>
                    <form action="{{ route('service.reapply', $service->tracking_number) }}" method="POST" enctype="multipart/form-data">
                         @csrf
                         <p class='text-dark font-size-16 fw-medium'>Replace Attached Requirements: </p>
                         @foreach($service->information->requirements as $requirement)
                         <div class="row ">
                              <div class="col-6 pb-5 col-lg-3">
                                   <span @class([ 'badge mx-4 bg-danger text-white shadow '=> $requirement->is_required == 1,
                                        'badge bg-info mx-4  text-white shadow ' => $requirement->is_required == 0,
                                        ])
                                        >{{ $requirement->is_required == 1 ? 'Required' : 'Optional' }}</span>
                                   </div>
                              <div class="col-6 pb-5 col-lg-9">
                                   <div class="input-group">
                                        <input type="file" class="form-control" name="attachments[]" value="">
                                        <label class="input-group-text">{{ $loop->index + 1 }}.
                                             {{ $requirement->description }}</label>
                                   </div> 
                              </div>
                         </div>
                         @endforeach
                         <div class="float-end">
                              <button type="submit" class='btn btn-success btn-lg shadow'>Re-apply</button>
                         </div>
                    </form>
          
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

<script>
     $('#btnProceedAndPrint').click(function() {
          let id = $(this).attr('data-id');
          $.ajax({
               url: `/service/apply/${id}`
               , method: 'POST'
               , success: function(response) {
                    if (response.success) {
                         swal({
                              text: "Successfully apply for this service please wait a couple of seconds for redirection of QR Print"
                              , icon: "success"
                              , buttons: false
                              , timer: 5000
                         , });
                    }
               }
          });
     });

</script>
@endpush
@endsection
