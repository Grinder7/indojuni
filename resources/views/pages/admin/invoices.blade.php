@extends('layouts.admin')
@section('title', 'Invoices - IndoJuni')
@section('content')
    <main class="px-md-5">
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

                    @foreach ($order_details as $order_detail)
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
    </main>
    </div>
    </div>

    <script>
        let buttonOpen = document.getElementsByClassName("showSidebar");
        let buttonClose = document.getElementsByClassName("hideSidebar");

        for (let i = 0; i < buttonOpen.length; i++) {
            buttonOpen[i].addEventListener("click", () => {
                invoiceBox.style.visibility = "visible";
                let id = buttonOpen.value;

            });
        }

        for (let i = 0; i < buttonClose.length; i++) {
            buttonClose[i].addEventListener("click", () => {
                invoiceBox.style.visibility = "hidden";
            });
        }
    </script>
@endsection
