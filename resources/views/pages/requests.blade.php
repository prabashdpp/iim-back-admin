@extends('layouts.app', ['activePage' => 'requests', 'titlePage' => __('Requests')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title ">Requests</h4>
                        </div>
                        <div class="card-body">
                            <div class="row input-daterange">

                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card card-stats">
                                        <div class="card-header card-header-success card-header-icon">
                                            <div class="card-icon">
                                                <i class="material-icons">support_agent</i>
                                            </div>
                                            <p class="card-category">Requests</p>
                                            <h4 class="card-title">Cash Requests: {{$cash_requests}}</h4>
                                            <h4 class="card-title">Gold Requests: {{$gold_requests}}</h4>
                                            <h4 class="card-title">Other Requests: {{$other_requests}}</h4>

                                        </div>
                                        <div class="card-footer">
                                            <div class="stats">
                                                <i class="material-icons">date_range</i>{{$last_updated}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <hr/>


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
                        processing: true,
                        serverSide: true,
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
                            {
                                data: 'options',
                                name: 'options',
                                orderable: true,
                                searchable: true
                            }
                        ]
                    });
                }

                $(document).on('change', '.toggle-class', function(){
                    var status = $(this).prop('checked') == true ? 2 : 1;
                    var request_id =  $(this).data('id');

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: 'requests/changeRequestStatus',
                        data: {'status': status, 'request_id': request_id},
                        success: function (data) {
                            swal.fire("", "Successfully Updated!", "success");
                        }
                    })
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


                /* Accept or reject requests of the customers*/
                $(document).on('click', '.request_action', function(){
                    Swal.fire({
                        title: 'Action for the Customer Request',
                         html: '<textarea class="swal2-textarea" id="message" style="display: flex;" placeholder="Add message to describe what happening to Customer"></textarea>',
                        icon: 'info',
                        showCancelButton: true,
                        showDenyButton:true,
                        confirmButtonColor: '#3085d6',
                       // cancelButtonColor: '#d33',
                        confirmButtonText: 'Accept',
                        denyButtonText: 'Reject',
                        width: '550px'


                    }).then((result) => {

                        if (result.isConfirmed) {
                            var acceptRequest=1;
                        }
                        else if(result.isDenied) {
                            if($("#message").val()==''){
                                swal.fire("","Description required when reject a request", "warning");
                                e.preventDefault();
                            }
                            var acceptRequest=0;
                        }
                        else{
                            e.preventDefault();
                        }

                        if(result.isConfirmed || result.isDenied){
                            var requestId =  $(this).data('id');
                            var message =$('#message').val();

                            $.ajax({
                                type: "POST",
                                dataType: "json",
                                url: 'requests/replyRequests',
                                data: {'acceptRequest': acceptRequest, 'requestId': requestId,'message':message},
                                success: function (data) {
                                    swal.fire("",data.success, "success");
                                }
                            });
                        }

                        });
                });

                /* Reply to the customer general requests*/
                $(document).on('click', '.request_reply', function(){
                    Swal.fire({
                        title: 'Reply Customer Request',
                        html: '<textarea class="swal2-textarea" id="message" style="display: flex;" placeholder="Enter Description to tell what happening to Customer"></textarea>',
                        icon: 'info',
                        showCancelButton: true,
                        showDenyButton:true,
                        confirmButtonColor: '#47a44b',
                        denyButtonColor: '#3085d6',
                        // cancelButtonColor: '#d33',
                        confirmButtonText: 'Mark as Resolve',
                        denyButtonText: 'Reply',
                        width: '550px'

                    }).then((result) => {

                        if (result.isConfirmed) {
                            var markAsResolved=1;
                        }
                        else if(result.isDenied) {
                            if($("#description").val()==''){
                                swal.fire("","Description required when reply for a request", "warning");
                                e.preventDefault();
                            }
                            var markAsResolved=0;
                            var isReplied=1;
                        }
                        else{
                            e.preventDefault();
                        }

                        if(result.isConfirmed || result.isDenied){
                            var requestId =  $(this).data('id');

                            $.ajax({
                                type: "POST",
                                dataType: "json",
                                url: 'requests/replyRequests',
                                data: {'markAsResolved': markAsResolved, 'isReplied': isReplied},
                                success: function (data) {
                                    swal.fire("",data.success, "success");
                                }
                            });
                        }

                    });
                });

            </script>

    @endpush


