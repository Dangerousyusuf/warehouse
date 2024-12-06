<!doctype html>
@include('includes.meta')
<title>teDepo | Kullanıcı Ekle </title>
<body>

	<!--start header-->
	@include('includes.header')
	<!--end top header-->

	<!--start sidebar-->
	@include('includes.sidebar')


	<main class="main-wrapper">
		<div class="main-content">

			<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
				<div class="breadcrumb-title pe-3">Kullanıcı</div>
				<div class="ps-3">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb mb-0 p-0">
							<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
							</li>
							<li class="breadcrumb-item active" aria-current="page">Kullanıcı Ekle</li>
						</ol>
					</nav>
				</div>
			</div>
			<!--breadcrumb-->
			<form id="usersForm" action="{{ route('users.store') }}" method="POST" novalidate>
    @csrf
    <!-- Ad ve Soyad -->
    <div class="row mb-3">
        <label for="name" class="col-sm-3 col-form-label">Ad ve Soyad</label>
        <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-text"><i class="material-icons-outlined fs-5">person</i></span>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="İsim" value="{{ old('name') }}" required>
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    
    <!-- Telefon Numarası -->
    <div class="row mb-3">
        <label for="phone" class="col-sm-3 col-form-label">Telefon Numarası</label>
        <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-text"><i class="material-icons-outlined fs-5">smartphone</i></span>
                <input type="tel" class="form-control phone-mask @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" placeholder="5xxxxxxxxx" value="{{ old('phone_number') }}" required>
                @error('phone_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Email -->
    <div class="row mb-3">
        <label for="email" class="col-sm-3 col-form-label">E-Posta Adresi</label>
        <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-text"><i class="material-icons-outlined fs-5">drafts</i></span>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Şifre -->
    <div class="row mb-3">
        <label for="password" class="col-sm-3 col-form-label">Şifre</label>
        <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-text"><i class="material-icons-outlined fs-5">vpn_key</i></span>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Şifre" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Şifreyi Onayla -->
    <div class="row mb-3">
        <label for="password_confirmation" class="col-sm-3 col-form-label">Tekrar Şifre</label>
        <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-text"><i class="material-icons-outlined fs-5">vpn_key</i></span>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Tekrar Şifre" required>
                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Görev -->
    <div class="row mb-3">
        <label for="role" class="col-sm-3 col-form-label">Görev Seçiniz</label>
        <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-text"><i class="material-icons-outlined fs-5">format_list_bulleted</i></span>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                    <option value="">Görev Seçiniz</option>
                    <option value="Yönetici" {{ old('role') == 'Yönetici' ? 'selected' : '' }}>Yönetici</option>
                    <option value="Müdür" {{ old('role') == 'Müdür' ? 'selected' : '' }}>Müdür</option>
                    <option value="Depo Sorumlusu" {{ old('role') == 'Depo Sorumlusu' ? 'selected' : '' }}>Depo Sorumlusu</option>
                </select>
                @error('role')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    {{-- Depo bilgilerini çekmek için gerekli kodlar --}}
   
    <!-- Çalışma Alanı (Depolar) -->
    <div class="row mb-3">
        <label for="work_areas" class="col-sm-3 col-form-label">Çalışma Alanı</label>
        <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-text"><i class="material-icons-outlined fs-5">home</i></span>
                <select class="form-select @error('work_areas') is-invalid @enderror" id="multiple-select-optgroup-field"  name="work_areas[]" multiple required>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ in_array($warehouse->id, old('work_areas', [])) ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
                @error('work_areas')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-9 offset-sm-3">
            <button type="submit" class="btn btn-success">Kullanıcı Oluştur</button>
        </div>
    </div>
</form>

		</div>
	</main>
	<div class="overlay btn-toggle"></div>
    @include('includes.footer')
	@include('includes.switcher')
	<script>
		/* Create Repeater */
		$("#repeater").createRepeater({
			showFirstItemToDefault: true,
		});
	</script>
	<script>
		$(document).ready(function () {
			$('.phone-mask').inputmask('0(999) 999-9999', { placeholder: '0(5xx) xxx-xxxx' });
		});
		function resetForm() {
		document.getElementById('usersForm').reset();
			$('#small-bootstrap-class-multiple-field').val(null).trigger('change');
		}
	</script>
</body>
</html>
