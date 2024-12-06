<!doctype html>
<!--start meta-->
@include('includes.meta')
<!--end top meta-->

<body>
	<!--authentication-->

	<div class="section-authentication-cover">
		<div class="">
			<div class="row g-0">

				<div
					class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex border-end bg-transparent">

					<div class="card rounded-0 mb-0 border-0 shadow-none bg-transparent bg-none">
						<div class="card-body">
							<img src="assets/images/auth/forgot-password1.png" class="img-fluid auth-img-cover-login"
								width="550" alt="">
						</div>
					</div>
			
				</div>

				<div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
					<div class="card rounded-0 m-3 mb-0 border-0 shadow-none bg-none">
						<div class="card-body p-5">

							<img src="assets/images/logo2.png" class="mb-4" width="75" alt="">
							<h4 class="fw-bold">Hesabınıza erişemiyor musunuz?</h4>
							<p class="mb-0 mt-2">Hesabınıza ulaşamıyorsanız, aşağıdaki bilgileri doldurarak
								yöneticilerin yeni hesap
								bilgilerini Gmail adresinize göndermesini sağlayabilirsiniz.</p>
							<div class="form-body mt-4">
								<form id="resetPasswordForm" method="POST" action="{{ url('/api/auth/forgot-password') }}">
									@csrf
									<div class="col-12">
										<label class="form-label" for="NewPassword">E-Posta Adresiniz</label>
										<input id="email" type="email" class="form-control" name="email" required
											autocomplete="email" autofocus>
									</div>
									<div class="col-12 mt-4">
										<div class="d-grid gap-2">
											<button type="submit" class="btn btn-grd-branding text-white">Talep Gönder</button>
											<a href="{{ route('login') }}" class="btn btn-grd-danger text-white">Giriş
												Ekranına Dön</a>
										</div>
									</div>
								</form>
							</div>

						</div>
					</div>
				</div>

			</div>
			<!--end row-->
		</div>
	</div>

	<!--authentication-->



	<script src="{{url('assets/js/bootstrap.bundle.min.js')}}"></script>

<!--plugins-->
<script src="{{url('assets/js/jquery.min.js')}}"></script>
<!--plugins-->
<script src="{{url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="{{url('assets/plugins/metismenu/metisMenu.min.js')}}"></script>
<script src="{{url('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{url('assets/js/main.js')}}"></script>
<script src="{{url('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>

<script src="{{url('assets/plugins/form-repeater/repeater.js')}}"></script>


<script src="{{url('assets/plugins/notifications/js/lobibox.min.js')}}"></script>

<script src="{{url('assets/js/notifications.js')}}"></script>

	<script>

		async function ResetPassword() {

			let email = document.getElementById('email').value;
			axios.post('/api/auth/forgot-password', {
				email: email
			})
				.then(response => {
					console.log(response);
					successToast(response.data.message);
					setTimeout(() => {
						window.location.href = '/login';
					}, 3000);
				})
				.catch(error => {
					errorToast(error.response.data.errors);
				});
		}

	</script>

</body>

</html>