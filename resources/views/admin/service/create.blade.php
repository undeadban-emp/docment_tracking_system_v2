@extends('admin.layouts.app')
@section('page-title', 'Add Service')
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.service.store') }}" method="POST">
            @csrf
            <div class="mb-3 row">
                <label for="service_process_id" class="col-md-4 col-form-label">Service Process ID</label>
                <div class="col-md-8">
                    <input class="form-control @error('service_process_id') is-invalid @enderror" type="text" value="{{ old('service_process_id') }}"
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
                        <select class="form-control selectpicker @error('office') is-invalid @enderror" name="office"  id="office" data-live-search="true" data-width="100%" data-dropup-auto="false" data-size="5">
                            <option value=""></option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->code }}">{{ $office->description }}</option>
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
                <label for="name" class="col-md-4 col-form-label">Service Name</label>
                <div class="col-md-8">
                    <input class="form-control @error('name') is-invalid @enderror" type="text" value="{{ old('name') }}"
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
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description">{{ old('description') }}</textarea>
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
