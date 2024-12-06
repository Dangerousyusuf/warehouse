<!doctype html>
@include('includes.meta')
<title>teDepo | Hesap Ayarları </title>
<body>

  <!--start header-->
  @include('includes.header')
  <!--end top header-->

  <!--start sidebar-->
  @include('includes.sidebar')
  <!--end sidebar-->

  <main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Ayarlar</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="lni lni-user"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Profil</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->

      <div class="card rounded-4">
        <div class="card-body p-4">
          <div class="position-relative mb-5">
            <img src="assets/images/gallery/profile-cover.png" class="img-fluid rounded-4 shadow" alt="">
            <div class="profile-avatar position-absolute top-100 start-50 translate-middle">
              <img src="assets/images/avatars/11.png" class="img-fluid rounded-circle p-1 bg-grd-danger shadow"
                width="170" height="170" alt="">
            </div>
          </div>
          <div class="profile-info pt-5 d-flex align-items-center justify-content-between">
            <div class="">
              <h3>{{ mb_strtoupper(Auth::user()->name, 'UTF-8') }}</h3>
              <p class="mb-0">{{ Auth::user()->email }}<br>
                {{ Auth::user()->phone_number ?? 'Telefon bilgisi mevcut değil' }}</p>
            </div>
          </div>
          <div class="kewords d-flex align-items-center gap-3 mt-4 overflow-x-auto">
            @if(Auth::user()->role == 'Yönetici')
                <button type="button" class="btn btn-sm btn-success rounded-5 px-4">Yönetici</button>
            @elseif(Auth::user()->role == 'Depo Sorumlusu')
                <button type="button" class="btn btn-sm btn-danger rounded-5 px-4">Depo Sorumlusu</button>
            @else
                <button type="button" class="btn btn-sm btn-secondary rounded-5 px-4">{{ Auth::user()->role ?? 'Tanımsız Rol' }}</button>
            @endif
          </div>
        </div>
      </div>

      <div class="row">
      <div class="col-12 col-xl-8">
          <div class="card rounded-4 border-top border-4 border-primary border-gradient-1">
            <div class="card-body p-4">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0 fw-bold">Bilgi Güncelle</h5>
                </div>
              </div>
              <form id="passwordUpdateForm" onsubmit="updatePassword(event); return false;" novalidate>
                @csrf
                @method('PUT')
                
                <div class="col-md-12 mb-3">
                  <label for="name" class="form-label">Ad Soyad</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="material-icons-outlined fs-5">person</i></span>
                    <input type="text" class="form-control" id="name" name="name" value="{{ mb_strtoupper(Auth::user()->name, 'UTF-8') }}" required>
                  </div>
                </div>

                <div class="col-md-12 mb-3">
                  <label for="phone_number" class="form-label">Telefon Numarası</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="material-icons-outlined fs-5">phone</i></span>
                    <input type="tel" class="form-control phone-mask" id="phone_number" name="phone_number" value="{{ Auth::user()->phone_number }}" required>
                  </div>
                </div>

                <div class="col-md-12 mb-3">
                  <label for="current_password" class="form-label">Güncel Şifrenizi Giriniz</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="material-icons-outlined fs-5">lock</i></span>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                  </div>
                </div>
                
                <div class="col-md-12 mb-3">
                  <label for="new_password" class="form-label">Yeni Şifrenizi Giriniz</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="material-icons-outlined fs-5">lock_open</i></span>
                    <input type="password" class="form-control" id="new_password" name="password">
                  </div>
                </div>
                
                <div class="col-md-12 mb-3">
                  <label for="new_password_confirmation" class="form-label">Tekrar Yeni Şifrenizi Giriniz</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="material-icons-outlined fs-5">lock_open</i></span>
                    <input type="password" class="form-control" id="new_password_confirmation" name="password_confirmation">
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="d-md-flex d-grid align-items-center justify-content-end gap-3">
                    <button type="submit" class="btn btn-grd-primary px-4 text-white">Bilgileri Güncelle</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-4">
          <div class="card rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0 fw-bold">Hakkında</h5>
                </div>
              </div>
              <div class="full-info">
                <div class="info-list d-flex flex-column gap-3">
                  <div class="info-list-item d-flex align-items-center gap-3"><span
                      class="material-icons-outlined">account_circle</span>
                    <p class="mb-0">Ad Soyad: {{ mb_strtoupper(Auth::user()->name, 'UTF-8') }}</p>
                  </div>
                  <div class="info-list-item d-flex align-items-center gap-3"><span
                      class="material-icons-outlined">done</span>
                    <p class="mb-0">Durum: {{ Auth::user()->status ?? 'Aktif' }}</p>
                  </div>
                  <div class="info-list-item d-flex align-items-center gap-3"><span
                      class="material-icons-outlined">code</span>
                    <p class="mb-0">Görev: {{ Auth::user()->role ?? 'Depo Sorumlusu' }}</p>
                  </div>
                  <div class="info-list-item d-flex align-items-center gap-3"><span
                      class="material-icons-outlined">flag</span>
                    <p class="mb-0">Şehir: {{ Auth::user()->city ?? 'Belirtilmemiş' }}</p>
                  </div>
                  <div class="info-list-item d-flex align-items-center gap-3"><span
                      class="material-icons-outlined">language</span>
                    <p class="mb-0">Dil: {{ Auth::user()->language ?? 'Türkçe' }}</p>
                  </div>
                  <div class="info-list-item d-flex align-items-center gap-3"><span
                      class="material-icons-outlined">send</span>
                    <p class="mb-0">E-Posta: {{ Auth::user()->email }}</p>
                  </div>
                  <div class="info-list-item d-flex align-items-center gap-3"><span
                      class="material-icons-outlined">call</span>
                    <p class="mb-0">Telefon: {{ Auth::user()->phone_number ?? 'Belirtilmemiş' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </main>

  <!--start overlay-->
  <div class="overlay btn-toggle"></div>
  <!--end overlay-->

  <!--start footer-->
  <footer class="page-footer">
    <p class="mb-0">Copyright © 2024. All right reserved.</p>
  </footer>
  <!--top footer-->

  <!--start switcher-->
  @include('includes.switcher')
  <!--end switcher-->

  <!--bootstrap js-->
  <script src="{{url('assets/js/bootstrap.bundle.min.js')}}"></script>

  <!--plugins-->
  <script src="{{url('assets/js/jquery.min.js')}}"></script>
  <!--plugins-->
  <script src="{{url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
  <script src="{{url('assets/plugins/metismenu/metisMenu.min.js')}}"></script>
  <script src="{{url('assets/plugins/form-repeater/repeater.js')}}"></script>
  <script>
    /* Create Repeater */
    $("#repeater").createRepeater({
      showFirstItemToDefault: true,
    });
  </script>
  <script src="{{url('assets/plugins/notifications/js/lobibox.min.js')}}"></script>
	<script src="{{url('assets/js/notifications.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
  <script src="{{url('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
  <script src="{{url('assets/js/main.js')}}"></script>

 
  <script>
    		$(document).ready(function () {
			$('.phone-mask').inputmask('0(999) 999-9999', { placeholder: '0(5xx) xxx-xxxx' });
		});
  async function updatePassword(event) {
    event.preventDefault();
    
    let name = document.getElementById('name').value;
    let phoneNumber = document.getElementById('phone_number').value;
    let currentPassword = document.getElementById('current_password').value;
    let newPassword = document.getElementById('new_password').value;
    let newPasswordConfirmation = document.getElementById('new_password_confirmation').value;
    let userId = {{ Auth::id() }};
    phoneNumber = phoneNumber.replace(/[()x\s-]/g, '');

    // Şifre uyuşmazlığı kontrolü (eğer yeni şifre girilmişse)
    if (newPassword && newPassword !== newPasswordConfirmation) {
      errorToast("Yeni şifre ve şifre onayı uyuşmuyor.");
      return;
    }

    try {
      let res = await axios.put("/api/auth/settings", {
        user_id: userId,
        name: name,
        phone_number: phoneNumber,
        current_password: currentPassword,
        password: newPassword,
        password_confirmation: newPasswordConfirmation
      });

      if (res.data.success) {
        resetForm();
        successToast("Bilgileriniz başarıyla güncellendi");
        setTimeout(() => {
          window.location.reload();
        }, 2000);
      } else {
        errorToast(res.data && res.data.message ? res.data.message : "Beklenmeyen bir yanıt alındı");
      }
      
    } catch (error) {
      console.error("Hata:", error);
      if (error.response && error.response.data && error.response.data.errors) {
        Object.values(error.response.data.errors).forEach(errorMessages => {
          errorMessages.forEach(errorMessage => errorToast(errorMessage));
        });
      } else {
        errorToast(error.response && error.response.data.message ? error.response.data.message : "Bir hata oluştu");
      }
    }
  }

  function resetForm() {
    document.getElementById('passwordUpdateForm').reset();
  }
  </script>
  

</body>

</html>