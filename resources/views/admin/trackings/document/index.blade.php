@extends('admin.layouts.app')
@section('page-title', 'Documents')
@section('content')
@include('templates.success')
<div class="card">
     <div class="card-body">
          <table id="positionsDataTables" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
               <thead>
                    <tr>
                         <td class='text-dark fw-medium text-uppercase'>Tracking Number</td>
                         <td class='text-dark fw-medium text-uppercase'>Description</td>
                         <td class='text-dark fw-medium text-uppercase'>Avail By</td>
                         <td class='text-dark fw-medium text-uppercase'>Date Availed</td>
                         <td class='text-dark fw-medium text-uppercase text-center'>Actions</td>
                    </tr>
               </thead>
               <tbody>
                    @foreach($documents as $document)
                    <tr>
                         <td class='font-size-16 align-middle text-dark fw-medium text-uppercase text-center'>{{ $document->tracking_number }}</td>
                         <td class='font-size-16 align-middle text-dark fw-medium text-uppercase text-center'>{{ $document->request_description }}</td>
                         <td class='font-size-16 align-middle text-dark fw-medium text-uppercase text-center'>{{ $document->avail_by->fullname }}</td>
                         <td class='font-size-16 align-middle text-dark fw-medium text-uppercase text-center'>{{ $document->created_at->format('F d, Y h:i A') }}</td>
                         <td class='font-size-16 align-middle text-dark fw-medium text-uppercase text-center'>
                              <a href="{{ route('admin.document.track.show', ['transactionCode' => $document->tracking_number, 'serviceID' => $document->service_id ]) }}" class='btn btn-primary'>TRACK</a>
                         </td>
                    </tr>
                    @endforeach
               </tbody>
          </table>
     </div>
</div>
@endsection
@push('page-scripts')
<script>
     $(document).ready(function() {
          $("#positionsDataTables").DataTable({});
     });

</script>
@endpush
