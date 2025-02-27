@extends('dashboard.base')

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header d-flex flex-row justify-content-between">
                      <span class="card-title">{{ 'PreBookings' }}</span>
                      <a href="{{route('prebooking.create')}}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add </a>
                    </div>
                    <div class="card-body p-0">

                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th>ID</th>
                          
							<th>Supplier</th>
							  <th>Company</th>
                            <th>PFI Number</th>
							<th></th>

                          </tr>
                        </thead>
                        <tbody>

                            @foreach($prebookings as $prebooking)
                            <tr>
                              <td>{{$prebooking->id}}</td>
                              <td>{{$prebooking->supplier->supplier_code}}</td>
							   <td>{{$prebooking->company_name}}</td>
							   <td>{{$prebooking->pfi_no}}</td>
                              <td class="d-flex flex-row">
								 @if(in_array("prebooking-edit", $all_permission))
                                <a  href="{{ url('/prebooking/activate/' . $prebooking->id ) }}" class="btn  btn-primary btn-sm">Activate</a>
                                @endif
                                @if(in_array("prebooking-edit", $all_permission))
                                <a hidden href="{{ url('/prebooking/' . $prebooking->id . '/edit') }}" class="btn  btn-primary btn-sm">Edit</a>
                                @endif
                                @if(in_array("prebooking-delete", $all_permission))
                                  <form action="{{ route('prebooking.destroy', $prebooking->id  ) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item'); ">
                                      @method('DELETE')
                                      @csrf
                                      <button class="btn btn-danger btn-sm">Delete</button>
                                  </form>
                                @endif
                              </td>
                            </tr>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')
@if(Session::get('message'))
      <script>
        $(document).ready(function(){
          showToastr('success',"{{ session('message') }}");
        });
      </script>
@endif
@endsection
