<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Labels</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/css.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/custom.css') }}" rel="stylesheet">
	<link href="{{ asset('/css/choosen.css') }}" rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="http://172.27.161.173/labels/"><b>Labels application</b></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/printer') }}">Choose printer</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/cbextralabels') }}">Scann BB and print CB labels</a></li>
				</ul>
				<!-- <ul class="nav navbar-nav">
					<li><a href="{{ url('/cblabel') }}">Scann CB and print ONE CB label</a></li>
				</ul> -->
				<!-- <ul class="nav navbar-nav">
					<li><a href="{{ url('/selectinbound') }}">Print labels from Inbound</a></li>
				</ul> -->
				<!-- <ul class="nav navbar-nav">
					<li><a href="{{ url('/pallets') }}">Print pallet labels</a></li>
				</ul> -->
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/bundle') }}">Print bundle labels</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/padprint') }}">Print PadPrint labels</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/padprint_conf') }}">PadPrint Conf</a></li>
				</ul>
				<!-- <ul class="nav navbar-nav">
					<li><a href="{{ url('/sap_acc') }}">SAP Acc</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/sap_hu') }}">SAP SU</a></li>
				</ul> -->

				<!-- <ul class="nav navbar-nav">
					<li><a href="{{ url('/selectinbound') }}">Print labels from Inbound (TEST)</a></li>
				</ul> -->
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/print_oprators') }}">Print Operator label</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/print_os') }}">Print OS label</a></li>
				</ul>



			<!--
				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			-->
			</div>
		</div>
	</nav>

	@yield('content')

	<!-- Scripts -->
	
	<script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('/js/choosen.js') }}" type="text/javascript" ></script>

 <script type="text/javascript">
$(function() {
    	
	// $('#po').autocomplete({
	// 	minLength: 3,
	// 	autoFocus: true,
	// 	source: '{{ URL('getpodata')}}'
	// });
	// $('#module').autocomplete({
	// 	minLength: 1,
	// 	autoFocus: true,
	// 	source: '{{ URL('getmoduledata')}}'
	// });
	// $('#style').autocomplete({
	// 	minLength: 3,
	// 	autoFocus: true,
	// 	source: '{{ URL('getstyledata')}}'
	// });

	$(".chosen").chosen();

	$('#filter').keyup(function () {

        var rex = new RegExp($(this).val(), 'i');
        $('.searchable tr').hide();
        $('.searchable tr').filter(function () {
            return rex.test($(this).text());
        }).show();
	});


	// $('#myTabs a').click(function (e) {
 //  		e.preventDefault()
 //  		$(this).tab('show')
	// });
	// $('#myTabs a:first').tab('show') // Select first tab

	// $(function() {
 //    	$( "#datepicker" ).datepicker();
 //  	});

  	
	$('#sort').bootstrapTable({
    	
	});

	//$('.table tr').each(function(){
  		
  		//$("td:contains('pending')").addClass('pending');
  		//$("td:contains('confirmed')").addClass('confirmed');
  		//$("td:contains('back')").addClass('back');
  		//$("td:contains('error')").addClass('error');
  		//$("td:contains('TEZENIS')").addClass('tezenis');

  		// $("td:contains('TEZENIS')").function() {
  		// 	$(this).index().addClass('tezenis');
  		// }
	//});

	// $('.days').each(function(){
	// 	var qty = $(this).html();
	// 	//console.log(qty);

	// 	if (qty < 7 ) {
	// 		$(this).addClass('zeleno');
	// 	} else if ((qty >= 7) && (qty <= 15)) {
	// 		$(this).addClass('zuto');
	// 	} else if (qty > 15 ) {	
	// 		$(this).addClass('crveno');
	// 	}
	// });


	// $('.status').each(function(){
	// 	var status = $(this).html();
	// 	//console.log(qty);

	// 	if (status == 'pending' ) {
	// 		$(this).addClass('pending');
	// 	} else if (status == 'confirmed') {
	// 		$(this).addClass('confirmed');
	// 	} else {	
	// 		$(this).addClass('back');
	// 	}
	// });

	// $('td').click(function() {
	//    	var myCol = $(this).index();
 	//    	var $tr = $(this).closest('tr');
 	//    	var myRow = $tr.index();

 	//    	console.log("col: "+myCol+" tr: "+$tr+" row:"+ myRow);
	// });

});
</script>

</body>
</html>
