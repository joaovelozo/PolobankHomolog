@extends('agency.includes.master')
@section('content')


@php
    $agencyId = Auth::user()->agency_id;

    $users = App\Models\User::where('agency_id', $agencyId)->get();
    $userCount = count($users);

    // Buscando somente as transações associadas à agência logada
    $transactions = App\Models\Transaction::where(function ($query) use ($agencyId) {
        $query->where('sender_id', $agencyId)
              ->orWhere('receiver_id', $agencyId);
    })->get();

    $transactionCount = count($transactions);

    $id = Auth::user()->id;
    $adminData = App\Models\User::find($id);
@endphp

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row page-title align-items-center">
              <div class="row">
                  <div class="col-md-3 col-xl-2">
                    <img src="{{asset('assets/backend/icon.png')}}" class="img-fluid" alt="Logo" width="200px">
                  </div>
                  <div class="col-md-9 col-xl-4 align-self-center">
                      <h4 class="mb-1 mt-0">Dashboard</h4>
                  </div>
              </div>
            </div>
        </div>
    </div>
          <!-- content -->
          <div class="row justify-content-center">
              <div class="col-md-6 col-xl-3">
                  <div class="card">
                      <div class="card-body p-0">
                          <div class="media p-3">
                              <div class="media-body">
                                  <span class="text-muted text-uppercase font-size-12 font-weight-bold">Clientes</span>
                                  <h2 class="mb-0">{{ $userCount}}</h2>
                              </div>
                              <div class="align-self-center">
                                  <div id="today-revenue-chart" class="apex-charts"></div>
                                  <span class="text-success font-weight-bold font-size-13"><i class="fa-solid fa-users"></i> 10.21%</span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="col-md-6 col-xl-3">
                  <div class="card">
                      <div class="card-body p-0">
                          <div class="media p-3">
                              <div class="media-body">
                                  <span class="text-muted text-uppercase font-size-12 font-weight-bold">Transações</span>
                                  <h2 class="mb-0">{{$transactions}}</h2>
                              </div>
                              <div class="align-self-center">
                                  <div id="today-product-sold-chart" class="apex-charts"></div>
                                  <span class="text-danger font-weight-bold font-size-13"><i
                                          class='uil uil-arrow-down'></i> 5.05%</span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>



          <!-- row -->

          <!-- products -->
          <div class="row">
              <div class="col-xl-5">
                  <div class="card">
                      <div class="card-body">
                          <h5 class="card-title mt-0 mb-0 header-title">Sales By Category</h5>
                          <div id="sales-by-category-chart" class="apex-charts mb-0 mt-4" dir="ltr"></div>
                      </div> <!-- end card-body-->
                  </div> <!-- end card-->
              </div> <!-- end col-->
              <div class="col-xl-7">
                  <div class="card">
                      <div class="card-body">
                          <a href="" class="btn btn-primary btn-sm float-right">
                              <i class='uil uil-export ml-1'></i> Export
                          </a>
                          <h5 class="card-title mt-0 mb-0 header-title">Recent Orders</h5>

                          <div class="table-responsive mt-4">
                              <table class="table table-hover table-nowrap mb-0">
                                  <thead>
                                      <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">Product</th>
                                          <th scope="col">Customer</th>
                                          <th scope="col">Price</th>
                                          <th scope="col">Status</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <tr>
                                          <td>#98754</td>
                                          <td>ASOS Ridley High</td>
                                          <td>Otto B</td>
                                          <td>$79.49</td>
                                          <td><span class="badge badge-soft-warning py-1">Pending</span></td>
                                      </tr>
                                      <tr>
                                          <td>#98753</td>
                                          <td>Marco Lightweight Shirt</td>
                                          <td>Mark P</td>
                                          <td>$125.49</td>
                                          <td><span class="badge badge-soft-success py-1">Delivered</span>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td>#98752</td>
                                          <td>Half Sleeve Shirt</td>
                                          <td>Dave B</td>
                                          <td>$35.49</td>
                                          <td><span class="badge badge-soft-danger py-1">Declined</span>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td>#98751</td>
                                          <td>Lightweight Jacket</td>
                                          <td>Shreyu N</td>
                                          <td>$49.49</td>
                                          <td><span class="badge badge-soft-success py-1">Delivered</span>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td>#98750</td>
                                          <td>Marco Shoes</td>
                                          <td>Rik N</td>
                                          <td>$69.49</td>
                                          <td><span class="badge badge-soft-danger py-1">Declined</span>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div> <!-- end table-responsive-->
                      </div> <!-- end card-body-->
                  </div> <!-- end card-->
              </div> <!-- end col-->
          </div>
          <!-- end row -->



                          </div> <!-- end .chat-conversation-->
                      </div>
                  </div>
              </div>
          </div>
          <!-- end row -->

      </div>
  </div> <!-- content -->


@endsection
