@extends('layouts.app', ['activePage' => 'reports-summary', 'titlePage' => __('Reports Summary')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Reports Summary</h4>
                        </div>

                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-chart">
                                        <div class="card-header card-header-success">
                                            <div class="ct-chart" id="dailySalesChart">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title">User Report</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-chart">
                                        <div class="card-header card-header-danger">
                                            <div class="ct-chart" id="investments"></div>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title">Investments</h4>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-chart">
                                        <div class="card-header card-header-info">
                                            <div class="ct-chart" id="requesttData"></div>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title">Completed Tasks</h4>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-chart">
                                        <div class="card-header card-header-danger">
                                            <div class="ct-chart" id="customerPayments"></div>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title">Payments</h4>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            </div>
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


        function load_data(from_date = '', to_date = ''){
            var table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('gold.getCustomerGold') }}",
                    type: 'GET',
                    data: function (d) {
                        d.from_date = from_date;
                        d.to_date = to_date;
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

        <script type="text/javascript">
            var userData = <?php echo json_encode($userData)?>;

            Highcharts.chart('dailySalesChart', {
                title: {
                    text: 'New User Growth, 2020'
                },
                subtitle: {
                },
                xAxis: {
                    categories: [ 'November', 'December','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                        'October'
                    ]
                },
                yAxis: {
                    title: {
                        text: 'Number of New Users'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    }
                },
                series: [{
                    name: 'New Users',
                    data: userData
                }],
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });


            var paymentData = <?php echo json_encode($paymentData)?>;

            Highcharts.chart('customerPayments', {
                title: {
                    text: 'Customer Payments, 2020'
                },
                subtitle: {
                },
                xAxis: {
                    categories: [  'October','November', 'December','January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',

                    ]
                },
                yAxis: {
                    title: {
                        text: 'Payments received'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                plotOptions: {
                    type: 'pie',
                    series: {
                        allowPointSelect: true
                    }
                },
                series: [{
                    name: 'Payments',
                    data: paymentData
                }],
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });


            var requestData = <?php echo json_encode($requestData)?>;

            Highcharts.chart('requesttData', {
                title: {
                    text: 'Customer Requests, 2020'
                },
                subtitle: {
                },
                xAxis: {
                    categories: [  'Cash Out','Gold Out', 'General','Questions']
                },
                yAxis: {
                    title: {
                        text: 'Payments received'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Requests',
                    data: requestData
                }],
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });

            var warehouseData = <?php echo json_encode($warehouseData)?>;

            Highcharts.chart('investments', {
                title: {
                    text: 'Customer Investments'
                },
                subtitle: {
                },
                xAxis: {
                    categories: [  'Cash Out','Gold Out', 'General','Questions']
                },
                yAxis: {
                    title: {
                        text: 'Payments received'
                    }
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },
                plotOptions: {
                    series: {
                        allowPointSelect: true
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Investments',
                    data: warehouseData
                }],
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom'
                            }
                        }
                    }]
                }
            });



        </script>

@endpush


