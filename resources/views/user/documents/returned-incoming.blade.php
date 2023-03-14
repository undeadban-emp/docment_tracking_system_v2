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
                    <table class='table table-condense'>
                         <tbody class='font-size-17 text-dark'>
                              <tr>
                                   <td>Tracking Number:</td>
                                   <td class='fw-bold text-dark'>{{ $trackingNumber }}</td>
                              </tr>
                              <tr>
                                   <td>Name:</td>
                                   <td>{{ $service->information->name }}</td>
                              </tr>
                              <tr>
                                   <td>Description:</td>
                                   <td>{{ $service->information->description }}</td>
                              </tr>
                              <tr>
                                   <td>Avail by:</td>
                                   <td class='fw-medium'>{{ $service->avail_by->lastname }}, {{ $service->avail_by->firstname }} {{ $service->avail_by->middlename }}
                                        {{ $service->avail_by->suffix }}</td>
                              </tr>
                              <tr>
                                   <td>Date Applied:</td>
                                   <td>{{ $dateApplied?->format('F d, Y h:i A') }}</td>
                              </tr>
                              <tr>
                                   <td>Submission Description:</td>
                                   <td>{{ $service->request_description }}</td>
                              </tr>
                         </tbody>
                    </table>
                    <br>
                    <label class='mx-2 text-dark font-size-17'>Attachments : </label>
                    <ol>
                         @foreach($attachedRequirements as $requirement)
                         @php
                         [$dateSubmit, $fileName] = explode("|", $requirement->file)
                         @endphp
                         <li class='view-document mx-3 font-size-17 text-primary text-decoration-underline' style='cursor:pointer;' data-file-name="{{ $fileName }}">
                              <span style=' pointer-events:none;'>{{ $fileName }}</span>
                         </li>
                         @endforeach
                    </ol>
                    <br>
                    <div class="form-group">
                         <label class='mx-2 text-dark font-size-17' for="#remarks">Remarks : </label>
                         <textarea name="remarks" class='form-control' id="" cols="30" rows="10"></textarea>
                    </div>
                    <br>
                    @if($service->status === 'received')
                    <br>
                    <div class="form-group">
                         <label class='text-dark font-size-17' for="#returnTo">(Select if return) : </label>
                         <select name="returnTo" id="returnTo" class='form-control form-select'>
                              <option value="{{ $service->user_id }}|{{ $service->forwarded_by }}|{{ $service->service_index }}">
                                   (AVAIL BY) {{ $service->avail_by->fullname }}
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
                         <label class='mx-2 text-dark font-size-17' for="#reasons">Reasons to return : </label>
                         <textarea name="reasons" class='form-control' id="" cols="30" rows="10">This is just a placeholder for reasons</textarea>
                    </div>
                    @endif

                    <div class="float-end mt-3">

                         @if($service->information->process->last()->index == $service->service_index)
                         <input type="submit" name="action" class='btn btn-danger btn-lg shadow' value='Return'>
                         <input type="submit" name="action" class='btn btn-info btn-lg shadow' value='Release'>
                         @else
                         @if($service->status == 'pending' || $service->status == 'forwarded')
                         <input type="submit" name="action" class='btn btn-success btn-lg shadow' value='Receive'>
                         @else
                         <input type="submit" name="action" class='btn btn-danger btn-lg shadow' value='Return'>
                         <input type="submit" name="action" class='btn btn-primary btn-lg shadow' value='Forward'>
                         @endif
                         @endif

                    </div>
               </div>
          </div>
     </form>

     <!-- end row -->
     @push('page-scripts')
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
     @endpush
     @endsection
