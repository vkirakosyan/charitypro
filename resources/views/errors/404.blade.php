<!DOCTYPE html>
<html>
<head>
	<title>Page Not Found</title>
	<link href='https://fonts.googleapis.com/css?family=Assistant' rel='stylesheet'>
	<link rel="stylesheet" href="{{URL('css/error.css')}}">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
<body>
	<div class="container">
	<div class="row">
			<div class="col-6">
				<p>OOPS!</p>
				<h4>Մենք այս պահին չենք կարողանում գտնել այն էջը, որը Դուք փնտրում եք։</h4>
				<h5>Մի քանի օգտակար հղումներ</h5>
				<ul>
	                <li>
	                    <a href="{{URL::to('about_us')}}">Մեր Մասին</a>
	                </li>
	                <li>
	                  <a href="{{URL::to('job')}}">Աշխատանք</a>
	                </li>
	                <li>
	                  <a href="{{URL::to('stories')}}">Պատմություններ</a>
	                </li>
	                <li>
	                    <a href="{{URL::to('forum')}}">Ֆորում</a>
	                </li>
					<li>
	                    <a href="{{URL::to('donations')}}">Նվիրաբերություն</a>
	                </li>
	                <li>
	                    <a href="{{URL::to('upcoming_events')}}">Իրադարձություններ</a>
	                </li>
	            </ul>
			</div>
			<div class="col-6">
				<div class="logo">
					<img src="{{URL('images/logo.png')}}" width="500" height="400">
				</div>
			</div>
		</div>
	</div>
</body>
</html>
