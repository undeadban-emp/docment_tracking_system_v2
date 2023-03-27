@extends('layouts.app')
@section('page-title', 'Manage Documents')
@section('content')
@include('templates.success')
<div class="card">
     <div class="card-body">
            <table id="manage-docs" class='table table-bordered dt-responsive'>
                    <thead>
                         <tr>
                              <th class='h6 fw-medium font-size-16'>Transaction No.</th>
                              <th class='h6 fw-medium font-size-16'>Service</th>
                              <th class='h6 fw-medium font-size-16'>Description</th>
                              <th class='h6 fw-medium font-size-16'>Forwarded By</th>
                              <th class='h6 fw-medium font-size-16'>Avail By</th>
                              <th class='h6 fw-medium font-size-16  text-center'>Actions</th>
                         </tr>
                    </thead>
                    <tbody>
                         @foreach($user as $document)
                         <tr class='align-middle'>
                              <td class='text-dark fw-medium font-size-14 text-center'>{{ $document->tracking_number }}</td>
                              <td class='text-dark fw-medium font-size-14 text-center'>{{ $document->information->name }}</td>
                              <td class='text-dark fw-medium font-size-14 text-center'>{{ $document->request_description }}</td>
                              <td class='text-dark fw-medium font-size-14'>{{ $document->forwarded_by_user->fullname }}</td>
                              <td class='text-dark fw-medium font-size-14'>{{ $document->avail_by->fullname }}</td>
                              <td class='text-center'>
                                   <a href="{{ url('/received/service/'.$document->tracking_number) }}" class='btn btn-primary'>
                                        VIEW
                                   </a>
                              </td>
                         </tr>
                         @endforeach


                    </tbody>
               </table>
     </div>
</div>
@prepend('page-scripts')
<script>
    $(document).ready(function(){
        $("#manage-docs").DataTable();
    });
    </script>
@endprepend
@endsection
