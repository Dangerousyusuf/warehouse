<!doctype html>
@include('includes.meta')
<title>teDepo | Fabrika Ayarları </title>
<body>
	@include('includes.header')
	@include('includes.sidebar')
	<main class="main-wrapper">
		<div class="main-content">
			<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
				<div class="breadcrumb-title pe-3">Fabrika</div>
				<div class="ps-3">
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb mb-0 p-0">
							<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
							</li>
							<li class="breadcrumb-item active" aria-current="page"> Fabrika Ayarları</li>
						</ol>
					</nav>
				</div>
			</div>
			<div class="col">
				<div class="card">
					<div class="card-body">
						<ul class="nav nav-tabs nav-danger" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link active" data-bs-toggle="tab" href="#urunAyarlari" role="tab"
									aria-selected="true">
									<div class="d-flex align-items-center">
										<div class="tab-icon"><i class="bi bi-box me-1 fs-6"></i>
										</div>
										<div class="tab-title">Ürün Ayarları</div>
									</div>
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" data-bs-toggle="tab" href="#stokAyarlari" role="tab"
									aria-selected="false">
									<div class="d-flex align-items-center">
										<div class="tab-icon"><i class="bi bi-clipboard-data me-1 fs-6"></i>
										</div>
										<div class="tab-title">Stok Ayarları</div>
									</div>
								</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link" data-bs-toggle="tab" href="#rolAyarlari" role="tab"
									aria-selected="false">
									<div class="d-flex align-items-center">
										<div class="tab-icon"><i class='bi bi-people me-1 fs-6'></i>
										</div>
										<div class="tab-title">Rol Ayarları</div>
									</div>
								</a>
							</li>
						</ul>
						<div class="tab-content py-3">
							<div class="tab-pane fade show active" id="urunAyarlari" role="tabpanel">
							<form id="featureForm" method="POST" action="{{ route('factory.settings.update') }}">
							@csrf
								<div class="d-flex justify-content-between align-items-center mb-4">
									<h5 class="mb-0">Ürün Ayarları</h5>
									<button type="submit" class="btn btn-success d-flex align-items-center ms-auto mt-3">
										<i class="material-icons-outlined">save</i>
										<span class="ms-1">Ürün Ayarlarını Kaydet</span>
									</button>
								</div>
							
									<input type="hidden" name="type" value="feature_unit"> <!-- Ürün ayarları için türü belirt -->
									<div class="mb-3 mt-4">
										<label class="form-label">Ürün Özellikleri Birimi</label>
										<input type="text" class="form-control" id="featureUnitInput" data-role="tagsinput" name="feature_unit"  
											value="{{ isset($factorySettings) ? $factorySettings->where('variable', 'product_feature_unit')->first()->value : '' }}"> 
										<small class="form-text text-muted">Özellik birimlerini virgülle ayırarak girin.</small>
									</div>
									<div class="mb-3 mt-4">
										<label class="form-label">Markalar</label>
										<input type="text" class="form-control" id="brandInput" data-role="tagsinput" name="brands"  
											value="{{ isset($factorySettings) ? $factorySettings->where('variable', 'brands')->first()->value ?? '' : '' }}"> 
										<small class="form-text text-muted">Markaları virgülle ayırarak girin.</small>
									</div>
									<!-- Ürün türleri alanı ekleniyor -->
									<div class="mb-3 mt-4">
										<label class="form-label">Ürün Türleri</label>
										<input type="text" class="form-control" id="productTypeInput" data-role="tagsinput" name="product_types"  
											value="{{ isset($factorySettings) ? $factorySettings->where('variable', 'product_types')->first()->value ?? '' : '' }}"> 
										<small class="form-text text-muted">Ürün türlerini virgülle ayırarak girin.</small>
									</div>
								</form>
							</div>
							<div class="tab-pane fade" id="stokAyarlari" role="tabpanel">
							<form id="stockSettingsForm" method="POST" action="{{ route('factory.settings.update.stock') }}"> 
								<div class="d-flex justify-content-between align-items-center mb-4">
									<h5 class="mb-0">Stok Ayarları</h5>
									<button type="submit" class="btn btn-success d-flex align-items-center mt-3 ms-auto">
										<i class="material-icons-outlined">save</i>
										<span class="ms-1">Stok Ayarlarını Kaydet</span>
									</button>
							
								</div>
							
									@csrf
									<input type="hidden" name="type" value="stock_settings"> <!-- Stok ayarları için türü belirt -->
									<div class="mb-4">
										<label class="form-label">Stok Düşme Kategorileri</label>
										<input type="text" class="form-control" id="categoryInput" data-role="tagsinput" name="stock_drop_category" 
										value="{{ isset($factorySettings) ? $factorySettings->where('variable', 'stock_drop_category')->first()->value : '' }}"> 
										<small class="form-text text-muted">Kategorileri virgülle ayırarak girin.</small>
									</div>
									<hr>
									<div class="form-check-success form-check form-switch mt-4">
										@php
											$stockLimitWarning = isset($factorySettings) ? $factorySettings->where('variable', 'stock_limit_warning')->first() : null;
										@endphp
										<input class="form-check-input" type="checkbox" id="flexSwitchCheckCheckedDanger" 
											name="stock_limit_warning" 
											value="1"
											{{ $stockLimitWarning && $stockLimitWarning->value == '1' ? 'checked' : '' }}>
										<label class="form-check-label" for="flexSwitchCheckCheckedDanger">Stok Limit Altına Düştüğünde Otomatik Sipariş Ver</label>
									</div>
									</form>
							</div>
							<div class="tab-pane fade" id="rolAyarlari" role="tabpanel">
								<div class="d-flex justify-content-between align-items-center mb-4">
									<h5 class="mb-0">Rol Ayarları</h5>
									<div class="d-flex justify-content-end ">
										<button type="button" class="btn btn-success d-flex align-items-center"
											data-bs-toggle="modal" data-bs-target="#addRoleModal">
											<i class="material-icons-outlined">add</i>
											<span class="ms-1">Rol Ekle</span>
										</button>
									</div>
								</div>
								<table class="table">
									<thead>
										<tr>
											<th>Rol Adı</th>
											<th>İzinler</th>
											<th>İşlemler</th>
										</tr>
									</thead>
									<tbody>
									
										@foreach($roles as $role)
											<tr>
												<td>{{ $role->name }}</td>
												<td>
													@foreach($role->permissions as $permission)
														<span class="badge bg-primary">{{ $permission->name }}</span>
													@endforeach
												</td>
												<td>
													<div class="d-flex justify-content-between">
														<a href="{{route('role.role_edit', $role->id)}}"
															class="btn btn-primary btn-sm">Düzenle</a>
														<button class="btn btn-danger btn-sm ms-1"
															onclick="deleteRole({{ $role->id }})">Sil</button>
													</div>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel"
				aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title ms-auto" id="addRoleModalLabel">Rol Ekle</h5>
							<button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"
								aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="mb-3">
								<label for="roleName" class="form-label">Rol Adı</label>
								<input type="text" class="form-control" id="roleName">
							</div>
							<div class="mb-3">
								<label for="copyFromRole" class="form-label">Yetkileri Kopyala</label>
								<select class="form-control" id="copyFromRole" onchange="togglePermissions()">
									<option value="" selected>Seçiniz</option>
									@foreach($roles as $role)
										<option value="{{ $role->id }}">{{ $role->name }}</option>
									@endforeach
								</select>
							</div>
							<div class="mb-3" id="permissions">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="selectAll"
										onclick="toggleAllPermissions(this)">
									<label for="selectAll" class="form-check-label">Tüm Yetkilere İzin Ver</label>
								</div>
								<div class="row">
								@foreach($role->permissions as $permission)
										<div class="col-3">
											<div class="form-check">
												<input class="form-check-input" type="checkbox"
													value="{{$permission->name}}" id="permission-{{$permission->id}}">
												<label for="permission-{{$permission->id}}"
													class="form-check-label">{{ $permission->name }}</label>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
							<button type="button" class="btn btn-primary" onclick="addRole()">Ekle</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</main>
	<div class="overlay btn-toggle"></div>
	@include('includes.footer')
	@include('includes.switcher')
	<div>
		<script>
		$("#repeater").createRepeater({
			showFirstItemToDefault: true,
		});
		document.addEventListener('DOMContentLoaded', function () {
			togglePermissions();
		});
		function togglePermissions() {
			const copyFromRole = document.getElementById('copyFromRole');
			const permissions = document.getElementById('permissions');
			if (copyFromRole) {
				if (copyFromRole.value) {
					permissions.style.display = 'none';
				} else {
					permissions.style.display = 'block';
				}
			}
		}
		function toggleAllPermissions(selectAllCheckbox) {
			const checkboxes = document.querySelectorAll('input[type="checkbox"][id^="permission-"]');
			checkboxes.forEach((checkbox) => {
				checkbox.checked = selectAllCheckbox.checked;
			});
		}
		function addRole() {
			const roleName = document.getElementById('roleName').value;
			const permissions = document.querySelectorAll('input[type="checkbox"][id^="permission-"]');
			const permissionsArray = Array.from(permissions).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value);
			const copyFromRole = document.getElementById('copyFromRole');
			const data = {
				name: roleName,
				permissions: permissionsArray
			};
			if (copyFromRole.value) {
				data.copy_role_id = copyFromRole.value;
			}

			axios.post('/api/role/', data, {
				headers: {
					'Content-Type': 'application/json',
				}
			}).then(response => {
				successToast('Rol eklendi');
				window.location.href = 'factory-settings';
			}).catch(error => {
				errorToast(error.response.data.message);
			});
		}
		function deleteRole(id) {
			// Değişiklik: Modal ile onay soruluyor
			const modalHtml = `
				<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="confirmDeleteModalLabel">Silme Onayı</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								Silmek istediğinize emin misiniz?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hayır</button>
								<button type="button" class="btn btn-danger" onclick="confirmDelete(${id})">Evet, Sil</button>
							</div>
						</div>
					</div>
				</div>
			`;
			document.body.insertAdjacentHTML('beforeend', modalHtml);
			const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
			modal.show();
		}
		function confirmDelete(id) {
			axios.delete('/api/role/' + id).then(response => {
				successToast('Rol silindi');
				window.location.reload();
			}).catch(error => {
				errorToast(error.response.data.message);
			});
			const modal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
			modal.hide();
		}
		document.addEventListener('DOMContentLoaded', function() {
			axios.get('/api/product-feature')
				.then(response => {
					const product_feature_unit = response.data;
					document.getElementById('featureUnitInput').value = feature.feature_unit; // Mevcut özelliği inputa yerleştir
				})
				.catch(error => {
					console.error(error);
				});
		});
		</script>
	</div>
</body>
</html>
