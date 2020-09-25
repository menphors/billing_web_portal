@extends('layouts.master')

@section('content')
    <h1>Your file was upload succesfully.....</h1>
    <h4>please find the information as below details!!!</h4>
  
    <h1 style="text-align:center; font-style:bold;">Query Information</h1>
        <div class="col-lg-12">
            <table class="table table-striped table-hover table-bordered" style="margin-top:25px;text-align:center; ">
                <tr class="bg-success text-white text-center">
                    <th>CUST_CODE</th>
                    <th>SERVICE_NUMBER</th>
                    <th>REMARK</th>
                    <th>Download File</th>
                    <!-- <h3 align ="center"> Export Data with Excel</h3><br/> -->
    <div align ="center">
        <a href="{{route('export')}}"
        class="btn btn-success">Export to Excel</a>
    
    </div>
      
                </tr>

                @foreach($data as $t)
                <tr class="text-center">
        	    <td>{{$t->CUST_CODE}}</td>
                <td>{{$t->SERVICE_NUMBER}}</td>
                <td>{{$t->REMARK}}</td>
        @endforeach  
        
             


            </table>

            @endsection




@section('js')
	<script>
        $(document).ready(function () {
            $("#sidebar-menu li ").removeClass("active open");
			$("#sidebar-menu li ul li").removeClass("active");
			
            $("#menu_batch_operation").addClass("active open");
			$("#batch_operation_collapse").addClass("collapse in");
            $("#batch_collection").addClass("active");
			
        });
    </script>
@endsection           