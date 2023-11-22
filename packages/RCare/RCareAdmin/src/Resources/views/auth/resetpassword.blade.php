<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Renova HealthCare</title>
	<link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('assets/styles/css/themes/lite-purple.min.css')}}">
</head>

<body>
	<div class="auth-layout-wrap" style="background-image: url({{asset('assets/images/photo-wide-4.jpg')}})">
		<div class="auth-content">
			<div class="card o-hidden">
				<div class="row">
					<div class="col-md-12">
						<div class="p-4">
							<div class="auth-logo text-center mb-4">
								<img src="{{asset('assets/images/logo.png')}}" alt="">
							</div>
							<div class="card-header text-center text-18">{{ __('Reset Password') }}</div>

							<div class="card-body">
								@if (session('errorMsg'))
								<div class="alert alert-danger" role="alert">
									{{ session('errorMsg') }}
								</div>
								@endif

								<form method="POST" action="{{ route('resetPassword') }}">
									@csrf
									<div class="form-group row">
										<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>
										<div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

									<div class="form-group row">
										<label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

										<div class="col-md-6">
											<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

											@error('password')
											<span class="invalid-feedback" role="alert">
												<strong>{{ $message }}</strong>
											</span>
											@enderror
										</div>
									</div>

									<div class="form-group row">
										<label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

										<div class="col-md-6">
											<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
										</div>
									</div>

									<div class="form-group row mb-0">
										<div class="col-md-6 offset-md-4">
											<button type="submit" class="btn btn-primary">
												{{ __('Reset Password') }}
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="{{asset('assets/js/common-bundle-script.js')}}"></script>

	<script src="{{asset('assets/js/script.js')}}"></script>
</body>

</html>

