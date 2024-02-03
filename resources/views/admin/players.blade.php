@extends('layouts.base')
@section('page_name', 'Players')
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
        <h3>PLAYERS</h3>
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
                                    <h6 class="text-muted font-semibold">Total Players</h6>
                                    <h6 class="font-extrabold mb-0" id="show_sms_name">{{ $players }}</h6>
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
                                    <h6 class="text-muted font-semibold">Total Amount</h6>
                                    <h6 class="font-extrabold mb-0" id="show_sms_balance">
                                        {{ $totalAmount }}</h6>
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

    <button type="button" class="btn btn-outline-primary block mb-5" data-bs-toggle="modal" data-bs-target="#addCodeModal">
        FILTER RECORDS
    </button>
    <!-- Vertically Centered modal Modal -->
    <div class="modal fade" id="addCodeModal" tabindex="-1" role="dialog" aria-labelledby="addCodeModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCodeModalTitle">SELECT DATES
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form action="{{ Route('filter') }}" method="post" id="radioForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="radio-vertical">From Date*</label>
                                    <input type="datetime-local" id="radio-vertical" class="form-control" name="from_date"
                                        placeholder="From Date*" max="{{ now()->format('Y-m-d\TH:i:s') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="payblii-vertical">To Date*</label>
                                    <input type="datetime-local" id="payblii-vertical" class="form-control" name="to_date"
                                        placeholder="To Date*" min="{{ now()->format('Y-m-d\TH:i:s') }}">
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
                            <span class="d-none d-sm-block">Filter</span>
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
                {{-- live wire table --}}
                @livewire('players-table')
            </div>
        </div>

    </section>
    <!-- Basic Tables end -->
@endsection

@section('pageJs')
    <script src="{{ asset('assets/vendors/jquery-datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-datatables/custom.jquery.dataTables.bootstrap5.min.js') }}"></script>
    <script>
        // Jquery Datatable
        let jquery_datatable = $("#players_table").DataTable({
            "order": [
                [0, "desc"]
            ]
        })
        var intervalId = window.setInterval(function() {
            /// call your function here
            Livewire.emit('getPlayers')
            // console.log(123)
        }, 10000);
    </script>
    <script>
        // Add event listener to "From Date" input
        document.getElementById('radio-vertical').addEventListener('change', function() {
            // Get the selected "From Date" value
            var fromDateValue = this.value;

            // Set the "To Date" input's min attribute to the selected "From Date" value
            document.getElementById('payblii-vertical').min = fromDateValue;
        });
    </script>
@endsection
