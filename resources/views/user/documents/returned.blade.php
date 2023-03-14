@extends('layouts.app')
@section('page-title', 'Return Documents')
@section('content')
@prepend('page-css')
    <style>
         #loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        margin-left:200px;
        margin-top:30px;
        }
    </style>
@endprepend
<div class="card">
     <div class="card-body">
            <table id="returned-docs" class='table table-hover table-bordered dt-responsive'>
                    <thead>
                         <tr class='text-truncate p-2'>
                              <th class='h6 text-dark font-size-16 '>Transaction No.</th>
                              <th class='h6 text-dark font-size-16 '>Service Name</th>
                              <th class='h6 text-dark font-size-16 '>Description</th>
                              <th class='h6 text-dark font-size-16 '>Returnee</th>
                              <th class='h6 text-dark font-size-16 '>Returned Date</th>
                              <th class='h6 text-dark font-size-16 '>Actions</th>
                         </tr>
                    </thead>
                    <tbody>
                         @foreach($user as $document)
                         <tr class='align-middle text-truncate'>
                              <td class='text-dark fw-medium font-size-14 text-center'>{{ $document->tracking_number }}</td>
                              <td class='text-dark fw-medium font-size-14 text-center'>{{ $document->information->name }}</td>
                              <td class='text-dark fw-medium font-size-14 text-center'>{{ $document->request_description }}</td>
                              <td class='text-dark fw-medium font-size-14'>{{ $document->returnee->fullname }}</td>
                              <td class='text-dark fw-medium font-size-14'>{{ $document->updated_at->format('F d, Y h:i A') }}</td>
                              <td class='text-center'>
                                   <a href="{{ route('user.document.edit', [$document->tracking_number]) }}" class='btn btn-primary'>
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
        $("#returned-docs").DataTable();
    });
    </script>
@endprepend
@endsection
