@extends('layouts.app')
@section('page-title', 'Your logs')
@section('content')
    @if ($userRole == 'liaison')
        <div class="card">
            <div class="card-body">
                    <table class='table table-bordered table-hover dt-responsive' id="logs-table-liaison">
                        <thead>
                                <tr>
                                    <th class='text-dark text-uppercase'>Tracking Number</th>
                                    <th class='text-dark text-uppercase'>Name</th>
                                    <th class='text-dark text-uppercase'>Description</th>
                                    <th class='text-dark text-uppercase'>Date Created</th>
                                    <th class='text-dark text-uppercase'>Date Finish</th>
                                    <th class='text-dark text-uppercase text-center'>actions</th>
                                </tr>
                        </thead>
                    </table>
            </div>
    </div>
    @else
    <div class="table-responsive">
    <div class="card">
     <div class="card-body">
                <input id="userId" class="d-none" value="{{ $userID }}"></input>
               <table class='table table-bordered table-hover dt-responsive' id="logs-table-checker">
                    <thead>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Transaction No.</th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Service</th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Forwarded By</th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Office</th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Forwarded To</th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Received By</th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Returned By: </th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Returned To: </th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Liaison Incharge: </th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Release By: </th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Date: </th>
                        <th class='text-truncate h6 fw-bold text-dark font-size-16 '>Action Taken: </th>
                    </thead>
               </table>
            </div>
     </div>
</div>
@endif
@prepend('page-scripts')
    <script>
        $(function () {
        var userId = $('#userId').val();

        $('#logs-table-checker').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('logs.list.checker') }}",
            columns: [
                {
                    data: 'tracking_number',
                    name: 'tracking_number'
                },
                {
                    data: 'service',
                    name: 'service'
                },
                {
                    data: 'forwarded_by_user',
                    name: 'forwarded_by_user',
                    render : function (_, _, data, row) {
                        let information = JSON.parse(data.forwarded_by_user);
                        if(information.status != 'last'){
                            if(information.id ==  userId){
                                return `<span class='badge bg-primary p-2'>You</span>`;
                            }else{
                                return `<span class='text-dark'><p>${information.fullname}</p></span>`;
                            }
                        }
                    },
                },
                {
                    data: 'office',
                    name: 'office',
                },
                {
                    data: 'forwarded_to_user',
                    name: 'forwarded_to_user',
                    render : function (_, _, data, row) {
                        if(data.forwarded_to_user == null){
                            return '';
                        }else{
                            if(data.forwarded_to_user.id == userId){
                                return `<span class='badge bg-primary p-2'>You</span>`;
                            }else{
                                return `<span class='text-dark'> ${data.forwarded_to_user.fullname}</span>`;
                            }
                        }
                    },
                },
                {
                    data: 'received_by',
                    name: 'received_by',
                    render : function (_, _, data, row) {
                        if(data.received_by != null){
                            if(data.received_by == userId){
                                return `<span class='badge bg-primary p-2'>You</span>`;
                            }else{
                                return `<span class='text-dark'> ${data.forwarded_to_user.fullname}</span>`;
                            }
                        }else{
                            return '';
                        }
                    },
                },
                {data: 'returned_by', name: 'returned_by'},
                {data: 'returned_to', name: 'returned_to'},
                {
                    data: 'avail_by',
                    name: 'avail_by',
                    render : function (_, _, data, row) {
                            if(data.avail_by.id == userId){
                                return `<span class='badge bg-primary p-2'>You</span>`;
                            }else{
                                return `<span class='text-dark'> ${data.avail_by.fullname}</span>`;
                            }
                        },
                    },
                    {
                    data: 'forwarded_by_user',
                    name: 'forwarded_by_user',
                    render : function (_, _, data, row) {
                        if(data.status == 'last'){
                            if(data.forwarded_by_user.id ==  userId){
                                return `<span class='badge bg-primary p-2'>You</span>`;
                            }else{
                                return `${data.forwarded_by_user.fullname}`;
                            }
                        }else{
                            return '';
                        }
                    },
                },
                {
                    data: 'date',
                    name: 'date',
                },
                {
                    data: 'status',
                    name: 'status',
                    render : function (_, _, data, row) {
                        if(data.status == 'pending'){
                            return '<span class="bg-warning badge p-2 text-white">Pending</span>';
                        }else if(data.status == 'received'){
                            return '<span class="bg-success badge p-2 text-white">Received</span>';
                        }else if(data.status == 'forwarded'){
                            return '<span class="bg-primary badge p-2 text-white">Forwarded</span>';
                        }else{
                            return '<span class="bg-info badge p-2 text-white">Last</span>';
                        }
                    },
                },
            ]
        });


        $('#logs-table-liaison').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('logs.list.liaison') }}",
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
                    data: 'date_created',
                    name: 'date_created'
                },
                {
                    className: "text-dark",
                    data: 'date_finish',
                    name: 'date_finish'
                },
                {
                    data: 'action',
                    name: 'action',
                    render : function (_, _, data, row) {
                        return `<a href='{{ url('/user/document/${data[0].tracking_number}/${data[0].service_id}') }}' class=' text-white edit btn btn-success '>View Road Map</a>`;
                    },
                },
            ]
        });


    });
    </script>
@endprepend

@endsection
