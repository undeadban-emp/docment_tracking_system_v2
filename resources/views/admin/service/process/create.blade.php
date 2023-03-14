@extends('admin.layouts.app')
@section('page-title', 'Add Process to ' . $service->name)
@prepend('page-css')
<meta id="userMetaData" content="@foreach($users as $usersMetaData){ |id|:|{{ $usersMetaData->id }}|, |code|:|{{ $usersMetaData->office }}|, |name|:|{{ $usersMetaData->fullname }}|}, @endforeach">
@endprepend
@section('content')
<div class="card">
     <div class="card-body">
          <form action="{{ route('admin.service-process.store', ['service_id' => $service->id]) }}" method="POST">
               @csrf
               <div class="mb-3 row">
                    <label for="service_process_id" class="col-md-4 col-form-label">Service Code</label>
                    <div class="col-md-8">
                         <input class="form-control @error('service_process_id') is-invalid @enderror" type="text" value="{{ $service->service_process_id }}" id="service_process_id" name="service_process_id" disabled>
                         @error('service_process_id')
                         <span class="invalid-feedback" role="alert">
                              {{ $message }}
                         </span>
                         @enderror
                    </div>
               </div>


               <div class="mb-3 row">
                    <label for="action" class="col-md-4 col-form-label">Action</label>
                    <div class="col-md-8">
                         <input class="form-control @error('action') is-invalid @enderror" type="text" value="{{ old('action') }}" id="action" name="action">
                         @error('action')
                         <span class="invalid-feedback" role="alert">
                              {{ $message }}
                         </span>
                         @enderror
                    </div>
               </div>

               <div class="mb-3 row">
                <label for="office" class="col-md-4 col-form-label">Office</label>
                <div class="col-md-8">
                        <select class="form-control selectpicker @error('office') is-invalid @enderror" name="office"  id="office" data-live-search="true" data-width="100%" data-dropup-auto="false">
                            <option value=""></option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->code }}" {{ old('office') }} >{{ $office->description }}</option>
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
                    <label for="description" class="col-md-4 col-form-label">Receiver</label>
                    <div class="col-md-8">
                         <select name="responsible_user" class='form-control selectpicker' id="responsible_user" data-live-search="true" data-width="100%" data-dropup-auto="false">
                            <option></option>
                         </select>
                         @error('description')
                         <span class="invalid-feedback" role="alert">
                              {{ $message }}
                         </span>
                         @enderror
                    </div>
               </div>

               <div class="mb-3 row">
                    <label for="responsible" class="col-md-4 col-form-label">Transaction</label>
                    <div class="col-md-8">
                         <input class="form-control @error('responsible') is-invalid @enderror" type="text" value="{{ old('responsible') }}" id="responsible" name="responsible">
                         @error('responsible')
                         <span class="invalid-feedback" role="alert">
                              {{ $message }}
                         </span>
                         @enderror
                    </div>
               </div>
               <hr>
               <div class="alert alert-info">
                    <i class="mdi mdi-bullseye-arrow me-2"></i> Incase if the receiver is not available or absent please select a additional receiver for this service process.
                    <div class="float-end">
                         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                         </button>

                    </div>
               </div>
               <div class="mb-3 row">
                    <label for="responsible" class="col-md-4 col-form-label">Register Receiver</label>
                    <div class="col-md-8">
                         <select name="additional_responsible[]" class='form-control selectpicker' id="additional_responsible" data-live-search="true" data-width="100%" data-dropup-auto="false" multiple>
                              <option value="0"></option>
                         </select>
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
     $(document).ready(function() {
          $("#positionsDataTables").DataTable();
     });

    // filter section by division
    $("#office").change(function (e) {
        //userMetaData
        if (document.querySelectorAll('[id="userMetaData"]')[1] == null) {
            var userMetaData = document
                .querySelectorAll('[id="userMetaData"]')[0]
                .content.replaceAll("|", '"');
        } else {
            var userMetaData = document
                .querySelectorAll('[id="userMetaData"]')[1]
                .content.replaceAll("|", '"');
        }
        var userMetaDataRemoveLast =
            "[" +
            userMetaData.substring(0, userMetaData.length - 2) +
            "]";
        let userIdOptionAll = JSON.parse(userMetaDataRemoveLast);

        let officeCode = e.target.value;

        //filter all section data in plantilla//
        let userFilter = userIdOptionAll.filter(
            function (user) {
                return user.code == officeCode;
            }
        );
        //Remove all option in user//
        function removeOptionsSection(userSelection) {
            var ii,
                L = userSelection.options.length - 1;
            for (ii = L; ii >= 0; ii--) {
                userSelection.remove(ii);
            }
        }
        removeOptionsSection(document.getElementById("responsible_user"));
        removeOptionsSection(document.getElementById("additional_responsible"));
        //add user data based in what you select in office//
        var i,
        userLengthId = userFilter.length;
        $("#responsible_user").append("<option></option>");
        for (i = 0; i < userLengthId; i++) {
            var user_final = userFilter[i];
            //filter all division data//
            let userIdFilter = userIdOptionAll.filter(function (user) {
                return (
                    user.code ==
                    user_final.code
                );
            });
            $("#responsible_user").append(
                '<option value="' + userIdFilter[i].id + '">' + userIdFilter[i].name + "</option>"
            );
        }
        // $('#responsible_user').change();
        // $('#additional_responsible').change();
        $('#responsible_user').selectpicker('refresh');

    });

    $("#responsible_user").change(function (e) {


        var resUser = document.getElementById("responsible_user");
        var selectedUser = e.target.value;
        var i;
        // remove duplicate
        function removeOptionsSection(userSelection) {
            var ii,
                L = userSelection.options.length - 1;
            for (ii = L; ii >= 0; ii--) {
                userSelection.remove(ii);
            }
        }
        removeOptionsSection(document.getElementById("additional_responsible"));
        var x = document.getElementById("responsible_user");
        var i;
        var ids = [];
        var names = [];
        // push the value to array
        for (i = 1; i < x.length; i++) {
            ids.push(x.options[i].value);
            names[x.options[i].value] = x.options[i].text;
        }
        //check the ids and remove selected id
        var result = ids.filter(checkAdult);
        function checkAdult(id) {
            return id != selectedUser;
        }
        // display the data
        $("#additional_responsible").append("<option></option>");
        var newL = result.length;
        for (i = 0; i < newL; i++) {
            $("#additional_responsible").append(
                '<option value="' + result[i] + '" >' + names[result[i]] + "</option>"
            );
        }
        $('#additional_responsible').selectpicker('refresh');
    });




</script>
@endpush
