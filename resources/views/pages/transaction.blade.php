@extends('layouts.app')
@section('title', 'Transaction')
@section('styles')
    <style>
        .avatar.sm {
            width: 2.25rem;
            height: 2.25rem;
            font-size: .818125rem;
        }

        .table-nowrap .table td,
        .table-nowrap .table th {
            white-space: nowrap;
        }

        .table>:not(caption)>*>* {
            padding: 0.75rem 1.25rem;
            border-bottom-width: 1px;
        }

        table th {
            font-weight: 600;
            background-color: #252a2e !important;
        }

        .fa-arrow-up {
            color: #00CED1;
        }

        .fa-arrow-down {
            color: #FF00FF;
        }
    </style>
@endsection
@section('content')
    <div class="container" style="padding-top:6rem">
        <h1>
            Halo, {{ Auth::user()->username }}
        </h1>
        <p>Dibawah ini adalah daftar semua transaksi yang sudah pernah dilakukan di website ini</p>
        @if ($transactions)
            <div class="row">
                <div class="col-12 mb-lg-5 mb-3">
                    <div class="position-relative card table-nowrap table-card">
                        <div class="table-responsive">
                            <table class="mb-0 table">
                                <thead class="small text-uppercase bg-body text-muted">
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Date</th>
                                        <th>Delivery Address</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        <tr class="align-middle">
                                            <td><a href="/invoice/{{ $transaction['id'] }}">{{ $transaction['id'] }}</a></td>
                                            <td>{{ $transaction['created_at'] }}</td>
                                            <td>{{ $transaction->payment()->first()['address'] }} &nbsp;
                                                {{ $transaction->payment()->first()['address2'] }}</td>
                                            <td> Rp{{ number_format($transaction['total'] + floor($transaction['total'] * 0.11), 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
