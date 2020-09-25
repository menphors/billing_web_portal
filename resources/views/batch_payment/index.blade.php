@extends('layouts.master')
@section('content')
    <div class="card card-gray">
        <div class="card-header">
            <div class="header-block">
            <p class="title"> Batch Payment </p>
            </div>
        </div>
        <hr>
        <div class="card-block">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <form action="{{url('batch_payment/save')}}" Method="POST">
                    {{csrf_field()}}
                        <div class="form-group row">
                            <label for="master_acc" class="col-sm-4">Master Account</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="master_acc" name="master_acc" auto-focus required placeholder="Input master account">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Remark</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="remark" name="remark" placeholder="Remark">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Exclude Number</label>
                            <div class="col-sm-8">
                            <input type="file" name="import_file" id="import_file">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Bill Cycle</label>
                            <div class="col-sm-8">
                                <select id="bill_cycle" name="bill_cycle" class="form-control">
                                    <option value="">---Select Bill Cycle---</option>
                                    <option value="111">Specific Bill Cycle</option>
                                    <option value="222">All Bill Cycle</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="specific_date" style="display:none;">
                            <label class="col-sm-4">Date</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="date" name="date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">Method</label>
                            <div class="col-sm-8">
                                <select name="method" id="method" class="form-control">
                                    <option value="">---Select Method---</option>
                                    <option value="100">Cash</option>
                                    <option value="200">Pay Slip</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="bank_number" style="display:none;">
                            <label class="col-sm-4">Bank Name</label>
                            <div class="col-sm-8">
                                <select name="" id="" class="form-control">
                                    <option value="">--Bank--</option>
                                    <option value="">ABA</option>
                                    <option value="">ACLeda</option>
                                    <option value="">Amret</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" id="cheque_number" style="display:none;">
                            <label class="col-sm-4">Cheque Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="cheque_number" name="cheque_number" placeholder="XXXXXXXXXXXX">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4">Payment Choice</label>
                            <div class="col-sm-8">
                                <select name="" id="" class="form-control">
                                    <option value="">--Payment Choice--</option>
                                    <option value="bill_amount" id="bill_amount">Bill Amount</option>
                                    <option value="amount_due" id="amount_due">Amount Due</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4">&nbsp;</label>
                            <div class="col-sm-8">
                                <button class="btn btn-primary btn-oval">
                                    <i class="fa fa-save"></i> Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
        //Script Hide and Show
        $(document).ready(function(){
			//Bill Cycle
			$('#bill_cycle').on('change', function() {
				if (this.value === '111') {
					$("#specific_date").show();
				} else {
					$("#specific_date").hide();
				}
			});
            //Method
            $('#method').on('change', function() {
				if (this.value === '200') {
					$("#bank_number, #cheque_number").show();
				} else {
					$("#bank_number, #cheque_number").hide();
				}
			});
		});
</script>
<script>
    $(document).ready(function () {
        $("#sidebar-menu li ").removeClass("active open");
        $("#sidebar-menu li ul li").removeClass("active");
        
        $("#menu_postpaid").addClass("active open");
        $("#postpaid_collapse").addClass("collapse in");
        $("#batch_payment").addClass("active");
        
    });
</script>
@endsection