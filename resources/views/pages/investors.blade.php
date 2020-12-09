@extends('layouts.app', ['activePage' => 'investors', 'titlePage' => __('Investors')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Investors</h4>
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
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Gender</th>
                                    <th>Country</th>
                                    <th>Currency Code</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
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
       <script>
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


        $(document).on('change', '.toggle-class', function(){
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id =  $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: 'users/changeUserStatus',
                data: {'status': status, 'user_id': user_id},
                success: function (data) {
                    swal("", "Successfully Updated!", "success");
                }
            })
        });

    </script>

    <script type="text/javascript">

        load_data();


        function search() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id =  $(this).data('id');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: 'users/changeUserStatus',
                data: {'status': status, 'user_id': user_id},
                success: function (data) {
                    swal("", "Successfully Updated!", "success");
                }
            })
        }

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
                swal("", "Both Date is required!", "warning");
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
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('users.list') }}",
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
                    {data: 'first_name', name: 'first_name', class:'first_name'},
                    {data: 'last_name', name: 'last_name', class:'last_name'},
                    {data: 'gender', name: 'gender'},
                    {data: 'country_code', name: 'country_code'},
                    {data: 'currency_code', name: 'currency_code'},
                    {data: 'email', name: 'email'},
                    {data: 'mobile', name: 'mobile'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },

                ]
            });
        }

        $(document).on('click', '.viewimages', function(){
            $id=  $(this).data('id');
            alert($id);
        });



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


