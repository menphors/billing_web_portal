@extends('layouts.master')
@section('content')
    <div class="card card-gray">
        <div class="card-header">
            <div class="header-block">
            <p class="title"> Testing </p>
            </div>
        </div>
        <hr>
        <div class="card-block">
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-8">
                    <form action="{{url('batch_payment/testing/save')}}" method="POST">
                    {{csrf_field()}}
                        <div class="form-group row">
                            <label for="cust_id" class="col-sm-4">Master Cust ID</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="cust_id" name="cust_id" auto-focus required placeholder="Input your master cust id">
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
    $(document).ready(function () {
        $("#sidebar-menu li ").removeClass("active open");
        $("#sidebar-menu li ul li").removeClass("active");
        
        $("#menu_postpaid").addClass("active open");
        $("#postpaid_collapse").addClass("collapse in");
        $("#testing").addClass("active");
        
    });
</script>
@endsection