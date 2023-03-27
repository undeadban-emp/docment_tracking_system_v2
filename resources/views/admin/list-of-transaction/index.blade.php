@extends('admin.layouts.app')
@section('page-title', 'List of Transaction')
@section('content')
@include('templates.success')
<div class="card">
    <div class="card-body">
            <div class="m-1 form-group mb-3">
                <div class="row">
                    <div class="col-6">
                        <select class="flex-grow-1 selectpicker form-control @error('position') is-invalid @enderror" id="office" data-live-search="true" data-width="100%" data-dropup-auto="false" data-size="5" name="position" autofocus>
                            <option value="*">All</option>
                            @foreach ($office as $offices)
                                <option style="width: 450px;" {{ '1001' == $offices->code ? 'selected' : '' }} value="{{ $offices->code }}" @if (old('position') == $offices->description) selected @endif>{{ $offices->description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <select class="flex-grow-1 selectpicker form-control @error('position') is-invalid @enderror" id="status" data-live-search="true" data-width="100%" data-dropup-auto="false" data-size="5" name="position" autofocus>
                            <option value="process">On-Process</option>
                            <option value="finished">Finished</option>
                        </select>
                    </div>
                </div>

            </div>
         <table id="listTransaction" class="table table-bordered dt-responsive nowrap mt-3" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
              <thead>
                   <tr>
                        <td class='text-dark fw-medium text-uppercase'>Tracking Number</td>
                        <td class='text-dark fw-medium text-uppercase'>Service Name</td>
                        <td class='text-dark fw-medium text-uppercase'>Description</td>
                        <td class='text-dark fw-medium text-uppercase'>Office</td>
                        <td class='text-dark fw-medium text-uppercase'>Date Applied</td>
                        <td class='text-dark fw-medium text-uppercase text-center'>Actions</td>
                   </tr>
              </thead>
         </table>
    </div>
</div>
@endsection
@push('page-scripts')
<script>
    $(function () {
                var officeValue = $('#office').val();
                var status = $('#status').val();
                var table = $('#listTransaction').DataTable({
                processing: true,
                serverSide: true,
                "order": [[ 0, "desc" ]],
                ajax: `{{ url('/admin/list-of-transaction/list/all/${officeValue}/${status}') }}`,
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
                        data: 'office',
                        name: 'office',
                        render : function (_, _, data, row) {
                            return`${data[0].description}`;
                        }
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
                            <a href='{{ url('/admin/document/${data[0].tracking_number}/${data[0].service_id}') }}' class=' text-white edit btn btn-success '>Track</a>
                            <button class="text-white edit btn btn-danger" value="${data[0].tracking_number}" id="delete">Delete</button>
                            `;
                        },
                    },
                ]
            });

            //filter office
            $("#office,#status").change(function (e) {
                var officeValue = $('#office').val();
                var status = $('#status').val();
                table.ajax
                    .url(`{{ url('/admin/list-of-transaction/list/all/${officeValue}/${status}') }}`)
                    .load();
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
                                    url: `{{ url('/admin/document/delete/${id}') }}`
                                    , type: "DELETE"
                                    , cache: false
                                    , success: function(success) {
                                        if (success) {
                                            $('#listTransaction').DataTable().ajax.reload();
                                            swal({
                                                icon: 'success'
                                                , text: 'Successfully deleted!'
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
