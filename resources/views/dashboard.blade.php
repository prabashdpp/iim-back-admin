@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
              <div class="card-icon">
                <i class="material-icons">people_alt</i>
              </div>
              <p class="card-category">Total Signups</p>
              <h4 class="card-title">{{$total_signups}}
              </h4>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-danger">warning</i>
                <a href="#pablo">Get More Space...</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
              <div class="card-icon">
                <i class="material-icons">request_page</i>
              </div>
              <p class="card-category">Total Requests</p>
              <h4 class="card-title">Cash Requests: {{$cash_requests}}</h4>
                <h4 class="card-title">Gold Requests: {{$gold_requests}}</h4>
                <h4 class="card-title">Other Requests: {{$other_requests}}</h4>

            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">date_range</i> Last 24 Hours
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-danger card-header-icon">
              <div class="card-icon">
                <i class="material-icons">account_balance</i>
              </div>
              <p class="card-category">Total Transactions</p>
                <h4 class="card-title">Buy: {{$buy_total}} &#163;</h4>
                <h4 class="card-title">Sell: {{$sell_total}} &#163;</h4>            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">local_offer</i> Tracked from Github
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
              <div class="card-icon">
                <i class="fa fa-twitter"></i>
              </div>
              <p class="card-category">Commission Rates</p>
                <h4 class="card-title">Buy Commission: {{$buy_commission}}</h4>
                <h4 class="card-title">Sell Commission: {{$sell_commission}} </h4>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons">update</i> Just Updated
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-success">
              <div class="ct-chart" id="UsersChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Users</h4>
              <p class="card-category">
            </div>
            <div class="card-footer">
              <div class="stats">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-warning">
              <div class="ct-chart" id="investments"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Investments</h4>
            </div>
            <div class="card-footer">
              <div class="stats">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-chart">
            <div class="card-header card-header-danger">
              <div class="ct-chart" id="requestChart"></div>
            </div>
            <div class="card-body">
              <h4 class="card-title">Requests</h4>
            </div>
            <div class="card-footer">
              <div class="stats">
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
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
  </script>
  <script type="text/javascript">
      var userData = <?php echo json_encode($userData)?>;

      Highcharts.chart('UsersChart', {
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



      var requestData = <?php echo json_encode($requestData)?>;

      Highcharts.chart('requestChart', {
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
              text: 'Customer Investments, 2020'
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
