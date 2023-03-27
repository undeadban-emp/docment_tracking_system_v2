@extends('layouts.app')
@section('page-title', 'For Release Documents')
@section('content')
@include('templates.success')
<div class="card">
     <div class="card-body">
               <table id="for-release-docs" class='table table-bordered dt-responsive'>
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
               </table>
     </div>
</div>
@prepend('page-scripts')
<script>
    $(document).ready(function(){
        $("#for-release-docs").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('service.forRelease.datas') }}",
            columns: [
                {
                    className: "text-dark",
                    data: 'tracking_number',
                    name: 'tracking_number'
                },
                {
                    className: "text-dark",
                    data: 'name',
                    name: 'name'
                },
                {
                    className: "text-dark",
                    data: 'description',
                    name: 'description'
                },
                {
                    className: "text-dark",
                    data: 'forwarded_by',
                    name: 'forwarded_by'
                },
                {
                    className: "text-dark",
                    data: 'avail_by',
                    name: 'avail_by'
                },
                {
                    data: 'action',
                    name: 'action',
                    render : function (_, _, data, row) {
                        return `<a href='{{ url('/received/service/${data.tracking_number}') }}' class=' text-white edit btn btn-primary '>View</a>`;
                    },
                },
            ]
        });
    });
    </script>
@endprepend
@endsection
