@extends('layouts.app', ['activePage' => 'commissions', 'titlePage' => __('Commissions')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Commissions</h4>
                        </div>

                        <div class="card-body">


                            <div class="row input-daterange">

                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="card card-stats">
                                    <div class="card-header card-header-success card-header-icon">
                                        <div class="card-icon">
                                            <i class="material-icons">euro_symbol</i>
                                        </div>
                                        <p class="card-category font-weight-bold">Buy Commission</p>
                                        <input type="text" name="buy_commission" id="buy_commission" class="form-control" data-width="750" value="{{$buy_commission}}"/>
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
                                            <i class="material-icons">euro_symbol</i>
                                        </div>
                                        <p class="card-category font-weight-bold">Sell Commission</p>
                                        <input type="text" name="sell_commission" id="sell_commission" class="form-control" value="{{$sell_commission}}"  />
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
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Commission Rate</th>
                                    <th>Commission Method</th>
                                    <th>Total</th>
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

        $(document).on('change', '#buy_commission,#sell_commission', function(){
            Swal.fire({
                title: 'Are you sure?',
                text: "This is a highly impactful change!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Save it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    var buy_commission =  $('#buy_commission').val();
                    var sell_commission = $('#sell_commission').val();

                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: 'requests/changeCommissions',
                        data: {'buy_commission': buy_commission, 'sell_commission': sell_commission},
                        success: function (data) {
                            swal.fire("",data.success, "success");
                        }
                    })
                }
            })
        });


            function load_data(from_date = '', to_date = '',filter_request_type=''){

                const FROM_PATTERN = 'YYYY-MM-DD HH:mm:ss.SSS';
                const TO_PATTERN   = 'DD/MM/YYYY HH:mm';

            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('commissions.getInvestmentsCommissions') }}",
                    type: 'GET',
                    data: function (d) {
                        d.from_date = from_date;
                        d.to_date = to_date;
                        d.filter_request_type = filter_request_type;

                    }
                },
                "columnDefs": [{
                    //render: $.fn.dataTable.render.moment( 'DD/MM/YYYY HH:mm' )
                    "render": function(data) {
                        return moment(data).format('DD/MM/YYYY HH:mm');
                    },
                    "targets": 1
                }],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'created_at', name: 'created_at' },
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'commission', name: 'commission'},
                    {data: 'action', name: 'action'},
                    {data: 'total', name: 'total'},
                ]
            });
        }

        $(document).ready(function(){

            function listings_swal() {
                return '<table class="table history-datatable"><thead class=" text-primary"><th>ID</th><th>Amount</th><th>Post Amount</th><th>Action</th><th>Worth Value</th><th>Commission</th><th>Created at</th></thead><tbody></tbody></table>';
            }

            var html = listings_swal();

            $(document).on('click', '.investor_history', function(){

            Swal.fire({
                title: 'Investment History',
                html: html,
                width: '1000px'
            });

            $id=  $(this).data('id');

            var table = $('.history-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('users.investments') }}",
                    type: 'GET',
                    data: function (d) {
                        d.user_id = $id;
                    }
                },
                "fnDrawCallback": function() {
                    $('.toggle-class').bootstrapToggle();
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'amount', name: 'amount'},
                    {data: 'post_amount', name: 'post_amount'},
                    {data: 'action', name: 'action'},
                    {data: 'worth_value', name: 'worth_value'},
                    {data: 'commission', name: 'commission'},
                    {data: 'created_at', name: 'created_at'},
                ]
            });
        });
        });


    </script>

@endpush


