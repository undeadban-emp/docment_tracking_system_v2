@extends('layouts.app-auth')
@section('page-title', 'Register')
@prepend('page-css')

@endprepend
@section('content')
<div class="col-md-8 col-lg-6 col-xl-6">
    <div class="card overflow-hidden">
        <div class="bg-login text-center">
            <div class="bg-login-overlay"></div>
            <div class="position-relative">
                <h5 class="text-white font-size-20">Create New Account</h5>
                <p href="" class="logo logo-admin mt-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="" width="70">
                </p>
            </div>
        </div>
        <div class="card-body pt-5">
            <div class="p-2">
                @include('templates.errors')
                <form method="POST" action="{{ route('register') }}" class="form-horizontal form-wizard-wrapper" id="user-registration-form">
                    @csrf
                    <h3>Account</h3>
                    <fieldset id="field_id_0">
                        <div class="form-group mb-3">
                            <label for="username">{{ __('Username') }} <span class="text-danger">*</span></label>
                            <input id="username" type="text" class="input_field0 form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autocomplete="username">
                            <p id="username-error"></p>
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="role">{{ __('Account Role') }} <span class="text-danger">*</span></label>
                            <select class="selectpicker form-control" name="role" data-live-search="true" id="role" data-size="5">
                                <option></option>
                                <option value="liaison">Liaison</option>
                                <option value="checker">Checker</option>
                            </select>
                            <p id="role-error"></p>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="form-group mb-3">
                            <label for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                            <input id="password" type="password" class="input_field0 form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                            <p id="password-error"></p>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password-confirm">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>
                            <input id="password-confirm" type="password" class="input_field0 form-control" name="password_confirmation" autocomplete="new-password">
                            <p id="password-confirm-error"></p>
                        </div>


                    </fieldset>

                    <h3>Personal</h3>
                    <fieldset id="field_id_1">
                        <div class="form-group mb-3">
                            <label for="firstname">{{ __('First Name') }} <span class="text-danger">*</span></label>
                            <input id="firstname" type="text" class="input_field1 form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" autofocus>
                            <p id="firstname-error"></p>
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="middlename">{{ __('Middle Name') }}</label>
                            <input id="middlename" type="text" class="form-control @error('middlename') is-invalid @enderror" name="middlename" value="{{ old('middlename') }}" autofocus>
                            <p id="middlename-error"></p>
                            @error('middlename')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="lastname">{{ __('Last Name') }} <span class="text-danger">*</span></label>
                            <input id="lastname" type="text" class="input_field1 form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" autofocus>
                            <p id="lastname-error"></p>
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="suffix">{{ __('Suffix') }}</label>
                            <input id="suffix" type="text" class="form-control @error('suffix') is-invalid @enderror" name="suffix" value="{{ old('suffix') }}" autofocus>
                            @error('suffix')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="position">{{ __('Position') }} <span class="text-danger">*</span></label>
                            <select class="flex-grow-1 selectpicker form-control @error('position') is-invalid @enderror" id="position" data-live-search="true" data-width="100%" data-dropup-auto="false" data-size="5" name="position" autofocus>
                                <option></option>
                                @foreach ($positions as $position)
                                    <option style="width: 450px;" value="{{ $position->code }}" @if (old('position') == $position->code) selected @endif>{{ $position->position_name }}</option>
                                @endforeach
                            </select>
                            <p id="position-error"></p>
                            @error('position')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="office">{{ __('Office') }} <span class="text-danger">*</span></label>
                            <select class="selectpicker form-control @error('office') is-invalid @enderror" data-live-search="true" id="office" data-width="100%" data-dropup-auto="false" data-size="4" name="office" autofocus>
                                <option></option>
                                @foreach ($offices as $office)
                                    <option style="width:450px;" value="{{ $office->code }}" @if (old('office') == $office->code) selected @endif>{{ $office->description }}</option>
                                @endforeach
                            </select>
                            <p id="office-error"></p>
                            @error('office')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone_number">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                            <input id="phone_number" type="text" class="input_field1 form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}">
                            <p id="phone-number-error"></p>
                            @error('phone_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </fieldset>

                    <h3>Finish</h3>
                    <fieldset id="field_id_2">
                        <div class="p-3">
                            <div class="form-check">
                                <input type="checkbox" class="input_field2 form-check-input" id="flexCheckDefault1" name="terms" {{ old('terms') ? 'checked' : '' }}>
                                <label id="term" class="form-check-label" for="flexCheckDefault1">I HEREBY CERTIFY that the information provided in this form is complete, true
                                    and correct to the best of my knowledge.</label>
                                    <p style="padding-top:20px;" id="terms"></p>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
        <p class="text-center">Already have an account ? <a href="{{ route('login') }}" class="fw-medium text-primary">Login</a></p>
    </div>
</div>
@endsection
@push('page-scripts')


<script>


$(function(){

    $("#user-registration-form").steps({
        headerTag:"h3",
        bodyTag:"fieldset",
        transitionEffect:"slide",
        onFinished: function (event, currentIndex)
        {
            if(currentIndex == 2){
                if(flexCheckDefault1.checked == true){
                    $(this).submit();
                }
                if(flexCheckDefault1.checked == false)
                {
                    document.getElementById("term").style.color = "#ff0000";
                    $('#terms').html("Terms and Condition is Required!").css('color', 'red');
                }
            }
        },
        onStepChanging : function (event, currentIndex, a) {
            let isValid = [];
            if(currentIndex > a) {
                return true;
            }
            $('.input_field' + currentIndex).each(function()
            {
                if(this.value != '') {
                    isValid.push(true);
                }else{
                    if(currentIndex == 0){
                        if($('#username').val() == ''){
                            $('#username').addClass("is-invalid");
                            $('#username-error').html("Username is Required!").css('color', 'red');
                        }
                        if($('#password').val() == ''){
                            $('#password').addClass("is-invalid");
                            $('#password-error').html("Password is Required!").css('color', 'red');
                        }
                        if($('#password-confirm').val() == ''){
                            $('#password-confirm').addClass("is-invalid");
                            $('#password-confirm-error').html("Confirm Password is Required!").css('color', 'red');
                        }
                        if($('#role').val() == ''){
                            $('#role').addClass("is-invalid");
                            $('#role-error').html("Account Role is Required!").css('color', 'red');
                        }
                    }
                    if(currentIndex == 1){
                        if($('#firstname').val() == ''){
                            $('#firstname').addClass("is-invalid");
                            $('#firstname-error').html("Firstname is Required!").css('color', 'red');
                        }
                        if($('#lastname').val() == ''){
                            $('#lastname').addClass("is-invalid");
                            $('#lastname-error').html("Lastname is Required!").css('color', 'red');
                        }
                        if($('#position').val() == ''){
                            $('#position').addClass("is-invalid");
                            $('#position-error').html("Position is Required!").css('color', 'red');
                        }
                        if($('#office').val() == ''){
                            $('#office').addClass("is-invalid");
                            $('#office-error').html("Office is Required!").css('color', 'red');
                        }
                        if($('#phone_number').val() == ''){
                            $('#phone_number').addClass("is-invalid");
                            $('#phone-number-error').html("Phone Number is Required!").css('color', 'red');
                        }
                    }

                }
            });
            if($('.input_field' + currentIndex).length == isValid.length) {
                    return true;
            } else {
                return false;
            }
        },
    });
    $('#role,#position,#office').on('click', function () {
                if($('#role').val() != ''){
                    $("#role").removeClass("is-invalid");
                    $("#role-error").html("");
                }
                if($('#position').val() != ''){
                    $("#position").removeClass("is-invalid");
                    $("#position-error").html("");
                }
                if($('#office').val() != ''){
                    $("#office").removeClass("is-invalid");
                    $("#office-error").html("");
                }
    });
    $('#username,#password,#password-confirm, #firstname,#lastname, #phone_number').on('keyup', function () {
                if($('#password').val().length < 6){
                    $('#password').addClass("is-invalid");
                    $('#password-error').html("Password must be atleast 8 characters long").css('color', 'red');
                }
                if($('#username').val() != ''){
                    $('#username').removeClass("is-invalid");
                    $('#username-error').html("");
                }
                if($('#password').val() != ''){
                    $('#password').removeClass("is-invalid");
                    $('#password-error').html("");
                }
                if($('#password-confirm').val() != ''){
                    $('#password-confirm').removeClass("is-invalid");
                    $('#password-error').html("");
                }
                if($('#firstname').val() != ''){
                    $('#firstname').removeClass("is-invalid");
                    $('#firstname-error').html("");
                }
                if($('#lastname').val() != ''){
                    $('#lastname').removeClass("is-invalid");
                    $('#lastname-error').html("");
                }
                if($('#phone_number').val() != ''){
                    $('#phone_number').removeClass("is-invalid");
                    $('#phone-number-error').html("");
                }
                if ($('#password').val() == $('#password-confirm').val()) {
                    $('#password-confirm-error').html('');
                    $('#password-confirm').removeClass("is-invalid");
                } else {
                    $('#password-confirm-error').html("Password Don't Match").css('color', 'red');
                    $('#password-confirm').addClass("is-invalid");
                }
    });
});
</script>
@endpush

