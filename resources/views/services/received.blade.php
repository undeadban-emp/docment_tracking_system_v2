@extends('layouts.app')
@section('page-title', $service->description)
@prepend('page-css')
@endprepend
@section('content')
@include('templates.success')
@include('templates.errors')
@if($service->status === 'pending' || $service->status === 'forwarded')
<form action="{{ route('service.document.received', $trackingNumber) }}" method="POST" enctype="multipart/form-data">
     @else
     <form action="{{ route('service.forward', $trackingNumber) }}" method="POST" enctype="multipart/form-data">
          @endif
          @csrf
          <div class="card shadow-none">
               <input type="hidden" name="tracking_number" value="{{ $trackingNumber }}">
               <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6 col-md-6">
                        <table class='table-hover table table-bordered'>
                            <tbody class='font-size-17 text-dark'>
                                 <tr>
                                      <td>Tracking Number:</td>
                                      <td class='fw-bold text-dark'>{{ $trackingNumber }}</td>
                                 </tr>
                                 <tr>
                                      <td>Service  Name:</td>
                                      <td class='fw-bold text-dark'>{{ $service->information->name }}</td>
                                 </tr>
                                 <tr>
                                      <td>Description:</td>
                                      <td class='fw-bold text-dark'>{{ $service->information->description }}</td>
                                 </tr>
                                 <tr>
                                      <td>Liaison Incharge:</td>
                                      <td class='fw-bold text-dark fw-medium'>{{ $service->avail_by->lastname }}, {{ $service->avail_by->firstname }} {{ $service->avail_by->middlename }}
                                           {{ $service->avail_by->suffix }}</td>
                                 </tr>
                                 <tr>
                                    <td>Office:</td>
                                    <td class='fw-bold text-dark'>{{ $service->avail_by->userOffice->description }}</td>
                                </tr>
                                 <tr>
                                      <td>Date Created:</td>
                                      <td class="fw-bold text-dark">{{ $dateApplied?->format('F d, Y h:i A') }}</td>
                                 </tr>
                                 <tr>
                                      <td>Submission Description:</td>
                                      <td class="fw-bold text-dark">{{ $service->request_description }}</td>
                                 </tr>
                            </tbody>
                       </table>

                       
                    </div>

                    <div class="col-12 col-md-6 col-md-6">
                        <div class="form-group">
                            <label class=' text-dark font-size-17' for="#remarks">Remarks : </label>
                            <textarea style="height: 90px;" name="remarks" class='form-control' id="" cols="30" rows="10"></textarea>
                       </div>
                       <br>
                       <label class='text-dark font-size-17'>Attachments : </label>
                       <ol>
                            @foreach($attachedRequirements as $requirement)
                            @php
                            [$dateSubmit, $fileName] = explode("|", $requirement->file)
                            @endphp
                            <li class='view-document font-size-17 text-primary text-decoration-underline' style='cursor:pointer;' data-file-name="{{ $fileName }}">
                                 <span style=' pointer-events:none;'>{{ $fileName }}</span>
                            </li>
                            @endforeach
                       </ol>
                       <br>
                       @if($service->status === 'received')
                       <div class="form-group d-none">
                            <label class='text-dark font-size-17' for="#returnTo">(Select if return) : </label>
                            <select name="returnTo" id="returnTo" class='form-control form-select'>
                                 <option value="{{ $service->user_id }}|{{ $service->forwarded_by }}|{{ $service->service_index }}">
                                      (Liaison Incharge) {{ $service->avail_by->fullname }}
                                 </option>

                                 @foreach($responsibles as $u)
                                 <option value="{{ $u->responsible_user }}|{{ $service->forwarded_by }}|{{ $u->index }}">
                                      {{ $u->user->fullname }} - {{ $u->responsible }}
                                 </option>
                                 @endforeach
                            </select>
                       </div>
                       <br>
                       <div class="form-group">
                            <label class='text-dark font-size-17' for="#reasons">Reasons to return : </label>
                            <textarea style="height: 90px;" name="reasons" class='form-control @error('reasons') is-invalid @enderror' id="" cols="30" rows="10"></textarea>
                            @error('reasons')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                       @endif
                    </div>
                </div>
                    <div class="float-end mt-3">
                         @if($service->information->process->last()->index == $service->service_index)
                         <input type="submit" name="action" id="disapproved1" class='btn btn-danger shadow' value='Disapprove'>
                         <input type="submit" name="action" id="release" class='btn btn-info shadow' value='Release'>
                         @else
                         @if($service->status == 'pending' || $service->status == 'forwarded')
                         <input type="submit" name="action" id="receive" class='btn btn-success shadow' value='Receive'>
                         @else
                         <input type="submit" name="action" id="disapproved2" class='btn btn-danger shadow' value='Disapprove'>
                         <input type="submit" name="action" id="forward" class='btn btn-primary shadow' value='Forward'>
                         @endif
                         @endif
                    </div>
               </div>
          </div>
     </form>

     <!-- end row -->
     @push('page-scripts')
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
     <script type="text/javascript">
          $.ajaxSetup({
               headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
          });
          $('#receive').click(function() {
            function delayFunc(){
                $('#receive').prop('disabled', true);
            }
            setTimeout(delayFunc, 100);
          });
          $('#disapproved2, #forward').click(function() {
            function delayFunc(){
                $('#disapproved2').prop('disabled', true);
                $('#forward').prop('disabled', true);
            }
            setTimeout(delayFunc, 100);
          });
          $('#disapproved1, #release').click(function() {
            function delayFunc(){
                $('#disapproved1').prop('disabled', true);
                $('#release').prop('disabled', true);
            }
            setTimeout(delayFunc, 100);
          });

     </script>

     @endpush
     @endsection
