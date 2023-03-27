@extends('layouts.app')
@section('page-title', 'Account Settings')
@section('content')
@include('templates.success')
@include('templates.errors')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Photo</h4>
                <div class="profile-widgets py-3">
                    <div class="text-center">
                        <div class="">
                            <img src="{{ !is_null($user->profile_picture) ? '/document-tracking/storage/account/' . $user->profile_picture : 'https://res.cloudinary.com/dfm6cr1l9/image/upload/v1653217730/pngkey.com-avatar-png-1149878_lvpbsn.png' }}" alt="" class="avatar-lg mx-auto img-thumbnail rounded-circle">
                        </div>
                        <div class="mt-3 ">
                            <button type="button" class="btn btn-primary waves-effect waves-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditPicture">Edit profile picture</button>
                        </div>
                    </div>
                </div>
                            <!-- Modal -->
                            <div id="modalEditPicture" class="modal fade" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title mt-0" id="myModalLabel">Edit profile picture
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('user.account.settings.update', ['type' => 'profile-picture']) }}" method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            @csrf
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="inputGroupFile02" name="profile_picture">
                                                <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <!-- /.modal -->
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Profile</h4>
                <form action="{{ route('user.account.settings.update', ['type' => 'profile']) }}" method="POST">
                    @csrf
                    <div class="mb-3 row">
                        <label for="firstname" class="col-md-4 col-form-label text-md-end">{{ __('First name') }} <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname', $user->firstname) }}" autofocus>
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="middlename" class="col-md-4 col-form-label text-md-end">{{ __('Middle name') }} <span   >(Optional)</span></label>
                        <div class="col-md-8">
                            <input id="middlename" type="text" class="form-control @error('middlename') is-invalid @enderror" name="middlename" value="{{ old('middlename', $user->middlename) }}" autofocus>
                            @error('middlename')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="lastname" class="col-md-4 col-form-label text-md-end">{{ __('Last name') }} <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname', $user->lastname) }}" autofocus>
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="suffix" class="col-md-4 col-form-label text-md-end">{{ __('Suffix') }} <span   >(Optional)</span></label>
                        <div class="col-md-8">
                            <input id="suffix" type="text" class="form-control @error('suffix') is-invalid @enderror" name="suffix" value="{{ old('suffix', $user->suffix) }}" autofocus>
                            @error('suffix')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="position" class="col-md-4 col-form-label text-md-end">{{ __('Position') }} <span class="text-danger">*</span></label>

                        <div class="col-md-8">
                            <select class="form-control @error('position') is-invalid @enderror" id="position" name="position" autofocus>
                                <option></option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->code }}" @if (old('position', $user->position) == $position->code) selected @endif>{{ $position->position_name }}</option>
                                @endforeach
                            </select>
                            @error('position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="office" class="col-md-4 col-form-label text-md-end">{{ __('Office') }} <span class="text-danger">*</span></label>

                        <div class="col-md-8">
                            <select class="form-control @error('office') is-invalid @enderror" id="office" name="office" autofocus>
                                <option></option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->code }}" @if (old('office', $user->office) == $office->code) selected @endif>{{ $office->description }}</option>
                                @endforeach
                            </select>
                            @error('office')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="row mb-3">
                        <label for="Account Role" class="col-md-4 col-form-label text-md-end">{{ __('Account Role') }} <span class="text-danger">*</span></label>

                        <div class="col-md-8">
                            <select class="form-control @error('accountRole') is-invalid @enderror" id="accountRole" name="accountRole" autofocus>
                                <option></option>
                                    <option value="liaison" @if (old('position', $user->role) == 'liaison') selected @endif>Liaison</option>
                                    <option value="checker" @if (old('position', $user->role) == 'checker') selected @endif>Checker</option>
                            </select>
                            @error('accountRole')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div> --}}

                    <div class="row mb-3">
                        <label for="phone_number" class="col-md-4 col-form-label text-md-end">{{ __('Phone number') }} <span class="text-danger">*</span></label>

                        <div class="col-md-8">
                            <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" autofocus>
                            @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Login Credentials</h4>
        <form action="{{ route('user.account.settings.update', ['type' => 'username']) }}" method="POST">
            @csrf
            <div class="row mb-3">
                <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }} <span class="text-danger">*</span></label>

                <div class="col-md-8">
                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" autofocus>
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
        <hr>
        <form action="{{ route('user.account.settings.update', ['type' => 'password']) }}" method="POST">
            @csrf
            <div class="mb-3 row">
                <label for="current_password" class="col-md-4 col-form-label text-md-end">{{ __('Current Password') }} <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autofocus>
                    @error('current_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }} <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autofocus>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">{{ __('Confirm New Password') }} <span class="text-danger">*</span></label>
                <div class="col-md-8">
                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autofocus>
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('page-scripts')

@endpush
