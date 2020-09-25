@extends('layouts.master')

@section('content')
    <div class="card card-gray">
        <div class="card-header">
            <div class="header-block">
                <p class="title">Add Supplement Offering</p>
            </div>
        </div>
        <hr>
        <div class="card-block">
            <!-- start form -->
                <form action="{{route('add_supplement_offer')}}" method="POST" enctype="multipart/form-data">  
                    @csrf
                    <div class="form-group">
                            <label for="msisdns">Phone Number List as Excel</label>
                            <input type="file"  
                                    class="form-control-file" name="msisdns" id="msisdns" required>
                    </div>
                    <!-- accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" -->
                    <div class="form-group">
                        <label for="new_primary_offering">New Primary Offering</label>
                        <input type="text" class="form-control" name="new_primary_offering" id="new_primary_offering" required>
                        </div>
                        <div class="form-group">
                        <label for="effective_mode">Effective Mode &nbsp;</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="effective_mode" id="inlineRadio1" value="effective_immediately" checked>
                                <label class="form-check-label" for="inlineRadio1">Immediately</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="effective_mode" id="inlineRadio1" value="next_bill_cycle"  >
                                <label class="form-check-label" for="inlineRadio1">Next Bill Cycle</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="effective_mode" id="inlineRadio2" value="effective_schedule">
                                <label class="form-check-label" for="inlineRadio2">Appointed</label>
                            </div>
                            <div class="form-group">
                                <input disabled type="datetime-local" class="form-control" id="effective_schedule" name="effective_schedule" required>
                            </div>
                        </div>
                        <div class="form-group">
                        <label for="effective_mode">Execution Mode &nbsp;</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="execution_mode" id="inlineRadio4" value="execute_immediately" checked>
                                <label class="form-check-label" for="inlineRadio4">Immediately</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="execution_mode" id="inlineRadio5" value="execute_schedule">
                                <label class="form-check-label" for="inlineRadio5">Run at</label>
                            </div>
                                
                            <div class="form-group">
                                    <input disabled type="datetime-local" class="form-control" id="execute_schedule" name="execute_schedule" required>
                            </div>
                        </div>

                        <div class="form-group">
                        <label for="new_primary_offering">Remark</label>
                        <textarea class="form-control" id="remark" name="remark" required> </textarea>
                        </div>
                        
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
        // A $( document ).ready() block.
        $( document ).ready(function() {
            console.log( "ready!" );
            $('.form-check-input').change(function(val){

                if(val.target.value == "execute_schedule"){
                    $("#execute_schedule").prop('disabled', false);
                }else if(val.target.value == "execute_immediately"){
                    $("#execute_schedule").prop('disabled', true);
                }

                if(val.target.value == "effective_schedule"){
                    $("#effective_schedule").prop('disabled', false);
                }else if(val.target.value == "next_bill_cycle"){
                    $("#effective_schedule").prop('disabled', true);
                }else if(val.target.value == "effective_immediately"){
                    $("#effective_schedule").prop('disabled', true);
                }
            })
        });
        $(document).ready(function () {
            $("#sidebar-menu li ").removeClass("active open");
			$("#sidebar-menu li ul li").removeClass("active");
			
            $("#menu_batch_operation").addClass("active open");
			$("#batch_operation_collapse").addClass("collapse in");
            $("#home").addClass("active");
			
        })
    </script>
@endsection
