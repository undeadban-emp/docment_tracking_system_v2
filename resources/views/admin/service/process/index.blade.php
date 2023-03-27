@extends('admin.layouts.app')
@section('page-title', $service->name)
@section('content')
@include('templates.success')
<div class="card">
     <div class="card-body">
          <div class="text-end">
            <a href="{{ route('admin.service.index') }}"><button type="button" class="btn btn-danger btn-sm mb-3">Back</button></a>
            <a href="{{ route('admin.service-process.create', ['service_id' => $service->id]) }}" class="btn btn-primary btn-sm mb-3">Add Process</a>
            <a href="{{ route('admin.change.process.order', $service->id) }}" class="btn btn-sm btn-secondary waves-effect waves-light mb-3">Change Process Order</a>
          </div>
          <table datas="{{ $service->process }}" id="officesDataTables" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
               <thead>
                    <tr>
                         <th>ID</th>
                         <th class='text-dark fw-medium text-uppercase'>Service Code</th>
                         <th class='text-dark fw-medium text-uppercase'>Receiver</th>
                         <th class='text-dark fw-medium text-uppercase'>Transaction</th>
                         <th class='text-dark fw-medium text-uppercase'>Office</th>
                         <th class='text-dark fw-medium text-uppercase'>Position</th>
                         <th class='text-dark fw-medium text-uppercase'>Action</th>
                         <th class='text-dark fw-medium text-uppercase'>Date Created</th>
                         <th class='text-dark fw-medium text-uppercase text-center'>Options</th>
                    </tr>
               </thead>
               <tbody>
                    @forelse ($service->process as $process)
                    <tr class='align-middle'>
                        <td class='text-dark fw-medium'>{{ $process->index }}</td>
                        <td class='text-dark fw-medium'>{{ $process->code }}</td>
                        <td class='text-dark fw-medium'>{{ $process->user->fullname }}</td>
                        <td class='text-dark fw-medium'>{{ $process->responsible }}</td>
                        <td class='text-dark fw-medium'>{{ $process->office?->description }}</td>
                        <td class='text-dark fw-medium'>{{ $process->user->userPosition->position_name  }}</td>
                        <td class='text-dark fw-medium'>{{ $process->action }}</td>
                        <td class='text-dark fw-medium'>{{ $process->created_at }}</td>
                        <td class='text-center'>
                            <a href="{{ route('admin.service-process.edit', $process->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <button class="btn btn-sm btn-danger delete-process" data-service-id="{{ $service->id }}" data-process-id="{{ $process->id }}">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-danger text-center">No Records</td>
                    </tr>
                    @endforelse
               </tbody>
          </table>
     </div>
</div>
@endsection
@push('page-scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script>

     $(document).ready(function() {
        let element = document.getElementById("officesDataTables");
        let checker = element.getAttribute("datas");
        if(checker.length != 2){
            $("#officesDataTables").DataTable({
                    "columnDefs": [{
                        "orderable": false
                        , "targets": [7]
                }, {
                        targets: [7]
                        , render: function(data) {
                            return moment(data).format('MM/DD/YYYY h:mm A');
                        }
                }]
                , "order": [
                        [0, "asc"]
                ]
            });
        }

     });
     $(document).on('click', '.delete-process', function() {
        let message = document.createElement('p');
        // get the service id
        let serviceID = $(this).attr('data-service-id');
        let processID = $(this).attr('data-process-id');
        message.innerHTML = `Deleting this record may affect some on-process documents would you like to run a scan? This will help you to check first if there\'s on-process document using this service.`;
        $(message).addClass('text-center text-dark');

        swal({
            icon: 'warning'
            , title: ''
            , content: message
            , buttons: ["No", "Yes"]
        }).then((isClicked) => {
            if (isClicked) {
                // Check if there's a on process document
                $.ajax({
                        url: `{{ url('/admin/service-process-scan/${serviceID}') }}`,
                        success: function(data) {
                            if (data.having_on_process != 0) {
                                swal({
                                    icon: 'warning',
                                    title: 'Warning',
                                    text: 'There\'s on-process document using this service. Please check the document list below.',
                                    buttons : false,
                                    timer : 5000,
                                })
                            } else {
                                // Delete Service Process
                                $.ajax({
                                    url: `/admin/service-process/${processID}`,
                                    type: 'DELETE',
                                    success: function(data) {
                                        // Create an paragraph element with text-center class
                                        let paragraph = document.createElement('p');
                                        paragraph.innerHTML = 'Service has been deleted please wait a couple of seconds while the page is refreshing.';
                                        paragraph.classList.add('text-center', 'text-dark');

                                        swal({
                                            icon: 'success',
                                            title: 'Success',
                                            content: paragraph,
                                            buttons : false,
                                            timer : 5000,
                                        })
                                        setTimeout(function() {
                                            location.reload();
                                        }, 3000);
                                    }
                                });
                            }
                        }
                });
            }
        });
     });

</script>
@endpush
