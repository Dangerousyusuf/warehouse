<!doctype html>
@include('includes.meta')
<title>teDepo | Kullanıcı Düzenle </title>
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
							<li class="breadcrumb-item active" aria-current="page">Kullanıcı Düzenle</li>
						</ol>
					</nav>
				</div>
			</div>
			<!--breadcrumb-->
			<form id="usersForm" action="{{ route('user-update', $user->id) }}" method="POST" novalidate>
				@csrf
				@method('PUT')
				<div class="card-body p-4">
					<h5 class="mb-4">Kullanıcı Düzenle</h5>
					<div class="row mb-3">
						<label for="name" class="col-sm-3 col-form-label">Ad ve Soyad</label>
						<div class="col-sm-9">
							<div class="input-group">
								<span class="input-group-text"><i class="material-icons-outlined fs-5">person</i></span>
								<input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="İsim" value="{{ old('name', $user->name) }}" required>
								@error('name')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<label for="phone" class="col-sm-3 col-form-label">Telefon Numarası</label>
						<div class="col-sm-9">
							<div class="input-group">
								<span class="input-group-text"><i class="material-icons-outlined fs-5">smartphone</i></span>
								<input type="tel" class="form-control phone-mask @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" placeholder="5xxxxxxxxx" value="{{ old('phone_number', $user->phone_number) }}" required>
								@error('phone_number')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<label for="email" class="col-sm-3 col-form-label">E-Posta Adresi</label>
						<div class="col-sm-9">
							<div class="input-group">
								<span class="input-group-text"><i class="material-icons-outlined fs-5">drafts</i></span>
								<input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
								@error('email')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
					</div>
					<div class="row mb-3">
						<label for="role" class="col-sm-3 col-form-label">Görev Seçiniz</label>
						<div class="col-sm-9">
							<div class="input-group">
								<span class="input-group-text"><i class="material-icons-outlined fs-5">format_list_bulleted</i></span>
								<select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
									<option value="">Görev Seçiniz</option>
									<option value="Yönetici" {{ old('role', $user->role) == 'Yönetici' ? 'selected' : '' }}>Yönetici</option>
									<option value="Müdür" {{ old('role', $user->role) == 'Müdür' ? 'selected' : '' }}>Müdür</option>
									<option value="Depo Sorumlusu" {{ old('role', $user->role) == 'Depo Sorumlusu' ? 'selected' : '' }}>Depo Sorumlusu</option>
								</select>
								@error('role')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
								@enderror
							</div>
						</div>
					</div>
					@php
						$warehouses = App\Models\Warehouse::whereNull('deleted_at')->get();
						$userWarehouseIds = $user->warehouses->pluck('id')->whereNull('deleted_at')->toArray();
					@endphp
					<div class="row mb-3">
						<label for="work_areas" class="col-sm-3 col-form-label">Çalışma Alanı</label>
						<div class="col-sm-9">
							<div class="input-group">
								<span class="input-group-text"><i class="material-icons-outlined fs-5">home</i></span>
								<select class="form-select @error('work_areas') is-invalid @enderror" id="multiple-select-optgroup-field" name="work_areas[]" multiple>
									@foreach($warehouses as $warehouse)
										<option value="{{ $warehouse->id }}" {{ in_array($warehouse->id, $user->warehouses->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $warehouse->name }}</option>
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
							<button type="submit" class="btn btn-success">Kullanıcıyı Güncelle</button>
							<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">Kullanıcıyı Sil</button>
						</div>
					</div>
				</div>
			</form>

			<!-- Silme Modalı -->
	<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteUserModalLabel">Kullanıcıyı Sil</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Kullanıcıyı silmek istediğinize emin misiniz?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
					<form id="deleteUserForm" action="{{ route('users.destroy', $user->id) }}" method="POST">
						@csrf
						@method('DELETE')
						<button type="submit" class="btn btn-danger">Kullanıcıyı Sil</button>
					</form>
				</div>
			</div>
		</div>
	</div>
		</div>
	</main>

	<div class="overlay btn-toggle"></div>

	
	@include('includes.footer')
	@include('includes.switcher')
	<script>
		$(document).ready(function () {
			$('.phone-mask').inputmask('0(999) 999-9999', { placeholder: '0(5xx) xxx-xxxx' });
			
		});
	</script>

	

</body>
</html>
