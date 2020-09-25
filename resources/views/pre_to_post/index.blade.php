@extends('layouts.master')

@section('content')
    <div class="card card-gray">
        <div class="card-header">
            <div class="header-block">
                <p class="title">Pre To Post</p>
            </div>
        </div>
        <hr>
        <div class="card-block">
            <!-- start form -->
            <form action="{{route('change_pre_to_post')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="number">Phone Number List as Excel</label>
                    <input type="file"  
                    class="form-control-file" name="number" id="number" required>
                </div>
                <div class="form-group">
                    <label for="new_primary_offering">Remark</label>
                    <textarea class="form-control" id="remark" name="remark" required> </textarea>
                    </div>
                <!-- accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" --> 
                    <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Submit</button>
                    </div>
            </form>
            <!-- end form -->

            <!-- report table -->
            <div class="table-flip-scroll">
                <table class="table table-sm table-bordered table-hover flip-content">
                    <thead class="flip-header">
                        <tr> 
                            <th scope="col">Remark</th>
                            <th scope="col">Executed_Date</th>
                            <th scope="col">Executed_By</th>
                            <th scope="col">Amount</th>       
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customer_info as $completed_trn)
                        <tr> 
                            <td>{{$completed_trn->Remark}}</td>
                            <td>{{$completed_trn->Executed_Date}}</td>
                            <td>{{$completed_trn->user_name}}</td>
                            <td>{{$completed_trn->Amount}}</td>                                            
                        </tr>
                        @endforeach
                    
                    </tbody>
                </table>
                {{$customer_info->links()}}
            </div>
            <!-- end report table -->
        </div>
    </div>
@endsection
@section('js')
	<script>
        $(document).ready(function () {
            $("#sidebar-menu li ").removeClass("active open");
			$("#sidebar-menu li ul li").removeClass("active");
			
            $("#menu_batch_operation").addClass("active open");
			$("#batch_operation_collapse").addClass("collapse in");
            $("#changepretopost").addClass("active");
			
        })
    </script>
@endsection