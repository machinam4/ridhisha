@extends('layouts.base')
@section('page_name', 'SMS')
@section('pageCss')
    <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-datatables/jquery.dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/all.min.css') }}">

    <style>
        table.dataTable td {
            padding: 15px 8px;
        }

        .fontawesome-icons .the-icon svg {
            font-size: 24px;
        }
    </style>
@endsection
@section('contents')
    <div class="page-heading">
        <h3>SMS Stats</h3>
    </div>
    <section class="row">
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon purple">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">SMS Name</h6>
                                    <h6 class="font-extrabold mb-0" id="show_sms_name">show</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon blue">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">SMS Credit</h6>
                                    <h6 class="font-extrabold mb-0" id="show_sms_balance">0.00</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon green">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">New Players</h6>
                                    <h6 class="font-extrabold mb-0">80.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-3 py-4-5">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stats-icon red">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6 class="text-muted font-semibold">Total sent SMS</h6>
                                    <h6 class="font-extrabold mb-0">112</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    <button type="button" class="btn btn-primary block mb-5" data-bs-toggle="modal" data-bs-target="#sendSMSModal">
        SEND SMS
    </button>
    <!-- Vertically Centered modal Modal -->
    <div class="modal fade" id="sendSMSModal" tabindex="-1" role="dialog" aria-labelledby="sendSMSModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendSMSModalTitle">New SMS
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ Route('sendSMS') }}" method="POST" id="smsForm123" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="excel-vertical">Xlsx File*</label>
                                    <input type="file" id="excel-vertical" class="form-control" name="excel"
                                        placeholder="Xlsx File" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message-vertical">Message*</label>
                                    <textarea id="message-vertical" class="form-control" name="message" placeholder="Message*" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="datetimepicker1">Schedule Time*</label>
                                    <input type="text" id="datetimepicker1" class="form-control" name="schedule"
                                        placeholder="Schedule Time" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="smsCount-vertical">SMS Count*</label>
                                    <input type="number" id="smsCount-vertical" class="form-control" name="count"
                                        placeholder="SMS Count" value="1000" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-light-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Cancel</span>
                        </button>
                        <button type="submit" class="btn btn-primary ml-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Save</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Basic Tables start -->
    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table" id="sms_table">
                    <thead>
                        <tr>
                            <th>Mobile Number</th>
                            <th>Date Sent</th>
                            <th>Message</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($smss == '')
                            <tr>
                                <td>No sms for the last 30 days</td>
                            </tr>
                        @else
                            @foreach ($smss as $sms)
                                <tr>
                                    <td>{{ $sms->MobileNumber }}</td>
                                    <td>{{ $sms->DoneDate }}</td>
                                    <td>{{ $sms->Message }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $sms->Status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
@endsection

@section('pageJs')
    <script src="{{ asset('assets/vendors/jquery-datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-datatables/custom.jquery.dataTables.bootstrap5.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            // console.log(moment().format("YYYY-MM-DD HH:mm"))
            $('#datetimepicker1').val(moment().format("YYYY-MM-DD HH:mm"));
            var settings = {
                "url": "/api/sms/sms_stats",
                "method": "GET",
                "timeout": 0,
            };

            $.ajax(settings).done(function(response) {
                results = JSON.parse(response)
                credit = JSON.parse(results.credit)
                sender = JSON.parse(results.sender)
                $('#show_sms_balance').text(credit.Data[0].Credits)
                $('#show_sms_name').text(sender.Data[0].SenderId)
                // console.log(sender.Data[0]);
            });
        })

        $("#smsForm").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            $('#sendSMSModal').modal('toggle');
            // $('.modal-backdrop').remove();
            var form = $(this);
            var url = form.attr('action');
            var formData = new FormData();
            $.ajax({
                type: "POST",
                url: url,
                data: formData(form),
                // data: form.serialize(), // serializes the form's elements.
                success: function(data) {
                    console.log(data)
                    Swal.fire({
                        icon: data['type'],
                        title: data['message']
                    })
                    // Livewire.emit('codeAdded')
                }
            });
        });
    </script>
@endsection
