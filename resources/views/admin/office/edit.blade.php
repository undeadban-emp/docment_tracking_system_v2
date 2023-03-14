@extends('admin.layouts.app')
@section('page-title', 'Edit Office')
@section('content')
<div class="card">
     <div class="card-body">
          <form action="{{ route('admin.office.update', $office->code) }}" method="POST">
               @csrf
               @method('PUT')
               <div class="mb-3 row">
                    <label for="code" class="col-md-4 col-form-label">Code</label>
                    <div class="col-md-8">
                         <input class="form-control @error('code') is-invalid @enderror" type="text" value="{{ old('code', $office->code) }}" id="code" name="code" readonly>
                         @error('code')
                         <span class="invalid-feedback" role="alert">
                              {{ $message }}
                         </span>
                         @enderror
                    </div>
               </div>
               <div class="mb-3 row">
                    <label for="description" class="col-md-4 col-form-label">Description</label>
                    <div class="col-md-8">
                         <input class="form-control @error('description') is-invalid @enderror" type="text" value="{{ old('description', $office->description) }}" id="description" name="description">
                         @error('description')
                         <span class="invalid-feedback" role="alert">
                              {{ $message }}
                         </span>
                         @enderror
                    </div>
               </div>
               <div class="mb-3 row">
                    <label for="description" class="col-md-4 col-form-label">Shortname</label>
                    <div class="col-md-8">
                         <input class="form-control @error('shortname') is-invalid @enderror" type="text" value="{{ old('shortname', $office->shortname) }}" id="shortname" name="shortname">
                         @error('shortname')
                         <span class="invalid-feedback" role="alert">
                              {{ $message }}
                         </span>
                         @enderror
                    </div>
               </div>

               <div class="mb-3 row">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('admin.office.index') }}"><button type="button" class="btn btn-danger">Cancel</button></a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
               </div>
          </form>
     </div>
</div>
@endsection
@push('page-scripts')
<script>
     $(document).ready(function() {
          $("#positionsDataTables").DataTable();
     });

</script>
@endpush
