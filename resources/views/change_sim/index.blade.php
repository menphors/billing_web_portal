@extends('layouts.master')

@section('content')
    <div class="card card-gray">
        <div class="card-header">
            <div class="header-block">
                <p class="title">Change SIM</p>
            </div>
        </div>
        <hr>
        <div class="card-block">
        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div>
                    {{session('success')}}
                </div>
            </div>
        @endif
            <!-- start form -->
            <form action="{{route('change_sim')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="number">Phone Number List as Excel</label>
                    <input type="file"class="form-control-file" name="change_sim" id="change_sim" required>
                </div>
                <div class="form-group">
                    <label for="change_sim">Remark</label>
                    <textarea class="form-control" id="remark" name="remark" required> </textarea>
                </div>
                    <!-- accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>     

                             
            </form>

    
             
@endsection




@section('js')
	<script>
        $(document).ready(function () {
            $("#sidebar-menu li ").removeClass("active open");
			$("#sidebar-menu li ul li").removeClass("active");
			
            $("#menu_batch_operation").addClass("active open");
			$("#batch_operation_collapse").addClass("collapse in");
            $("#change_sim").addClass("active");
			
        });
    </script>
@endsection