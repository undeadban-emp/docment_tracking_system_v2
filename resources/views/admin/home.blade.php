@extends('admin.layouts.app')
@section('page-title', 'Admin Dashboard')
@section('content')
@include('templates.success')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">User(s) For Approval</h4>
        <div class="table-responsive">
            <table id="usersDataTables" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <td class='text-dark fw-medium text-uppercase'>Name</td>
                        <td class='text-dark fw-medium text-uppercase'>Username</td>
                        <td class='text-dark fw-medium text-uppercase'>Phone Number</td>
                        <td class='text-dark fw-medium text-uppercase'>Position</td>
                        <td class='text-dark fw-medium text-uppercase'>Office</td>
                        <td class='text-dark fw-medium text-uppercase'>Status</td>
                        <td class='text-dark fw-medium text-uppercase'>Date Registered</td>
                        <td class='text-dark fw-medium text-uppercase text-center'>Actions</td>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@push('page-scripts')
<script>
    function remove(element) {
        let id = element.getAttribute('data-id');
        let recordToDelete = element.getAttribute('data-textval');
        Swal.fire({
            title: "Are you sure you want to delete?",
            text: recordToDelete,
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ms-2 mt-2",
                buttonsStyling: !1,
        }).then((t) => {
            t.value
                ? document.getElementById('deleteForm-' + id).submit()
                : console.log('cancel');
         })
    }
</script>
<script>
$(document).ready(function(){
    $("#usersDataTables").DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/admin/dashboard/data') }}",
            "columnDefs": [{
            "orderable": false, "targets": [7]
            },{
                targets:[6], render:function(data){
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
                    data: 'phone_number',
                    name: 'phone_number'
                },
                {
                    className: "text-dark",
                    data: 'position',
                    name: 'position'
                },
                {
                    className: "text-dark",
                    data: 'office',
                    name: 'office'
                },
                {
                    className: "text-dark",
                    data: 'status',
                    name: 'status',
                    render : function (_, _, data, row) {
                        if(data.status == 'pending'){
                            return '<p style="background-color:#FF715B; border-radius:10px; text-align: center; color:white;">Pending</p>';
                        }

                    },
                },
                {
                    className: "text-dark",
                    data: 'date_register',
                    name: 'date_register'
                },
                {
                    className: "text-dark",
                    data: 'action',
                    name: 'action',
                    render : function (_, _, data, row) {
                        return `
                            <button id="approve" user_id="${data.action}" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right" title="Approve User"><span class="mdi mdi-check"></span></button>
                            <button id="diapprove" user_id="${data.action}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Disapprove User"><span class="mdi mdi-block-helper"></span></button>
                        `;
                    },
                },
            ]
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", "#approve", function() {
    let id = $(this).attr("user_id");
    swal({
            title: "Are you sure you want to approve this user?"
            , icon: "warning"
            , buttons: true
            , dangerMode: true
        , })
        .then((willApprove) => {
            if (willApprove) {
                $.ajax({
                    url: `/admin/user/approve/${id}`
                    , type: "patch"
                    , cache: false
                    , success: function(success) {
                        if (success) {
                            $('#usersDataTables').DataTable().ajax.reload();
                            swal({
                                icon: 'success'
                                , text: 'Successfully Approved!'
                            , });

                        }
                    }
                });
            } else {
                swal("Cancelled", "", "error");
            }
        });
    });

    $(document).on("click", "#diapprove", function() {
    let id = $(this).attr("user_id");
    swal({
            title: "Are you sure you want to disapprove this user?"
            , icon: "warning"
            , buttons: true
            , dangerMode: true
        , })
        .then((willDisapproved) => {
            if (willDisapproved) {
                $.ajax({
                    url: `/admin/user/reject/${id}`
                    , type: "patch"
                    , cache: false
                    , success: function(success) {
                        if (success) {
                            $('#usersDataTables').DataTable().ajax.reload();
                            swal({
                                icon: 'success'
                                , text: 'Successfully Disapproved!'
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
