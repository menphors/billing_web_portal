
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Styles -->
        <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
        <link rel="icon" href="{{asset('admin/images/smart-icon-logo.png')}}">
        <link rel="stylesheet" href="{{asset('admin/css/vendor.css')}}">
        <link rel="stylesheet" href="{{asset('admin/css/app-green.css')}}">
        <link rel="stylesheet" href="{{asset('admin/fontawesome/css/all.min.css')}}" >
        <link rel="stylesheet" href="{{asset('admin/css/custom.css')}}" >

        <!-- Fonts
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css"> -->

        <!-- js chosen
        <link rel="stylesheet" href="{{asset('css/component-chosen.css')}}"> -->
        
        <!-- Ubuntu Font Customize -->
        <link rel="stylesheet" href="{{asset('admin/css/style.css')}}">

    </head>
    <body>
        <div class="main-wrapper">
            <div class="app" id="app">
                <header class="header">
                    <div class="header-block header-block-collapse d-lg-none d-xl-none">
                        <button class="collapse-btn" id="sidebar-collapse-btn">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    <div class="header-block header-block-search">
                        <strong>
                        CRM & BILLING DEPARTMENT
                        </strong> <br>
                        <strong>in-house web portal</strong>
                    </div>
                    <div class="header-block">
                        <!-- <img src="{{asset('admin/images/smart.png')}}" width="100"> -->
                    </div>
                    <div class="header-block header-block-nav">
                        <ul class="nav-profile">
                            <li class="profile dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user"></i> <span class="name">{{Auth::user()->name}}</span>
                                </a>
                                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power-off icon"></i>{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </header>
                <aside class="sidebar">
                    <div class="sidebar-container">
                        <div class="sidebar-header">
                            <div class="brand">
                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-3">
                                        <img src="{{asset('admin/images/smart.png')}}" alt="" width="100">
                                    </div>
                                    <div class="col-sm-3"></div>
                                </div>
                            </div>
                        </div>
                        <nav class="menu">
                            <ul class="sidebar-menu metismenu" id="sidebar-menu">
                                <li id="menu_dashboard">
                                    <a href="{{url('dashboard')}}">
                                    <i class="fa fa-home"></i> Dashboard 
                                    </a>
                                </li>
                                <li id='menu_batch_operation'>
                                    <a href="#"><i class="fab fa-ubuntu"></i>
                                    Batch Operation<i class="fa arrow"></i> 
                                    </a>
                                    <ul class="sidebar-nav" id="batch_operation_collapse" >
                                        @canview('activate')
                                        <li id="activate">
                                            <a href="{{ url('activate_sub') }}"> Activate Subcriber</a>
                                        </li>
                                        @endcanview
                                        
                                        @canview('home')
                                        <li id="home">
                                            <a href="{{ url('changeprimaryoffering') }}"> Change Primary Offering</a>
                                        </li>
                                        @endcanview
                                        @canview('supoffering')
                                        <li id="supoffering">
                                            <a href="{{ url('add_supplement_offer') }}"> Add Supplement Offering</a>
                                        </li>
                                        @endcanview

                                        <!-- need to add canview -->
                                        @canview('changesim')
                                        <li id="change_sim">
                                            <a href="{{ url('change_sim') }}">Change SIM</a>
                                        </li>
                                        @endcanview

                                        @canview('batchcollection')
                                        <li id="batch_collection">
                                            <a href="{{ url('batch_collection') }}">Query Batch Collection</a>
                                        </li>
                                        @endcanview

                                        
                                       
                                        <!-- <li id="menu_acc_info" id="changeacctinfo">
                                            <a href="{{ url('change_acc_info') }}"> Change Account Information</a>
                                        </li> -->
                                        @canview('cust')
                                        <li id="cust">
                                            <a href="{{ url('change_cust_info') }}"> Change Customer Information</a>
                                        </li>
                                        @endcanview
                                        @canview('changeacctinfo')
                                        <li id="changeacctinfo">
                                            <a href="{{ url('change_acct_info') }}"> Change Acccount Information</a>
                                        </li>
                                        @endcanview
                                        @canview('dealer')
                                        <li id="dealer">
                                            <a href="{{ url('change_dealer_info') }}"> Change Dealer NGBSS</a>
                                        </li>
                                        @endcanview
                                        @canview('evc')
                                        <li id="evc">
                                            <a href="{{ url('change_evc_info') }}"> Change EVC Information</a>
                                        </li>
                                        @endcanview
                                        @canview('deactivate')
                                        <li id="deactivate">
                                            <a href="{{ url('deactivate_sub') }}"> Deactivate Subcriber</a>
                                        </li>
                                        @endcanview
                                        @canview('changeposttopre')
                                        <li id="changeposttopre" >
                                            <a href="{{ url('change_post_to_pre') }}"> Postpaid To Prepaid</a>
                                        </li>
                                        @endcanview
                                        @canview('changepretopost')
                                        <li id="changepretopost">
                                            <a href="{{ url('change_pre_to_post') }}"> Prepaid To Postpaid</a>
                                        </li>
                                        @endcanview                                        
                                        @canview('changebillmedium')
                                        <li id="changebillmedium">
                                            <a href="{{ url('change_bill_medium') }}"> Change Bill Meduim</a>
                                        </li>
                                        @endcanview
                                    </ul>
                                </li>
                                <li id="menu_postpaid">
                                    <a href="#"> <i class="fas fa-phone"></i>
                                        PostPaid<i class="fa arrow"></i>
                                    </a>
                                    <ul class="sidebar-nav" id="postpaid_collapse">
                                    @canview('payment')
                                        <li id="batch_payment">
                                            <a href="{{url('batch_payment')}}">Batch Payment</a>
                                        </li>
                                    @endcanview
                                    @canview('suspend')
                                        <li id="request_suspend">
                                            <a href="{{url('request_suspend_view')}}">Request Temporary Suspend</a>
                                        </li>
                                    @endcanview
                                    @canview('cancelsuspend')
                                        <li id="cancel_suspend">
                                            <a href="{{url('cancel_suspend')}}">Cancel Suspend</a>
                                        </li>
                                    @endcanview
                                    <li id="testing">
                                            <a href="{{url('batch_payment/testing')}}">Testing</a>
                                    </li>
                                    </ul>
                                </li>
                                <li id='menu_report'>
                                    <a href="#"><i class="far fa-clipboard"></i>
                                    Report<i class="fa arrow"></i> 
                                    </a>
                                    <ul class="sidebar-nav" id="report_collapse">
                                        @canview('todo')
                                        <li id="todo">
                                            <a href="{{ url('to_do') }}"> TO DO</a>
                                        </li>
                                        @endcanview
                                        @canview('completed')
                                        <li id="completed">
                                            <a href="{{ url('completed') }}"> Completed</a>
                                        </li>
                                        @endcanview
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <footer class="sidebar-footer">
                        <ul class="sidebar-menu metismenu" id="customize-menu">
                            <li>
                                <a href="#" class="text-center">Smart Axiata</a>
                            </li>
                        </ul>
                    </footer>
                </aside>
                <div class="sidebar-overlay" id="sidebar-overlay"></div>
                <div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
                <div class="mobile-menu-handle"></div>
                <article class="content dashboard-page">
                    <section class="section">
                        @yield('content')
                    </section>
                   
                </article>
                <footer class="footer">
                    
                    <div class="footer-block author">
                        <ul>
                           
                        </ul>
                    </div>
                </footer>
            </div>
        </div>
               <!-- Reference block for JS -->
               <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
                <div class="color-primary"></div>
                <div class="color-secondary"></div>
            </div>
        </div>

