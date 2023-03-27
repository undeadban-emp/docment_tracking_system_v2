@extends('admin.layouts.app')
@section('page-title', 'Manage Users')
@section('content')
@include('templates.success')
<div class="card">
    <div class="card-body">
        <div class="text-end">
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary mb-3">Add User</a>
         </div>
        <table id="usersDataTables" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <td class='text-dark fw-medium text-uppercase'>Name</td>
                    <td class='text-dark fw-medium text-uppercase'>Username</td>
                    <td class='text-dark fw-medium text-uppercase'>Status</td>
                    <td class='text-dark fw-medium text-uppercase'>Office</td>
                    <td class='text-dark fw-medium text-uppercase'>Position</td>
                    <td class='text-dark fw-medium text-uppercase'>Phone Number</td>
                    <td class='text-dark fw-medium text-uppercase'>Date Created</td>
                    <td class='text-dark fw-medium text-uppercase'>Date Updated</td>
                    <td class='text-dark fw-medium text-uppercase text-center'>Actions</td>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@push('page-scripts')
<script>
$(document).ready(function(){
    $("#usersDataTables").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/admin/user/data/all') }}",
        "columnDefs": [{
            "orderable": false, "targets": [8]
        },{
            targets:[6,7], render:function(data){
            return moment(data).format('h:mm A MM/DD/YYYY');
            }
        }],
        "order": [[ 6, "desc" ]],
            columns: [
                {
                    className: "text-dark",
                    data: 'fullname',
                    name: 'fullname'
                },
                {
                    className: "text-dark",
                    data: 'username',
                    name: 'username'
                },
                {
                    className: "text-dark",
                    data: 'status',
                    name: 'status',
                    render : function (_, _, data, row) {
                        if(data.status == 'approved'){
                            return `<p style="background-color:#28A745; width:100px; border-radius:5px; text-align: center; color:white;">Approved</p>`;
                        }else{
                            return `<p style="background-color:#FF715B; width:100px; border-radius:5px; text-align: center; color:white;">Disapproved</p>`;
                        }
                    },
                },
                {
                    className: "text-dark",
                    data: 'office',
                    name: 'office'
                },
                {
                    className: "text-dark",
                    data: 'position',
                    name: 'position'
                },
                {
                    className: "text-dark",
                    data: 'phone_number',
                    name: 'phone_number'
                },
                {
                    className: "text-dark",
                    data: 'date_created',
                    name: 'date_created'
                },
                {
                    className: "text-dark",
                    data: 'date_updated',
                    name: 'date_updated'
                },
                {
                    className: "text-dark",
                    data: 'action',
                    name: 'action',
                    render : function (_, _, data, row) {
                        return `
                            <a href="{{ url('/admin/user/edit/${data.action}') }}"><button class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="right" title="Edit"><span class="mdi mdi-pen"></span></button></a>
                            <button id="delete" user_id="${data.action}" class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="right" title="Reset Password"><span class="mdi mdi-lock-reset"></span></button>
                            <button id="reset" user_id="${data.action}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete"><span class="mdi mdi-delete"></span></button>
                        `;
                    },
                }
            ]
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).on("click", "#delete", function() {
    let id = $(this).attr("user_id");
    swal({
            title: "Are you sure you want to delete this position?"
            , icon: "warning"
            , buttons: true
            , dangerMode: true
        , })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: `{{ url('/admin/user/delete/${id}') }}`
                    , type: "DELETE"
                    , cache: false
                    , success: function(success) {
                        if (success) {
                            $('#usersDataTables').DataTable().ajax.reload();
                            swal({
                                icon: 'success'
                                , text: 'Successfully Deleted!'
                            , });

                        }
                    }
                });
            } else {
                swal("Cancelled", "", "error");
            }
        });
    });

    $(document).on("click", "#delete", function() {
    let id = $(this).attr("user_id");
    swal({
            title: "Are you sure you reset password?"
            , icon: "warning"
            , buttons: true
            , dangerMode: true
        , })
        .then((willReset) => {
            if (willReset) {
                $.ajax({
                    url: `{{ url('/admin/user/reset/${id}') }}`
                    , type: "get"
                    , cache: false
                    , success: function(success) {
                        if (success) {
                            $('#usersDataTables').DataTable().ajax.reload();
                            swal({
                                icon: 'success'
                                , text: 'Successfully Reset Password!'
                            , });

                        }
                    }
                });
            } else {
                swal("Cancelled", "", "error");
            }
        });
    });
});
</script>
@endpush
