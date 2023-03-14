@extends('admin.layouts.app')
@section('page-title', 'Edit Service')
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.service.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3 row">
                <label for="service_process_id" class="col-md-4 col-form-label">Service Process ID</label>
                <div class="col-md-8">
                    <input class="form-control @error('service_process_id') is-invalid @enderror" type="text" value="{{ old('service_process_id', $service->service_process_id) }}"
                        id="service_process_id" name="service_process_id">
                        @error('service_process_id')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="office" class="col-md-4 col-form-label">Office</label>
                <div class="col-md-8">
                        <select class="form-control @error('office') is-invalid @enderror" name="office"  id="office">
                            <option value=""></option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->code }}" @if(old('office', $service->office) == $office->code) selected @endif>{{ $office->description }}</option>
                            @endforeach
                        </select>
                        @error('office')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>

            <div class="mb-3 row">
                <label for="name" class="col-md-4 col-form-label">Name</label>
                <div class="col-md-8">
                    <input class="form-control @error('name') is-invalid @enderror" type="text" value="{{ old('name', $service->name) }}"
                        id="name" name="name">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label for="description" class="col-md-4 col-form-label">Description</label>
                <div class="col-md-8">
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description">{{ old('description', $service->name) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12 text-end">
                    <a href="{{ route('admin.service.index') }}"><button type="button" class="btn btn-danger">Cancel</button></a>
                    <button type="submit" class="btn btn-primary">Update</button>
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