<!-- <script> 

    @if(Session::has('success'))
        swal({
            title: "Success!",
            text:  "{{Session::get('success')}}",
            icon: "success",
        });
    @endif
</script> -->
        <!-- Reference block for JS -->
        <div class="ref" id="ref">
            <div class="color-primary"></div>
            <div class="chart">
            <div class="color-primary"></div>
            <div class="color-secondary"></div>
        </div>
        <!-- Scripts -->
        <!-- <script src="{{ asset('js/app.js') }}" defer></script> -->
        <!-- <script src="{{ asset('js/jquery.min.js') }}"></script> -->
        <!-- <script src="{{ asset('js/sweetalert.min.js') }}"></script> -->
        <script src="{{url('admin/js/vendor.js')}}"></script>
        <script src="{{url('admin/js/app.js')}}"></script>
        <!-- js chosen
        <script src="{{asset('js/chosen.jquery.min.js')}}"></script>
        <script src="{{asset('js/chosen.init.js')}}"></script>        
        <script src="{{asset('js/init.js')}}"></script> -->
        <script>
            var numberOfOptions = $('li#menu_report #report_collapse li').length
            if($('#menu_report #report_collapse li').length <= 0)
            {
                $("#menu_report").hide();
            }
            else{
                $("#menu_report").show();
            }
 
            var numberOfBatchOperation = $('li#menu_batch_operation #batch_operation_collapse li').length
            if($('#menu_batch_operation #batch_operation_collapse li').length <= 0)
            {
                $("#menu_batch_operation").hide();
            }
            else{
                $("#menu_batch_operation").show();
            }
        </script>
        @yield('js')
    </body>
</html>