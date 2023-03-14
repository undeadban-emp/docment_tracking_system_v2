@extends('layouts.app')
@section('page-title', $service->description)
@prepend('page-css')
<link rel='stylesheet' type='text/css' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
<link rel="stylesheet" href="{{ asset('/assets/css/timeline.css') }}">
<style>
    .list:hover{
        opacity: 0.9;
    }
</style>
@endprepend
@section('content')
@include('templates.success')
@include('templates.errors')
<div class="card">
     <div class="card-body">

          <h3 class="text-center">{{ $service->name }}</h3>
          <p style="font-size:16px;" class="text-center">{{ $service->description }}</p>
          <div class="accordion" id="accordionExample">
               <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                         <button style="background-color: #283D92;" class="list text-white accordion-button collapsed text-dark font-size-15" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                              Process of {{ $service->name }}
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
                                        @foreach($service->process as $process)
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
                         <button style="background-color: #283D92;" class="list text-white accordion-button collapsed text-dark font-size-15" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              Checklist Requirements - {{ $service->requirements?->count() }}
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
                                        @foreach($service->requirements as $requirement)
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
          <br>
          <form action="{{ route('service.apply', $service->id) }}" method="POST" enctype="multipart/form-data">
               @csrf
               <p class="text-dark font-size-16 fw-medium">Description:</p>
               <textarea class="form-control @error('request_description') is-invalid @enderror" rows="5" value="{{ old('request_description') }}" name="request_description"></textarea>
                @error('request_description')
                
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror
               <p class='text-dark font-size-16 fw-medium pt-3'>Attach Requirements: </p>
               @foreach($service->requirements as $requirement)
               <div class="row">
                    <div class="col-1">
                         <span @class([ 'badge bg-danger text-white shadow pb-2 pt-3 pl-2 pr-2'=> $requirement->is_required == 1,
                              'badge bg-info text-white shadow' => $requirement->is_required == 0,
                              ])
                              >{{ $requirement->is_required == 1 ? 'Required' : 'Optional' }}</span>
                    </div>
                    <div class="col-11">
                         <div class="input-group mb-3">
                              <input type="file" class="form-control" name="attachments[{{ $requirement->id }}]" value="">
                              <label class="input-group-text">{{ $loop->index + 1 }}.
                                   {{ $requirement->description }}</label>
                         </div>
                    </div>
                   
               </div>
               @endforeach

               <div class="float-end">
                    @if(Session::has('success'))
                    <button type="button" class='btn btn-info btn-lg shadow' id="printQR" data-service-id="{{ Session::get('tracking-number') }}">Print QR Code</button>
                    @endif
                    <button id="submit" type="submit" class='btn btn-primary btn-lg shadow'>Submit</button>
               </div>
          </form>

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

     $('#printQR').click(function() {
          let serviceID = $(this).attr('data-service-id');
          window.open(`/print-qr/${serviceID}`);
     });
     $('#submit').click(function() {
        function delayFunc(){
            $('#submit').prop('disabled', true);
        }
        setTimeout(delayFunc, 100);
     });

</script>
@endpush
@endsection
