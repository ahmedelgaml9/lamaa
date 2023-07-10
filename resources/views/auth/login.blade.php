<html lang="en">
	<head>
		<title>Lam3a</title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta charset="utf-8" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Lam3a" />
		<meta property="og:url" content="{{url('/')}}" />
		<meta property="og:site_name" content="Lam3a" />
		<link rel="canonical" href="{{url('/')}}" />
		<link rel="shortcut icon" href="" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <link href="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dashboard/dist/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&amp;l='+l:'';j.async=true;j.src= 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer','GTM-5FS8GGP');</script>
	</head>

	<body id="kt_body" class="bg-body">

		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(/metronic8/demo1/assets/media/illustrations/sketchy-1/14.png">
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
				
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<form class="form w-100" method="post" action="{{ route('login') }}">
                              @csrf
							<div class="text-center mb-10">
								<h1 class="text-dark mb-3">Sign In </h1>
							</div>
							<div class="fv-row mb-10">
								<label class="form-label fs-6 fw-bolder text-dark">Email</label>
								<input class="form-control form-control-lg form-control-solid" type="text" name="email" autocomplete="off" />
                                <span class="text-danger">{{ $errors->first('email') }}</span>

							</div>
						
							<div class="fv-row mb-10">
								<div class="d-flex flex-stack mb-2">
									<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>							
								</div>
							
								<input class="form-control form-control-lg form-control-solid" type="password" name="password" autocomplete="off" />
                                <span class="text-danger">{{ $errors->first('password') }}</span>

						 	 </div>
					 	
					     	<div class="text-center">
						    	 <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
								     Submit
							    </button>
					    	</div>
					    </form>
				    </div>
			     </div>
			
				 <div class="d-flex flex-center flex-column-auto p-10">
				</div>
			</div>
		</div>


        <script>var hostUrl = "/metronic8/demo1/assets/";</script> 
         <script src="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.js')}}"></script>
         <script src="{{asset('dashboard/dist/assets/js/scripts.bundle.js')}}"></script>
         <script src="{{asset('dashboard/dist/assets/js/custom/authentication/sign-in/general.js')}}"></script>
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!--End::Google Tag Manager (noscript) -->
	</body>
	<!--end::Body-->
</html>
