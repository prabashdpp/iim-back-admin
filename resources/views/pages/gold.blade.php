@extends('layouts.app', ['activePage' => 'gold', 'titlePage' => __('Gold')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Gold</h4>
                        </div>

                        <div class="card-body">


                            <div class="row input-daterange">

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card card-stats">
                                    <div class="card-header card-header-success card-header-icon">
                                        <div class="card-icon">
                                            <i class="material-icons">account_balance</i>
                                        </div>
                                        <p class="card-category font-weight-bold">Total Gold : <span id="warehouse_gold_amount">{{$warehouse_gold_amount}}</span> {{\App\Constants\AppConstants::INSTRUMENT_UNIT}}</p>
                                        <input type="text" name="add_gold_amount" id="add_gold_amount" class="form-control" data-width="750" placeholder="Add Gold to Warehouse"/>
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="material-icons">date_range</i>{{$updated_at}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card card-stats">
                                    <div class="card-header card-header-danger card-header-icon">
                                        <div class="card-icon">
                                            <i class="material-icons">payments</i>
                                        </div>
                                        <p class="card-category font-weight-bold">Total Cash : <span id="warehouse_cash_amount">{{$warehouse_cash_amount}}</span> {{\App\Constants\AppConstants::INSTRUMENT_CURRENCY}}</p>
                                        <input type="text" name="add_cash_amount" id="add_cash_amount" class="form-control" placeholder="Add Cash to Warehouse"  />
                                    </div>
                                    <div class="card-footer">
                                        <div class="stats">
                                            <i class="material-icons">date_range</i>{{$updated_at}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <hr/>


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
                                    <th>Gold Weight</th>
                                    <th>Worth Value</th>
                                    <th>Trade Value</th>
                                    <th>Commission Rate</th>
                                    <th>Action</th>
                                    <th>Commission</th>
                                    <th>Options</th>
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
            $(document).ready(function(){

                $('#add_gold_amount').val('');
                $('#add_cash_amount').val('');

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


            $(document).on('change', '#add_gold_amount,#add_cash_amount', function(){
            Swal.fire({
                title: 'Are you sure to add?',
                text: "This is a highly impactful change!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Add it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    var add_gold_amount =  $('#add_gold_amount').val();
                    var add_cash_amount = $('#add_cash_amount').val();

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: 'gold/editWarehouseGold',
                        data: {'add_gold_amount': add_gold_amount, 'add_cash_amount': add_cash_amount},
                        success: function (data) {
                            $('#add_gold_amount').val('');
                            $('#add_cash_amount').val('');
                            $('#warehouse_gold_amount').text(data.warehouse_data.warehouse_gold_amount);
                            $('#warehouse_cash_amount').text(data.warehouse_data.warehouse_cash_amount);
                            swal.fire("",data.success, "success");
                        }
                    })
                }
            })
        });


            function load_data(from_date = '', to_date = '',filter_request_type=''){
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('gold.getCustomerGold') }}",
                    type: 'GET',
                    data: function (d) {
                        d.from_date = from_date;
                        d.to_date = to_date;
                        d.filter_request_type = filter_request_type;

                    }
                },

                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'created_at', name: 'created_at' },
                    {data: 'name', name: 'name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'worth_value', name: 'worth_value'},
                    {data: 'trade_value', name: 'trade_value'},
                    {data: 'commission', name: 'commission'},
                    {data: 'action', name: 'action'},
                    {data: 'total', name: 'total'},
                    {
                        data: 'options',
                        name: 'options',
                        orderable: true,
                        searchable: true
                    }
                ]
            });
        }

        $(document).ready(function(){

            function listings_swal() {
                return '<table class="table view_user_datatable mdl-data-table"><thead class=" text-primary"><th>ID</th><th>First Name</th><th>Last Name</th><th>Gender</th><th>Country</th><th>Currency</th><th>Email</th><th>Mobile</th><th>Created at</th></thead><tbody></tbody></table>';
            }

            var html = listings_swal();

            $(document).on('click', '.view_user', function(){

            Swal.fire({
                title: 'User Details',
                html: html,
                width: '1000px'
            });

            $id=  $(this).data('id');

            var table = $('.view_user_datatable').DataTable({
                processing: true,
                serverSide: true,
                paging:   false,
                ordering: false,
                info:     false,
                searching : false,
                ajax: {
                    url: "{{route('gold.getUsers') }}",
                    type: 'GET',
                    data: function (d) {
                        d.user_id = $id;
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'gender', name: 'gender'},
                    {data: 'country_code', name: 'country_code'},
                    {data: 'currency_code', name: 'currency_code'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                    {data: 'created_at', name: 'created_at'},
                ]
            });
        });
        });


    </script>

@endpush


