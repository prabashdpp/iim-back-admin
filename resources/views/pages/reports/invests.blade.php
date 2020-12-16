@extends('layouts.app', ['activePage' => 'report-invests', 'titlePage' => __('Investments Report')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Investments Report</h4>
                        </div>

                        <div class="card-body">

                            <div class="row input-daterange">
                                <div class="col-md-3">
                                    <input type="text" name="from_date" id="from_date" class="form-control datepicker" placeholder="From Date"  />
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="to_date" id="to_date" class="form-control datepicker" placeholder="To Date"  />
                                </div>
                                <div class="col-md-3">
                                    <select name="filter_request_type" id="filter_request_type" class="form-control" required>
                                        <option value="-1">All</option>
                                        <option value="{{\App\Constants\AppConstants::BUY}}">Buy</option>
                                        <option value="{{\App\Constants\AppConstants::SELL}}">Sell</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                                    <button type="button" name="refresh" id="refresh" class="btn btn-default">Refresh</button>
                                </div>
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table yajra-datatable">
                                    <thead class=" text-primary">
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Gold Amount</th>
                                    <th>Warehouse Post Amount</th>
                                    <th>Action</th>
                                    <th>Worth Value</th>
                                    <th>Trade Value</th>
                                    <th>Commission</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('js')

    <script type="text/javascript">

        $( function() {
            $( ".datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "dd-mm-yy",
                maxDate: 0,
                autoClose: true,
                clearBtn: true
            });
        });


        load_data();

        $('#filter').click(function(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var filter_request_type  = $('#filter_request_type').val();

            $('.yajra-datatable').DataTable().destroy();

            load_data(from_date, to_date,filter_request_type);
        })

        $('#refresh').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('.yajra-datatable').DataTable().destroy();
            load_data();
        });


        function load_data(from_date = '', to_date = '',filter_request_type=''){
            var table = $('.yajra-datatable').DataTable({
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                processing: true,
                serverSide: true,
                dom: 'fBlrptip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Invests Report'+ from_date + to_date,
                        className: "btn btn-info",
                        style: "paddingRight:10px",
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5,6,7 ]
                        }

                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Invests Report'+ from_date + to_date,
                        className: "btn btn-warning",
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5,6,7 ]
                        }
                    }
                ],
                ajax: {
                    url: "{{route('reports.getInvestments') }}",
                    type: 'GET',
                    data: function (d) {
                        d.from_date = from_date;
                        d.to_date = to_date;
                        d.filter_request_type = filter_request_type;

                    }
                },
                "fnDrawCallback": function() {
                    $('.toggle-class').bootstrapToggle();
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'created_at', name: 'created_at' },
                    {data: 'name', name: 'name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'post_amount', name: 'post_amount'},
                    {data: 'action', name: 'action'},
                    {data: 'worth_value', name: 'worth_value'},
                    {data: 'trade_value', name: 'trade_value'},
                    {data: 'total_commission', name: 'total_commission'},
                  ]
            });
        }

    </script>

@endpush


