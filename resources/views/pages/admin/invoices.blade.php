@extends('layouts.adm')

@section('content')

  <body>
    


    <header>
      <div class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
          <a href="#" class="navbar-brand d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
              <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
              <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
            </svg>
            <strong>&nbsp;Admin Page</strong>
          </a>
          <li><a href="/" class="text-white">Log Out</a></li>
        </div>
      </div>
    </header>

    <main class="px-md-5">

    <section class="text-center container">
      <div class="row py-lg-4" >
          <p>
            <a href="/admin" class="btn btn-secondary my-2">Go Back</a>
          </p>
      </div>
    </section>

      <h2>View Invoices</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col" style="width: 20%">Order ID</th>
              <th scope="col" style="width: 25%">Username</th>
              <th scope="col" style="width: 25%">Total Price</th>
              <th scope="col" style="width: 10%">Action</th>
            </tr>
          </thead>
          <tbody class = "align-middle">

          @foreach($order_details as $order_detail)

            <tr>
              <td>{{$order_detail->id}}</td>
              <td>{{$order_detail->username}}</td>
              <td>Rp{{ number_format($order_detail->total, 2, ',', '.') }}</td>
              <td>
                <a class="btn btn-outline-primary my-1 showSidebar" value = "{{$order_detail->id}}" href ="{{route('app.invoice',$order_detail->id)}}">View</a>
              </td>
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

  for(let i=0; i < buttonOpen.length; i++){
    buttonOpen[i].addEventListener("click", ()=>{
        invoiceBox.style.visibility = "visible";
        let id = buttonOpen.value;
        
    });
  }

  for(let i = 0; i < buttonClose.length; i++){
    buttonClose[i].addEventListener("click", ()=>{
        invoiceBox.style.visibility = "hidden";
    });
  }
</script>
@include('partials/app-footer-II')