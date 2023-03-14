@extends('admin.layouts.app')
@section('page-title', $service->name . ' Requirements')
@section('content')
@include('templates.success')
<div class="card">
     <div class="card-body">
          <div class="text-end">
                <a href="{{ route('admin.service.index') }}"><button type="button" class="btn btn-danger btn-sm mb-3">Back</button></a>
                <a href="{{ route('admin.service-requirements.create', ['service_id' => $service->id]) }}" class="btn btn-primary btn-sm mb-3">Add Requirement</a>
          </div>
          <table datas="{{ $service->requirements }}" id="requirementsDataTables" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
               <thead>
                    <tr>
                         <th class='text-dark fw-medium text-uppercase'>Description</th>
                         <th class='text-dark fw-medium text-uppercase'>Where to secure</th>
                         <th class='text-dark fw-medium text-uppercase'>Status</th>
                         <th class='text-dark fw-medium text-uppercase'>Date Created</th>
                         <th class='text-dark fw-medium text-uppercase'>Date Updated</th>
                         <th class='text-dark fw-medium text-uppercase text-center'>Actions</th>
                    </tr>
               </thead>
               <tbody>
                    @forelse ($service->requirements as $requirement)
                    <tr class='align-middle'>
                         <td class='text-dark fw-medium'>{{ $requirement->description }}</td>
                         <td class='text-dark fw-medium'>{{ $requirement->where_to_secure }}</td>
                         <td class='text-dark fw-medium text-center align-middle'>
                              <span @class([ 'badge bg-danger p-2 text-white shadow text-uppercase'=> $requirement->is_required == 1,
                                   'badge bg-warning p-2 text-white shadow text-uppercase' => $requirement->is_required == 0
                                   ])>
                                   {{ $requirement->is_required == 1 ? 'required' : 'optional' }}
                              </span>
                         </td>
                         <td class='text-dark fw-medium'>{{ $requirement->created_at }}</td>
                         <td class='text-dark fw-medium'>{{ $requirement->updated_at }}</td>
                         <td class='text-center'>
                              <form action="{{ route('admin.service-requirements.destroy', [$requirement->id]) }}" method="POST">
                                   @csrf
                                   <a href="{{ route('admin.service-requirements.edit', [$requirement->id, 'service_id' => $service->id]) }}" class="btn btn-sm btn-primary">Edit</a>
                                   @method('DELETE')
                                   <input type="submit" class='btn btn-sm btn-danger' value="Delete">
                              </form>
                         </td>
                    </tr>
                    @empty
                    <tr>
                         <td colspan="7" class="text-danger text-center">No Records</td>
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
          let recordToDelete = element.getAttribute('data-textval');
          swal({
               title: "Are you sure you want to delete?"
               , text: recordToDelete
               , type: "warning"
               , showCancelButton: !0
               , confirmButtonText: "Yes, delete it!"
               , cancelButtonText: "No, cancel!"
               , confirmButtonClass: "btn btn-success mt-2"
               , cancelButtonClass: "btn btn-danger ms-2 mt-2"
               , buttonsStyling: !1
          , }).then((t) => {
               t.value ?
                    document.getElementById('deleteForm-' + id).submit() :
                    console.log('cancel');
          })
     }

</script>
<script>
     $(document).ready(function() {
          let element = document.getElementById("requirementsDataTables");
          let checker = element.getAttribute("datas");
        if(checker.length != 2){
          $("#requirementsDataTables").DataTable({
               "columnDefs": [{
                    "orderable": false
                    , "targets": [5]
               }, {
                    targets: [4]
                    , render: function(data) {
                         return moment(data).format('MM/DD/YYYY h:mm A');
                    }
               }]
               , "order": [
                    [0, "asc"]
               ]
          , });
        }
     });

</script>
@endpush
