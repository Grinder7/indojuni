@extends('layouts.admin')

@section('content')
    <div class="h-100 d-flex flex-column">
        <div class="row row-cols-2" style="height: 66%">
            <div class="col-9 d-flex flex-column h-100">
                <div class="h-100 d-flex justify-content-center align-items-center">
                    Transaction Chart
                </div>
                <div class="position-relative w-100 flex-grow-1 flex-shrink-1 h-100">
                    <canvas id="weeklyTransactionsChart" style="width: 100%; height: 100%;"></canvas>
                </div>

            </div>
            <div class="col-3 row row-cols-1 align-items-center">
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Sales</h5>
                            <p class="card-text">Rp{{ number_format($monthlySales, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">User</h5>
                            <p class="card-text">{{ $userCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Product</h5>
                            <p class="card-text">{{ $productCount }}</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Transaction</h5>
                            <p class="card-text">{{ $transactionCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-grow-1 mt-3">
            <h4>Latest Transaction</h4>
            <div class="table-responsive">
                <table class="table-striped table">
                    <thead>
                        <tr>
                            <th scope="col">Order ID</th>
                            <th scope="col">Username</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody class = "align-middle">

                        @foreach ($fiveLatestOrders as $order_detail)
                            <tr>
                                <td><a href="{{ route('app.invoice', $order_detail->id) }}">{{ $order_detail->id }}</a></td>
                                <td>{{ $order_detail->user->username }}</td>
                                <td>Rp{{ number_format($order_detail->total, 2, ',', '.') }}</td>
                                <td>{{ $order_detail->created_at }}</td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"
        integrity="sha512-ZwR1/gSZM3ai6vCdI+LVF1zSq/5HznD3ZSTk7kajkaj4D292NLuduDCO1c/NT8Id+jE58KYLKT7hXnbtryGmMg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="{{ asset('js/adminDashboard.js') }}"></script>
@endsection
