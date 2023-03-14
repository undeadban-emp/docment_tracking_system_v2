@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')
<div class="card">
    <div class="card-body">
        <table class='table table-bordered'>
            <thead>
                <tr>
                    <td class='text-muted fw-medium font-size-16 text-uppercase'>Transaction No.</td>
                    <td class='text-muted fw-medium font-size-16 text-uppercase'>Service</td>
                    <td class='text-muted fw-medium font-size-16 text-uppercase'>Forwarded By</td>
                    <td class='text-muted fw-medium font-size-16 text-uppercase'>Avail By</td>
                    <td class='text-muted fw-medium font-size-16 text-uppercase'># of Attachments</td>
                    <td class='text-muted fw-medium font-size-16 text-uppercase text-center'>Actions</td>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach($forForwardDocuments as $document)
                <tr class='align-middle'>
                    <td class='text-dark fw-medium font-size-16 text-center'>{{ $document->transaction_code }}</td>
                    <td class='text-dark fw-medium font-size-16'>{{ $document->name }}</td>
                    <td class='text-dark fw-medium font-size-16'>{{ $document->received_by }}</td>
                    <td class='text-dark fw-medium font-size-16'>{{ $document->user_lastname }}, {{ $document->user_firstname }} {{ $document->user_middlename }} {{ $document->user_suffix }}</td>
                    <td class='text-dark fw-medium font-size-16 text-center'>{{ count(json_decode($document->attachments)) }}</td>
                    <td class='text-center'>
                        <a href="/received/service/{{ $document->service_id }}/0?idx={{ $hash->encode($document->user_id) }}" class='btn btn-primary'>
                            VIEW
                        </a>
                    </td>
                </tr>
                @endforeach --}}
            </tbody>
        </table>
    </div>
</div>
@endsection
