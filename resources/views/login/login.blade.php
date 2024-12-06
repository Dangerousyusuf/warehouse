<!doctype html>
<!--start meta-->
@include('includes.meta')
<!--end top meta-->
<title>teDepo | Giriş </title>

<body>

  <!--authentication-->
  <div class="section-authentication-cover">
    <div class="">
      <div class="row g-0">

        <div
          class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex border-end bg-transparent">

          <div class="card rounded-0 mb-0 border-0 shadow-none bg-transparent bg-none">
            <div class="card-body">
              <img src="assets/images/auth/login1.png" class="img-fluid auth-img-cover-login" width="650" alt="">
            </div>
          </div>
        </div>
        <div
          class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center border-top border-4 border-primary border-gradient-1">
          <div class="card rounded-0 m-3 mb-0 border-0 shadow-none bg-none">
            <div class="card-body p-sm-5 ">
              <div class="text-center">
                <img src="assets/images/logo2.png" class="mb-4" width="75" alt="">
                <h4 class="fw-bold">Merhaba</h4>
                <p class="mb-0">Hesabınıza giriş yapmak için lütfen bilgilerinizi girin!</p>
              </div>
              <div class="separator section-padding mt-2">
                <div class="line"></div>
                <p class="mb-0 fw-bold">-
                </p>
                <div class="line"></div>
              </div>
              <div class="form-body mt-4">
                <form id="loginForm" class="row g-3" method="POST" action="{{ route('login') }}">
                  @csrf
                  <div class="col-12">
                    <label for="email" class="form-label">{{ __('E-Posta Adresiniz') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                      name="email" value="{{ old(key: 'email') }}" required autocomplete="email" autofocus>
                      @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
                  <div class="col-12">
                    <label for="password" class="form-label">{{ __('Parola') }}</label>
                    <div class="input-group" id="show_hide_password">
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password">
                        <a href="javascript:;" class="input-group-text bg-transparent" id="togglePassword">
                          <i class="bi bi-eye-slash-fill"></i>
                        </a>
                      @error('password')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                      <label class="form-check-label" for="remember"> {{ __('Beni Hatırla') }}</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                      <button type="submit" class="btn btn-grd-success text-white">Giriş Yap</button>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="text-start">
                      <p class="mb-0">Hesabınıza erişemiyor musunuz?<a href="{{ route('reset') }}">
                          Yardım.</a></p>
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

  <!--plugins-->
  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

  <!--plugins-->
  <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <!--plugins-->
  <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
  <script>
    /* Create Repeater */
    $("#repeater").createRepeater({
      showFirstItemToDefault: true,
    });
  </script>
  <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <script src="{{ asset('assets/plugins/notifications/js/lobibox.min.js') }}"></script>
  <script src="{{ asset('assets/js/notifications.js') }}"></script>

  <!-- JavaScript kodunu ekleyin -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            this.querySelector('i').classList.toggle('bi-eye-fill');
            this.querySelector('i').classList.toggle('bi-eye-slash-fill');
        });
    });
  </script>

</body>

</html>