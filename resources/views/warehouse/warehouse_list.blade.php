<!doctype html>
@include('includes.meta')
<title>teDepo | Depo Liste </title>

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
                <div class="breadcrumb-title pe-3">Depo</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Depo Listesi</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->


            <div class="row g-3">
                <div class="col-auto">
                    <div class="position-relative">
                        <input class="form-control px-5" type="search" placeholder="Depo Ara">
                        <span
                            class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>

                <div class="col-auto ms-auto">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Dışa
                            Aktar</button>
                        <a href="warehouse-add" class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Depo
                            Ekle</a>
                    </div>
                </div>
            </div><!--end row-->




            <div class="card mt-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Depo Adı</th>
                                    <th>Genel Doluluk Oranı</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlemler</th>
                                    <th>Sil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $warehouseData = $warehouseData ?? [];
                                @endphp
                                @forelse ($warehouseData as $warehouse)
                                    <tr>

                                        <td class="details-control" data-warehouse-id="{{$warehouse['id']}}">
                                            @if (!empty($warehouse['shelves']))
                                                <i class="bi bi-plus-circle"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="product-box">
                                                    <img src="assets/images/warehouse-icon.png" width="70" class="rounded-3"
                                                        alt="">
                                                </div>
                                                <div class="product-info">
                                                    <a href="javascript:;"
                                                        class="product-title">{{ $warehouse['name'] }}</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="progress" role="progressbar"
                                                aria-valuenow="{{ $warehouse['occupancy_rate'] }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                <div class="progress-bar {{ $warehouse['occupancy_rate'] > 70 ? 'bg-warning' : '' }}"
                                                    style="width: {{ $warehouse['occupancy_rate'] }}%">
                                                    {{ $warehouse['occupancy_rate'] }}%
                                                </div>
                                            </div>

                                        </td>
                                        <td>{{ $warehouse['created_at'] }}</td>
                                        <td>

                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                                    id="dropdownMenuButton{{ $warehouse['id'] }}" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    İşlemler
                                                </button>
                                                <ul class="dropdown-menu"
                                                    aria-labelledby="dropdownMenuButton{{ $warehouse['id'] }}">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('warehouse.warehouse_edit', $warehouse['id']) }}">Düzenle</a>
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            onclick="showWarehouseDetails({{ $warehouse['id'] }})">Detay</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" title="Sil"
                                                data-bs-target="#deleteModal{{ $warehouse['id'] }}"
                                                onclick="openDeleteModal({{ $warehouse['id'] }})">Sil</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Depo bulunamadı.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- "Ürün Ekle" Modalı -->
            @if($warehouseData != null)
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Ürün Ekle</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Arama Alanı -->
                            <div class="mb-3">
                                <input type="text" id="productSearch" class="form-control" placeholder="Ürün Ara...">
                            </div>
                            <div class="mb-3 d-flex justify-content-between">
                                <div>
                                    <input type="checkbox" id="selectAllProducts" onclick="selectAllProducts(this)">
                                    <label for="selectAllProducts">Tümünü Seç</label>
                                </div>
                                <div>
                                    <input type="number" id="globalQuantity" class="form-control" min="0"
                                        placeholder="Tümüne aynı stok adetini ata">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="submitAddProducts()">Ekle</button>
                            </div>
                            <form id="addProductsForm" action="{{ route('warehouse.addProducts', $warehouse['id']) }}"
                                method="POST">
                                @csrf
                                <input type="hidden" name="warehouse_id" value="{{ $warehouse['id'] }}">
                                <input type="hidden" name="shelf_id" value="">
                                <table class="table" id="productTable">
                                    <thead>
                                        <tr>
                                            <th>Ürün Seç</th>
                                            <th>Ürün Adı</th>
                                            <th>Ürün Kodu</th>
                                            <th>Adet</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Ana Ürün ve Varyantları -->
                                        @foreach($products as $product)
                                            <!-- Ana ürün satırı -->
                                            <tr class="product-row">
                                                <td>
                                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}">
                                                    <input type="hidden" name="is_variant[{{ $product->id }}]" value="0">
                                                </td>
                                                <td class="product-name">{{ $product->name }}</td>
                                                <td class="product-code">{{ $product->product_code }}</td>
                                                <td>
                                                    <input type="number" name="quantities[{{ $product->id }}]"
                                                        class="form-control" min="0" placeholder="Adet girin" disabled>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Silme Modalı -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Depoyu Sil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                        </div>
                        <div class="modal-body">
                            Bu depoyu silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
                        </div>
                        <div class="modal-footer">
                            <form id="deleteWarehouseForm" method="POST"
                                data-route="{{ route('warehouse.destroy', ':id') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="warehouse_id" id="warehouse_id" value="">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        </div>
        </div>





        </div>
    </main>


    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

    <!--start footer-->
    @include('includes.footer')
    <!--top footer-->

    <!--start switcher-->
    @include('includes.switcher')
    <!--end switcher-->







    <script>
        var warehouseData = <?php echo json_encode($warehouseData); ?>;

        function format(warehouseId) {
            var shelves = warehouseData[warehouseId]['shelves'];

            var html = '<table class="table table-striped mb-0">' +
                '<thead>' +
                '<tr>' +
                '<th>Raf Adı</th>' +
                '<th>Doluluk Oranı</th>' +
                '<th>Stok Miktarı</th>' +
                '<th>İşlemler</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';

            for (var i = 0; i < shelves.length; i++) {
                var stockQuantity = shelves[i].stock_quantity ? parseInt(shelves[i].stock_quantity) : 0;
                var stockLimit = shelves[i].stock_limit ? parseInt(shelves[i].stock_limit) : 1;

                var occupancyRate = stockLimit > 0 ? Math.round((stockQuantity / stockLimit) * 100) : 0;

                html += '<tr>' +
                    '<td>' + shelves[i].name + '</td>' +
                    '<td>' +
                    '<div class="progress" role="progressbar" aria-valuenow="' + occupancyRate + '" aria-valuemin="0" aria-valuemax="100">' +
                    '<div class="progress-bar" style="width: ' + occupancyRate + '%;">' +
                    occupancyRate + '%' +
                    '</div></div></td>' +
                    '<td>' + stockQuantity + '/' + stockLimit + '</td>' +
                    '<td>' +
                    '<form action="{{ route('stock.showByShelf', ['id' => '__SHELF_ID__']) }}" method="GET" style="display: inline;" id="dynamicForm" onsubmit="return updateAndSubmitForm(event);">' +
                    '<input type="hidden" name="shelfId" id="shelfId" value="' + shelves[i].id + '">' +
                    '<button type="submit" class="btn btn-sm btn-info">Detay</button>' +
                    '</form>' +
                    '<button class="btn btn-sm btn-success ms-2" data-bs-toggle="modal" data-bs-target="#addProductModal" onclick="prepareAddProductForm(' + warehouseId + ', ' + shelves[i].id + ')">Ürün Ekle</button>' +
                    '</td>' +
                    '</tr>';
            }

            html += '</tbody></table>';
            return html;
        }



        function updateAndSubmitForm(event) {
            event.preventDefault(); // Formun varsayılan davranışını durdur
            const form = document.getElementById('dynamicForm');

            const shelfId = event.target.elements['shelfId'].value; // Formun içindeki shelfId valuesini çek
          
            if (shelfId) {
                // Action URL'sini güncelle
                form.action = form.action.replace('__SHELF_ID__', shelfId);
                form.submit(); // Formu gönder
            } else {
                alert('Raf bilgisi eksik!');
            }
        }

        function showWarehouseDetails(warehouseId) {
            // Depo detaylarını göstermek için gerekli işlemleri burada yapın
            alert("Depo " + warehouseId + " detayları gösterilecek");
        }

        function showShelfDetails(warehouseId, shelfId) {
            const form = document.getElementById('dynamicForm');
            form.action = form.action.replace('__SHELF_ID__', shelfId); // Form action URL'sini günceller
            form.submit(); // Formu gönderir
        }

        function prepareAddProductForm(warehouseId, shelfId) {
            document.querySelector('#addProductsForm input[name="warehouse_id"]').value = warehouseId;
            document.querySelector('#addProductsForm input[name="shelf_id"]').value = shelfId;
        }

        $(document).ready(function () {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print'],
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [0, 4, 5]
                    }
                ],
                "order": [[1, 'asc']]
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');

            $('#example2 tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var warehouseId = $(this).data('warehouse-id');
                var icon = $(this).find('i');

                if (icon.length === 0) {
                    return; // Raf yoksa hiçbir şey yapma
                }

                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    icon.removeClass('bi-dash-circle').addClass('bi-plus-circle');
                } else {
                    row.child(format(warehouseId)).show();
                    tr.addClass('shown');
                    icon.removeClass('bi-plus-circle').addClass('bi-dash-circle');
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const checkboxes = document.querySelectorAll('input[name="product_ids[]"]');
            const variantCheckboxes = document.querySelectorAll('input[name="variant_ids[]"]');
            const selectAllCheckbox = document.getElementById('selectAllProducts');
            const globalQuantityInput = document.getElementById('globalQuantity');

            // "Tümünü Seç" Checkbox Kontrolü
            selectAllCheckbox.addEventListener('change', function () {
                const isChecked = this.checked;
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    const quantityInput = checkbox.closest('tr').querySelector('input[name^="quantities"]');
                    quantityInput.disabled = !isChecked;
                    if (isChecked && globalQuantityInput.value) {
                        quantityInput.value = globalQuantityInput.value;
                    } else if (!isChecked) {
                        quantityInput.value = '';
                    }
                });
                variantCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    const quantityInput = checkbox.closest('tr').querySelector('input[name^="quantities"]');
                    quantityInput.disabled = !isChecked;
                    if (isChecked && globalQuantityInput.value) {
                        quantityInput.value = globalQuantityInput.value;
                    } else if (!isChecked) {
                        quantityInput.value = '';
                    }
                });
            });

            // "Tümüne Aynı Stok Adetini Ata" Input Kontrolü
            globalQuantityInput.addEventListener('input', function () {
                const globalValue = this.value;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked && globalValue) {
                        const quantityInput = checkbox.closest('tr').querySelector('input[name^="quantities"]');
                        quantityInput.value = globalValue;
                    }
                });
                variantCheckboxes.forEach(checkbox => {
                    if (checkbox.checked && globalValue) {
                        const quantityInput = checkbox.closest('tr').querySelector('input[name^="quantities"]');
                        quantityInput.value = globalValue;
                    }
                });
            });

            // Tekil checkbox kontrolü - adet alanını etkinleştirme
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const quantityInput = this.closest('tr').querySelector('input[name^="quantities"]');
                    quantityInput.disabled = !this.checked;
                    if (!this.checked) quantityInput.value = '';
                });
            });
            variantCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const quantityInput = this.closest('tr').querySelector('input[name^="quantities"]');
                    quantityInput.disabled = !this.checked;
                    if (!this.checked) quantityInput.value = '';
                });
            });

            // Ürün Arama Fonksiyonu
            const productSearch = document.getElementById('productSearch');
            productSearch.addEventListener('keyup', function () {
                const searchTerm = productSearch.value.toLowerCase();
                const productRows = document.querySelectorAll('.product-row, .variant-row, .sub-variant-row');

                productRows.forEach(row => {
                    const productName = row.querySelector('.product-name').textContent.toLowerCase();
                    const productCode = row.querySelector('.product-code').textContent.toLowerCase();
                    row.style.display = productName.includes(searchTerm) || productCode.includes(searchTerm) ? '' : 'none';
                });
            });
        });

        function submitAddProducts() {
            // Seçili ürünlerin checkboxlarını kontrol et
            const selectedCheckboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
            //const selectedVariantCheckboxes = document.querySelectorAll('input[name="variant_ids[]"]:checked');

            // Seçili varyantların id ve is_variant değerlerini ayarlamak için bir dizi oluştur
            const selectedVariants = {};
            selectedCheckboxes.forEach(checkbox => {
                const variantId = checkbox.value; // Varyant ID'sini al
                const isVariantValue = checkbox.nextElementSibling.value; // is_variant değerini al
                selectedVariants[variantId] = isVariantValue; // Anahtar-değer çifti olarak ekle
            });

            // Seçili varyantları formda gizli bir alana ekleyin
            Object.keys(selectedVariants).forEach(variantId => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'is_variants[' + variantId + ']'; // Dizi formatında
                hiddenInput.value = selectedVariants[variantId]; // Varyant değerini ekle
                document.getElementById('addProductsForm').appendChild(hiddenInput);
            });

            // Formu gönder
            document.getElementById('addProductsForm').submit();
        }


        function openDeleteModal(warehouseId) {
            // Modal içindeki formu seçin
            const deleteForm = document.getElementById('deleteWarehouseForm');
            const routeTemplate = deleteForm.getAttribute('data-route');

            // :id kısmını gerçek warehouseId ile değiştir
            deleteForm.action = routeTemplate.replace(':id', warehouseId);
            document.getElementById('warehouse_id').value = warehouseId;

            // Modal'ı aç
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {});
            deleteModal.show();
        }



    </script>

    <style>
        td.details-control {
            cursor: pointer;
            text-align: center;
        }

        td.details-control i {
            font - size: 1.5rem;
        }
    </style>

</body>

</html>