<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />

    <title>Invoice - IndoJuni</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="{{ asset('images/app/xyXVxK19116nI6TPT5KF.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style type="text/css">
        body {
            margin-top: 20px;
            background-color: #eee;
        }

        .card {
            box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0, 0, 0, 0.125);
            border-radius: 1rem;
        }
    </style>
</head>

<body class="bg-body-tertiary">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css"
        integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-dark">
                    <div class="card-body">
                        <div class="invoice-title">
                            <h4 class="float-end font-size-15">
                                Invoice #{{ $orderDetail->id }}
                            </h4>
                            <div class="mb-4">
                                <h2 class="mb-1 text-light">IndoJuni</h2>
                            </div>
                            <div class="text-light">
                                <p class="mb-1">Dk. Basoka No. 100, Lampung</p>
                                <p class="mb-1">
                                    indojuni@gmail.com
                                </p>
                                <p><i class="uil uil-phone me-1"></i> (+62) 26 1884 857</p>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-light">
                                    <h5 class="font-size-16 mb-3">Kustomer:</h5>
                                    <h5 class="font-size-15 mb-2">{{ $paymentDetail->firstname }}
                                        {{ $paymentDetail->lastname }}</h5>
                                    <p class="mb-1">{{ $paymentDetail->address }}</p>
                                    <p class="mb-1">{{ $paymentDetail->address2 }}</p>
                                    <p class="mb-1">
                                        {{ $paymentDetail->email }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="text-light text-sm-end">
                                    <div class="mt-4">
                                        <h5 class="font-size-15 mb-1">Invoice Date:</h5>
                                        <p>{{ $orderDetail->created_at }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="py-2">
                            <h5 class="font-size-15">Order Summary</h5>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap table-centered mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 70px">No.</th>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th class="text-end" style="width: 120px">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $key => $item)
                                            <tr>
                                                <th scope="row">{{ $key + 1 }}</th>
                                                <td>
                                                    <div>
                                                        <h5 class="text-truncate font-size-14 mb-1">
                                                            {{ $item['product_name'] }}
                                                        </h5>
                                                    </div>
                                                </td>
                                                <td>Rp{{ number_format($item['price'], 2, ',', '.') }}</td>
                                                <td>{{ $item['quantity'] }}</td>
                                                <td class="text-end">Rp{{ number_format($item['total'], 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th scope="row" colspan="4" class="text-end">
                                                Sub Total
                                            </th>
                                            <td class="text-end">
                                                Rp{{ number_format($orderDetail->total, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" colspan="4" class="border-0 text-end">
                                                Tax
                                            </th>
                                            <td class="border-0 text-end">
                                                Rp{{ number_format(floor($orderDetail->total * 0.11), 2, ',', '.') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row" colspan="4" class="border-0 text-end">
                                                Total
                                            </th>
                                            <td class="border-0 text-end">
                                                <h4 class="m-0 fw-semibold">
                                                    Rp{{ number_format($orderDetail->total + floor($orderDetail->total * 0.11), 2, ',', '.') }}
                                                </h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex float-end">
                                <a class="btn btn-success me-1 pt-2" href="{{ URL::previous() }}">
                                    <i class="fa-solid fa-arrow-left"></i>
                                    <span>Back</span>
                                </a>
                            </div>
                            {{-- <div class="d-print-none mt-4">
                                <div class="float-end">
                                    <a href="javascript:window.print()" class="btn btn-success me-1"><i
                                            class="fa fa-print"></i></a>
                                    <a href="#" class="btn btn-primary w-md">Send</a>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script type="text/javascript"></script>
</body>

</html>
