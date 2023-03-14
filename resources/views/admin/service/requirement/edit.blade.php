@extends('admin.layouts.app')
@section('page-title', 'Edit Service Requirement')
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.service-requirements.update', [$requirement->id, 'service_id' => $service->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3 row">
                <label for="description" class="col-md-4 col-form-label">Description</label>
                <div class="col-md-8">
                    <input class="form-control @error('description') is-invalid @enderror" type="text" value="{{ old('description', $requirement->description) }}" id="description" name="description">
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label for="where_to_secure" class="col-md-4 col-form-label">Where to secure</label>
                <div class="col-md-8">
                    <input class="form-control @error('where_to_secure') is-invalid @enderror" type="text" value="{{ old('where_to_secure', $requirement->where_to_secure) }}" id="where_to_secure" name="where_to_secure">
                        @error('where_to_secure')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="name" class="col-md-4 col-form-label"></label>
                <div class="col-md-8">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_required" id="flexCheckChecked" @if($requirement->is_required) checked @endif>
                        <label class="form-check-label" for="flexCheckChecked">
                            Check if required
                        </label>
                    </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-12 text-end">
                    <button onclick="history.back()" type="button" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('page-scripts')
<script>
$(document).ready(function(){
    $("#positionsDataTables").DataTable();
});
</script>
@endpush
