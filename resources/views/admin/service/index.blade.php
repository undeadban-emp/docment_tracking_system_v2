@extends('admin.layouts.app')
@section('page-title', 'Manage Services')
@section('content')
@include('templates.success')

<div class="card">
    <div class="card-body">

        <div class="text-end">
            <a href="{{ route('admin.service.create') }}" class="btn btn-primary mb-3">Add Service</a>
         </div>

        <table checker="{{ $services }}" id="officesDataTables" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <td class='text-dark fw-medium text-uppercase'>Service Code</td>
                    <td class='text-dark fw-medium text-uppercase'>Name</td>
                    <td class='text-dark fw-medium text-uppercase'>Description</td>
                    <td class='text-dark fw-medium text-uppercase'>Office</td>
                    <td class='text-dark fw-medium text-uppercase'>Date Created</td>
                    <td class='text-dark fw-medium text-uppercase'>Date Updated</td>
                    <td class='text-dark fw-medium text-uppercase text-center'>Actions</td>
                </tr>
            </thead>
            <tbody >
                @forelse ($services as $service)
                    <tr class='align-middle'>
                        <td class='text-dark fw-medium'>{{ $service->service_process_id }}</td>
                        <td class='text-dark fw-medium'>{{ $service->name }}</td>
                        <td class='text-dark fw-medium'>{{ $service->description }}</td>
                        <td class='text-dark fw-medium'>
                            @if ($service->serviceOffice()->exists())
                            {{ $service->serviceOffice->description }}
                            @else
                                Not Exist
                            @endif
                        </td>
                        <td class='text-dark fw-medium'>{{ $service->created_at }}</td>
                        <td class='text-dark fw-medium'>{{ $service->updated_at }}</td>
                        <td class='text-center'>
                            <a href="{{ route('admin.service.show', $service->id) }}" class="btn btn-secondary btn-sm">Show</a>
                            <a href="{{ route('admin.service.edit', $service->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ route('admin.service-requirements.index',['service_id' => $service->id]) }}" class="btn btn-info btn-sm">Manage Requirements</a>
                            <a href="{{ route('admin.service-process.index', ['id' => $service->id]) }}" class="btn btn-success btn-sm">Manage Process</a>
                            <a class="text-white btn btn-danger btn-sm" data-id="{{ $service->id }}" data-textval="#" onclick="remove(this)">Remove</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td style="color:red;" colspan="7" class="text-center">No Records</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('page-scripts')
<script>
    function remove(element) {
        let id = element.getAttribute('data-id');
        swal({
            title: "Are you sure you want to delete?"
                    , text: "Once deleted, you will not be able to recover this record!"
                    , icon: "warning"
                    , buttons: true
                    , dangerMode: true
        }).then((willDelete) => {
            if(willDelete){
                $.ajax({
                   url: `{{ url('/admin/service/delete/${id}') }}`,
                   type: "DELETE",
                   cache: false,
                   data: {
                        _token: '{{ csrf_token() }}'
                   }
                   , success: function(dataResult) {
                        if (dataResult) {
                            swal("Deleted Successfully!", " ", "success");
                            location.reload();
                        }
                   }
                });
            }
         })
    }
</script>
<script>
$(document).ready(function(){
    let element = document.getElementById("officesDataTables");
    let checker = element.getAttribute("checker");
     if(checker.length != 2){
         $("#officesDataTables").DataTable({
            "columnDefs": [{
                "orderable": false, "targets": [6]
            },{
                targets:[4,5], render:function(data){
                return moment(data).format('MM/DD/YYYY h:mm A');
                }
            }],
            "order": [[ 5, "desc" ]]
        });
    }

});
</script>
@endpush
