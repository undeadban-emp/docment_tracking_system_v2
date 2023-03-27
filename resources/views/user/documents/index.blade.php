@extends('layouts.app')
@section('page-title', 'My Documents')
@section('content')
@prepend('page-css')

@endprepend
@include('templates.success')
<div class="card">
     <div class="card-body">
               <table class='table table-bordered table-hover dt-responsive' id="logs-table-liaison-my-docs">
                    <thead>
                         <tr>
                              <th class='text-truncate h6 text-dark font-size-16'>Tracking Number</th>
                              <th class='text-truncate h6 text-dark font-size-16'>Service Name</th>
                              <th class='text-truncate h6 text-dark font-size-16'>Description</th>
                              <th class='text-truncate h6 text-dark font-size-16'>Date Applied</th>
                              <th class='text-truncate h6 text-dark font-size-16 text-center'>actions</th>
                         </tr>
                    </thead>
               </table>
     </div>
</div>
@prepend('page-scripts')
<script>
    $(function () {
                $('#logs-table-liaison-my-docs').DataTable({
                processing: true,
                serverSide: true,
                "order": [[ 0, "desc" ]],
                ajax: "{{ route('user.documents.listMyDocs') }}",
                columns: [
                    {
                        data: 'tracking_number',
                        name: 'tracking_number'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render : function (_, _, data, row) {
                            return `
                            <a href='{{ url('/user/document/${data[0].tracking_number}/${data[0].service_id}') }}' class=' text-white edit btn btn-success '>Track</a>
                            <a href='{{ url('/print-qr/${data[0].tracking_number}') }}' class=' text-white edit btn btn-primary '>Qr Code</a>
                            <button class="text-white edit btn btn-danger" value="${data[0].tracking_number}" id="delete">Delete</button>
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

                $(document).on("click", "#delete", function() {

                    let id = $(this).attr("value");
                    swal({
                            title: "Are you sure you want to delete?"
                            , text: "Once deleted, you will not be able to recover this record!"
                            , icon: "warning"
                            , buttons: true
                            , dangerMode: true
                        , })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    url: `{{ url('/user/document/delete/${id}') }}`
                                    , type: "DELETE"
                                    , cache: false
                                    , success: function(success) {
                                        if (success) {
                                            $('#logs-table-liaison-my-docs').DataTable().ajax.reload();
                                            swal({
                                                icon: 'success'
                                                , text: 'Successfully deleted!'
                                            , });
                                            let count = parseInt($('#myDocsCount').text());
                                            let total = $('#myDocsCount').text(count - 1);
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
@endprepend
@endsection
