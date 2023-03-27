@extends('admin.layouts.app')
@section('page-title', 'Manage Offices')
@section('content')
@include('templates.success')
<div class="card">
     <div class="card-body">
          <div class="text-end">
               <a href="{{ route('admin.office.create') }}" class="btn btn-primary mb-3">Add Office</a>
          </div>
          <table id="officesDataTables" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
               <thead>
                    <tr>
                         <td class='text-dark fw-medium text-uppercase'>Code</td>
                         <td class='text-dark fw-medium text-uppercase'>Description</td>
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
    $("#officesDataTables").DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('/admin/office/data/all') }}",
        "columnDefs": [{
            "orderable": false, "targets": [4]
        },{
            targets:[2,3], render:function(data){
            return moment(data).format('h:mm A MM/DD/YYYY');
            }
        }],
        "order": [[ 1, "asc" ]],
            columns: [
                {
                    className: "text-dark",
                    data: 'code',
                    name: 'code'
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
                    data: 'date_updated',
                    name: 'date_updated'
                },
                {
                    className: "text-dark",
                    data: 'action',
                    name: 'action',
                    render : function (_, _, data, row) {
                        return `
                            <a href="{{ url('/admin/office/edit/${data.action}') }}"><button class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="right" title="Edit"><span class="mdi mdi-pen"></span></button></a>
                            <button id="delete" office_code="${data.action}" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Delete"><span class="mdi mdi-delete"></span></button>
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
    let id = $(this).attr("office_code");
    swal({
            title: "Are you sure you want to delete this position?"
            , icon: "warning"
            , buttons: true
            , dangerMode: true
        , })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: `{{ url('/admin/office/delete/${id}') }}`
                    , type: "DELETE"
                    , cache: false
                    , success: function(success) {
                        if (success) {
                            $('#officesDataTables').DataTable().ajax.reload();
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

});
</script>
@endpush
