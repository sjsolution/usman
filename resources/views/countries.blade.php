<!DOCTYPE html>
<html>
<head>
	<title>Countries Lists</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css" />
</head>
<body>

<div class="container">
	<h2 class="text-center">Laravel 5.5 Countries Lists</h2>

	@if($countries->count())
		@foreach($countries as $country)
			<span style="padding: 5px;"> {!! $country->flag['flag-icon'] !!} {!! $country->name->common !!} </span> 
		@endforeach
	@endif
</div>

</body>
</html>