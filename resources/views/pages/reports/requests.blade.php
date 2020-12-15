@extends('layouts.app', ['activePage' => 'report-requests', 'titlePage' => __('Requests')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Requests Report</h4>
                        </div>

                        <div class="card-body">

                            <div class="row input-daterange">
                                <div class="col-md-4">
                                    <input type="text" name="from_date" id="from_date" class="form-control datepicker" placeholder="From Date"  />
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="to_date" id="to_date" class="form-control datepicker" placeholder="To Date"  />
                                </div>
                                <div class="col-md-4">
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
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
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

            if(from_date != '' &&  to_date != '')
            {
                $('.yajra-datatable').DataTable().destroy();
                load_data(from_date, to_date);
            }
            else
            {
                swal.fire("", "Both Date is required!", "warning");
            }
        })

        $('#refresh').click(function(){
            $('#from_date').val('');
            $('#to_date').val('');
            $('.yajra-datatable').DataTable().destroy();
            load_data();
        });


        function load_data(from_date = '', to_date = ''){
            var table = $('.yajra-datatable').DataTable({
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                processing: true,
                serverSide: true,
                dom: 'fBlrptip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Request Report'+ from_date + to_date,
                        className: "btn btn-info",
                        style: "paddingRight:10px",
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5,6 ]
                        }

                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Request Report'+ from_date + to_date,
                        className: "btn btn-warning",
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4, 5,6 ]
                        }
                    }
                ],
                ajax: {
                    url: "{{route('requests.getCustomerRequests') }}",
                    type: 'GET',
                    data: function (d) {
                        d.from_date = from_date;
                        d.to_date = to_date;
                    }
                },
                "fnDrawCallback": function() {
                    $('.toggle-class').bootstrapToggle();
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'created_at', name: 'created_at' },
                    {data: 'name', name: 'name'},
                    {data: 'request_type', name: 'request_type'},
                    {data: 'amount', name: 'amount'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                ]
            });
        }

    </script>

@endpush


