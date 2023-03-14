@extends('admin.layouts.app')
@section('page-title', $service->name)
@section('content')
<div class="card">
    <div class="card-body">
        <table class="table">
            <tr>
                <th>Service Code</th>
                <td>{{ $service->service_process_id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $service->name }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $service->description }}</td>
            </tr>
            <tr>
                <th>Office</th>
                <td>{{ ($service->serviceOffice()->exists()) ? $service->serviceOffice->description : 'Not exist' }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-4">Service Process</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Responsible User</th>
                    <th>Responsible Office</th>
                    <th>Action</th>
                    {{-- <th>Description</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($service->process as $process)
                    <tr>
                        <td>{{ $process->index }}</td>
                        <td>{{ $process->user->fullname }}</td>
                        <td>{{ $process->office?->description }}</td>
                        <td>{{ $process->responsible }}</td>
                        {{-- <td>{{ $process->description }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mb-3 row">
            <div class="col-md-12 text-end">
                <a href="{{ route('admin.service.index') }}"><button type="button" class="btn btn-danger">Back</button></a>
            </div>
        </div>
    </div>
</div>
@endsection
@push('page-scripts')
<script>

</script>
@endpush
