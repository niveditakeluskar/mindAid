@if ($message = Session::get('success'))
<!-- style="margin-left: 1.1em;margin-right: 1.1em;
 --><div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;">
	<button  type="button" class="close" data-dismiss="alert" >&times; </button>	
        <strong >{{ $message }}</strong>
      @php(session(['success' => '']))
</div>
@endif


@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block" style="margin-left: 1.1em;margin-right: 1.1em;">
	<button type="button" class="close" data-dismiss="alert">&times; </button>	
        <strong>{{ $message }}</strong>
</div>
@endif


@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block" style="margin-left: 1.1em;margin-right: 1.1em;">
	<button type="button" class="close" data-dismiss="alert">&times; </button>	
	<strong>{{ $message }}</strong>
</div>
@endif


@if ($message = Session::get('info'))
<div class="alert alert-info alert-block" style="margin-left: 1.1em;margin-right: 1.1em;">
	<button type="button" class="close" data-dismiss="alert">&times; </button>	
	<strong>{{ $message }}</strong>
</div>
@endif

@if ($message = Session::get('errorMsg'))
<div class="alert alert-danger alert-block" style="margin-left: 1.1em;margin-right: 1.1em;">
	<button type="button" class="close" data-dismiss="alert">&times; </button>	
        <strong>{{ $message }}</strong>
</div>
@endif

