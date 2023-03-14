@extends('admin.layouts.app')
@section('page-title', 'Edit Position')
@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.position.update', $position->code) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3 row">
                <label for="code" class="col-md-4 col-form-label">Code</label>
                <div class="col-md-8">
                    <input class="form-control @error('code') is-invalid @enderror" type="text" value="{{ old('code', $position->code) }}"
                        id="code" name="code" readonly>
                        @error('code')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label for="position_name" class="col-md-4 col-form-label">Position name</label>
                <div class="col-md-8">
                    <input class="form-control @error('position_name') is-invalid @enderror" type="text" value="{{ old('position_name', $position->position_name) }}"
                        id="position_name" name="position_name">
                        @error('position_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <label for="position_short_name" class="col-md-4 col-form-label">Position short name</label>
                <div class="col-md-8">
                    <input class="form-control @error('position_short_name') is-invalid @enderror" type="text" value="{{ old('position_short_name', $position->position_short_name) }}"
                        id="position_short_name" name="position_short_name">
                        @error('position_short_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12 text-end">
                    <a href="{{ route('admin.position.index') }}"><button type="button" class="btn btn-danger">Cancel</button></a>
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
