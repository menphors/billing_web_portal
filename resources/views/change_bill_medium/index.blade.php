@extends('layouts.master')

@section('content')
    <div class="card card-gray">
        <div class="card-header">
            <div class="header-block">
                <p class="title">Change Bill Medium</p>
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
            <form action="{{route('change_bill_medium')}}" 
                                method="POST" 
                                enctype="multipart/form-data">
                                
                            @csrf
                                                 

                            <div class="form-group row"> 

                            <label for="acct_id" class="col-sm-4">ACCT_ID & MSISDN</label>
                            <div class="col-sm-8">
                                    <input type="file"  
                                           class="form-control-file" name="acct_id" id="acct_id" required>   
                            </div>
                            <label for="medium_id" class="col-sm-4">Select bill</label>
                            <div class="col-sm-8">
                                <select id="medium_id" name='medium_id' class="form-control">   <!-- value=medium_id -->
                                    <option value="">Select Bill Medium</option>
                                    <option value="585282034" id="585282034">Ebill_Naga</option>
                                    <option value="1084702653" id="1084702653">Ebill_Normal</option>
                                    <option value="1184702815" id="1184702815">Email_summary_Master Invoice</option>
                                    <option value="1287210869" id="1287210869">Email_summary_Naga Invoice</option>
                                    <option value="1084702566" id="1084702566">Email_summary+detail_Master Invoice</option>
                                    <option value="1387286199" id="1387286199">Email_summary+detail_Naga Invoice</option>
                                    <option value="943864158" id="943864158">Paper_Bill_Testing</option>
                                    <option value="1384702895" id="1384702895">Paper_summary_Naga Invoice</option>
                                    <option value="1484702340" id="1484702340">Paper_summary+detail_Master Invoice</option>
                                    <option value="1087196528" id="1087196528">Paper_summary+detail_Naga Invoice</option>
                                    <option value="1685279892" id="1685279892">SMS_naga</option>
                                    <option value="1384703017" id="585282034">SMS_normal</option>
                                </select> 
                            </div>
                            <label for="medium_code" class="col-sm-4">Select bill type</label>
                            <div class="col-sm-8">
                                <select id="medium_type" name='medium_type' class="form-control">   <!-- value=medium_id -->
                                    <option value="">Select Bill Type</option>
                                    <option value="1" id="1">Paper</option>
                                    <option value="2" id="2">SMS</option>
                                    <option value="3" id="3">Email</option>
                                    <option value="4" id="4">Fax</option>
                                    <option value="5" id="5">Ebill</option>
                                </select> 
                            </div>
                            
                            
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
            $("#changebillmedium").addClass("active");
			
        })
    </script>
@endsection