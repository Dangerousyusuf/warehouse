<!doctype html>
@include('includes.meta')
<title>teDepo | Ürün Ekleme </title>

<body>

    <!--start header-->
    @include('includes.header')
    <!--end top header-->

    <!--start sidebar-->
    @include('includes.sidebar')
    <!--end sidebar-->


    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Ürün</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Ürün Ekle</li>
                        </ol>
                    </nav>
                </div>

            </div>

            <!--end breadcrumb-->
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('products.update', $product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-4 row">
                                    <div class="col-6">
                                        <h5 class="mb-3">Ürün Adı</h5>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="product_name" name="name" value="{{ old('name', $product->name) }}"
                                            placeholder="Ürün Adını Giriniz" required
                                            oninput="this.value = this.value.toUpperCase();">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <h5 class="mb-3">Ürün Kodu</h5>
                                        <input type="text"
                                            class="form-control @error('product_code') is-invalid @enderror"
                                            id="product_code" name="product_code"
                                            value="{{ old('product_code', $product->product_code) }}"
                                            placeholder="Ürün Kodu Giriniz" required
                                            oninput="this.value = this.value.toUpperCase();">
                                        @error('product_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4 row">
                                    <div class="col-6">
                                        <h5 class="mb-3">Barkod</h5>
                                        <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                            id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}"
                                            placeholder="Barkod Giriniz">
                                        @error('barcode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <h5 class="mb-3">Kategori</h5>
                                        <select class="form-select" id="AddCategory" name="category_id" required>
                                            <option value="" selected>Kategori Seçiniz</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <h5 class="mb-3">Ürün Açıklaması</h5>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" placeholder="Ürün Açıklamasını Giriniz"
                                        rows="4">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <h5 class="mb-3">Ürün Resmi</h5>
                                    <input class="form-control @error('images.*') is-invalid @enderror"
                                        id="image-uploadify" type="file" name="images[]"
                                        accept=".jpg, .png, image/jpeg, image/png" multiple>
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($product->image)
                                        <div class="mt-3">
                                            <h5 class="mb-3">Ürün Fotoğrafı</h5>
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="Ürün Resmi"
                                                class="img-fluid" style="width: 10%; height: auto;">
                                        </div>
                                    @endif
                                </div>
                              
                                   
                                    <div class="mb-4">
                                      
                                        <div class="col">
                                            <div class="card">
                                                <div class="card-body">
                                                    <ul class="nav nav-tabs nav-danger" role="tablist">
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link active" data-bs-toggle="tab"
                                                                href="#stockSettings" role="tab" aria-selected="true">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="tab-icon"><i
                                                                            class="bi bi-box me-1 fs-6"></i>
                                                                    </div>
                                                                    <div class="tab-title">Stok</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                href="#variantSettings" role="tab" aria-selected="true">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="tab-icon"><i
                                                                            class="bi bi-list-check me-1 fs-6"></i>
                                                                    </div>
                                                                    <div class="tab-title">Varyant</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                href="#attributesSettings" role="tab"
                                                                aria-selected="false">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="tab-icon"><i
                                                                            class="bi bi-clipboard-data me-1 fs-6"></i>
                                                                    </div>
                                                                    <div class="tab-title">Özellikler</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                href="#pricingSettings" role="tab"
                                                                aria-selected="false">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="tab-icon"><i
                                                                            class='bi bi-cash me-1 fs-6'></i>
                                                                    </div>
                                                                    <div class="tab-title">Ücret</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                        <li class="nav-item" role="presentation">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                href="#advancedAttributesSettings" role="tab"
                                                                aria-selected="false">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="tab-icon"><i
                                                                            class='bi bi-gear me-1 fs-6'></i>
                                                                    </div>
                                                                    <div class="tab-title">Gelişmiş Özellikler</div>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content py-3">
                                                        <div class="tab-pane fade show active" id="stockSettings"
                                                            role="tabpanel">
                                                            <h6 class="mb-3">Stok</h6>
                                                            <div class="row g-3 mt-3">
                                                                <div class="col-6">
                                                                    <label for="Brand" class="form-label">Kritik Stok
                                                                        Seviyesi</label>
                                                                    <input type="number" class="form-control"
                                                                        id="critical_stock_level"
                                                                        name="critical_stock_level"
                                                                        placeholder="Kritik Stok Seviyesi"
                                                                        value="{{ old('critical_stock_level', $product->critical_stock_level) }}">
                                                                </div>

                                                                <div class="row g-3 mt-3" id="warehouse_stock_inputs"
                                                                    style="display: none;">
                                                                    @forelse(Auth::user()->warehouses as $warehouse)
                                                                        <div class="col-sm-12">
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <label
                                                                                        for="stock_{{ $warehouse->id }}">{{ $warehouse->name }}</label>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <input class="form-control"
                                                                                        type="number"
                                                                                        id="stock_{{ $warehouse->id }}"
                                                                                        name="stock[{{ $warehouse->id }}]"
                                                                                        placeholder="Stok Miktarı"
                                                                                        value="{{ old('stock.' . $warehouse->id, '') }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @empty
                                                                        <div class="col-sm-12">
                                                                            <div class="alert alert-warning">
                                                                                Yetkili olduğunuz depo bulunamadı.
                                                                            </div>
                                                                        </div>
                                                                    @endforelse
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="tab-pane fade" id="variantSettings" role="tabpanel">
                                                            <h6 class="mb-3">Varyant Ayarları</h6>
                                                            <div id="variant-table-container">
                                                                <!-- Mevcut Varyantlar -->
                                                                <table class="table table-bordered">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Varyant Değeri</th>
                                                                            <th>Ürün Varyant Adı</th>
                                                                            <th>Varyant SKU</th>
                                                                            <th>Varyant Görseli</th>
                                                                            <th>Alt Varyant Ekle</th>
                                                                            <th>Sil</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($product->children as $variantIndex => $variant)
                                                                            <!-- Ana Varyant -->
                                                                            <tr>
                                                                                <td>{{ $variant->name }}</td>
                                                                                <td>
                                                                                    <input type="text" class="form-control"
                                                                                        name="variant_names[{{ $variant->id }}]"
                                                                                        value="{{ old('variant_names.' . $variant->id, $variant->name) }}"
                                                                                        placeholder="Varyant Adı">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control"
                                                                                        name="variant_skus[{{ $variant->id }}]"
                                                                                        value="{{ old('variant_skus.' . $variant->id, $variant->product_code) }}"
                                                                                        placeholder="Varyant SKU">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="file" class="form-control"
                                                                                        name="variant_images[{{ $variant->id }}]">
                                                                                    @if($variant->image)
                                                                                        <img src="{{ asset('storage/' . $variant->image) }}"
                                                                                            alt="Varyant Görseli"
                                                                                            class="img-fluid mt-2"
                                                                                            style="width: 50px; height: auto;">
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-primary add-sub-variant-btn"
                                                                                        data-variant-id="{{ $variant->id }}">Alt
                                                                                        Varyant Ekle</button>
                                                                                </td>
                                                                                <td>
                                                                                    <button type="button"
                                                                                        class="btn btn-danger remove-row-btn">Sil</button>
                                                                                </td>
                                                                            </tr>

                                                                            <!-- Alt Varyantlar -->
                                                                            @foreach($variant->children as $subVariantIndex => $subVariant)
                                                                                <tr class="alt-variant-row">
                                                                                    <td>-- {{ $subVariant->name }}</td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control"
                                                                                            name="sub_variant_names[{{ $subVariant->id }}]"
                                                                                            value="{{ old('sub_variant_names.' . $subVariant->id, $subVariant->name) }}"
                                                                                            placeholder="Alt Varyant Adı">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" class="form-control"
                                                                                            name="sub_variant_skus[{{ $subVariant->id }}]"
                                                                                            value="{{ old('sub_variant_skus.' . $subVariant->id, $subVariant->product_code) }}"
                                                                                            placeholder="Alt Varyant SKU">
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="file" class="form-control"
                                                                                            name="sub_variant_images[{{ $subVariant->id }}]">
                                                                                        @if($subVariant->image)
                                                                                            <img src="{{ asset('storage/' . $subVariant->image) }}"
                                                                                                alt="Alt Varyant Görseli"
                                                                                                class="img-fluid mt-2"
                                                                                                style="width: 50px; height: auto;">
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary"
                                                                                            disabled>Alt Varyant</button>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button"
                                                                                            class="btn btn-danger remove-row-btn">Sil</button>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- Yeni Varyant Ekle -->
                                                            <button type="button" id="add-variant-btn" class="btn btn-primary">Varyant
                                                            Ekle</button>
                                                        </div>



                                                        <div class="tab-pane fade" id="attributesSettings"
                                                            role="tabpanel">
                                                            <h6 class="mb-3">Özellikler</h6>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label class="form-label">Özellik</label>
                                                                    <select class="form-select" id="unit" name="unit"
                                                                        required>
                                                                        @foreach($units as $unit)
                                                                            <option value="{{ $unit }}" {{ old('unit', $product->unit) == $unit ? 'selected' : '' }}>
                                                                                {{ $unit }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label">Ürün Türü</label>
                                                                    <select class="form-select" id="product_type"
                                                                        name="product_type" required>
                                                                        @foreach($types as $type)
                                                                            <option value="{{ $type }}" {{ old('product_type', $product->product_type) == $type ? 'selected' : '' }}>
                                                                                {{ $type }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label for="Brand" class="form-label">Ürün
                                                                        Ağırlığı</label>
                                                                    <div class="row">
                                                                        <div class="col-8">
                                                                            <input type="number" class="form-control"
                                                                                id="weight" name="weight"
                                                                                placeholder="Ürün Ağırlığı"
                                                                                value="{{ old('weight', $product->weight) }}">
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <select class="form-select" id="weight_unit"
                                                                                name="weight_unit">
                                                                                <option value="kg" {{ old('weight_unit', $product->weight_unit) == 'kg' ? 'selected' : '' }}>KG
                                                                                </option>
                                                                                <option value="g" {{ old('weight_unit', $product->weight_unit) == 'g' ? 'selected' : '' }}>G
                                                                                </option>
                                                                                <option value="l" {{ old('weight_unit', $product->weight_unit) == 'l' ? 'selected' : '' }}>L
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mt-2">
                                                                    <label for="Brand" class="form-label">Ürün
                                                                        Boyutu</label>
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <input type="text" class="form-control"
                                                                                id="size_x" name="size_x"
                                                                                placeholder="En"
                                                                                value="{{ old('size_x', $product->size_x) }}">
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <input type="text" class="form-control"
                                                                                id="size_y" name="size_y"
                                                                                placeholder="Boy"
                                                                                value="{{ old('size_y', $product->size_y) }}">
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <input type="text" class="form-control"
                                                                                id="size_z" name="size_z"
                                                                                placeholder="Kalınlık"
                                                                                value="{{ old('size_z', $product->size_z) }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>



                                                        <div class="tab-pane fade" id="pricingSettings" role="tabpanel">
                                                            <h6 class="mb-3">Ücret</h6>
                                                            <div class="row g-3">
                                                                <div class="col-6">
                                                                    <h6 class="mb-2 mt-3">Standart Fiyatı</h6>
                                                                    <input
                                                                        class="form-control @error('standard_price') is-invalid @enderror"
                                                                        type="text" id="standard_price"
                                                                        name="standard_price"
                                                                        value="{{ old('standard_price', $product->standard_price) }}"
                                                                        placeholder="$$$">
                                                                    @error('standard_price')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror

                                                                </div>
                                                                <div class="col-6">
                                                                    <h6 class="mb-2 mt-3">Satış Fiyatı</h6>
                                                                    <input
                                                                        class="form-control @error('sale_price') is-invalid @enderror"
                                                                        type="text" id="sale_price" name="sale_price"
                                                                        value="{{ old('sale_price', $product->sale_price) }}"
                                                                        placeholder="$$$">
                                                                    @error('sale_price')
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror

                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="tab-pane fade" id="advancedAttributesSettings"
                                                            role="tabpanel">
                                                            <h6 class="mb-3">Gelişmiş Özellikler</h6>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <label class="form-label">Otomatik Sipariş
                                                                        Miktarı</label>
                                                                    <input class="form-control" type="number"
                                                                        id="auto_order_quantity"
                                                                        name="auto_order_quantity"
                                                                        placeholder="Otomatik Sipariş Miktarı"
                                                                        value="{{ old('auto_order_quantity', $product->auto_order_quantity) }}">
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-6">
                                                                        <label class="form-label">Tahmini Günlük
                                                                            Kullanım</label>
                                                                        <input class="form-control" type="number"
                                                                            id="estimated_daily_usage"
                                                                            name="estimated_daily_usage"
                                                                            placeholder="Tahmini Günlük Kullanım"
                                                                            value="{{ old('estimated_daily_usage', $product->estimated_daily_usage) }}">
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="form-label">Tahmini Termin
                                                                            Süresi</label>
                                                                        <input class="form-control" type="number"
                                                                            id="estimated_delivery_time"
                                                                            name="estimated_delivery_time"
                                                                            placeholder="Tahmini Termin Süresi"
                                                                            value="{{ old('estimated_delivery_time', $product->estimated_delivery_time) }}">
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="AddCategory" class="form-label">Durum</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Pasif
                                            </option>
                                        </select>
                                    </div>

                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="button" class="btn btn-outline-danger btn-lg"><i
                                                    class="bi bi-x-circle me-2"></i>İptal</button>
                                            <button type="submit" class="btn btn-outline-primary btn-lg"><i
                                                    class="bi bi-cloud-download me-2"></i>Kaydet</button>
                                        </div>
                                    </div>
                            </form>
                        </div>



                    </div>

                </div>


            </div>

        </div><!--end row-->
        </div>
    </main>
    <!--end main wrapper-->


    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

    <!--start footer-->
    @include('includes.footer')
    <!--top footer-->




    <!--start switcher-->
    @include('includes.switcher')
    <!--start switcher-->

    <script>

        $(document).ready(function () {
            $('#image-uploadify').imageuploadify();
            $('#stock-tab').click(); // Stok kısmını aç
            // Assuming this is your $variations array
        })

        document.addEventListener('DOMContentLoaded', function () {
    const addVariantBtn = document.getElementById('add-variant-btn'); // Varyant Ekle Butonu
    const variantTableBody = document.querySelector('#variant-table-container tbody'); // Tablo Gövdesi
    let variantCounter = 0; // Yeni eklenen varyantlar için sayaç

    // Varyant Ekle Butonuna Tıklanınca
    addVariantBtn.addEventListener('click', function () {
        variantCounter++; // Sayaç artır

        // Yeni Varyant HTML'i
        const newRow = `
            <tr data-variant-id="new_${variantCounter}">
                <td>
                    <input type="text" class="form-control" name="variant_names[new][${variantCounter}]" placeholder="Varyant Adı">
                </td>
                <td>
                    <input type="text" class="form-control" name="variant_skus[new][${variantCounter}]" placeholder="Varyant SKU">
                </td>
                <td>
                    <input type="file" class="form-control" name="variant_images[new][${variantCounter}]">
                </td>
                <td>
                    <button type="button" class="btn btn-primary add-sub-variant-btn" data-variant-id="new_${variantCounter}">Alt Varyant Ekle</button>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row-btn">Sil</button>
                </td>
            </tr>
        `;

        // Yeni Varyantı Tabloya Ekle
        variantTableBody.insertAdjacentHTML('beforeend', newRow);

        // Silme Butonu Etkinleştir
        const removeButtons = document.querySelectorAll('.remove-row-btn');
        removeButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                btn.closest('tr').remove(); // Satırı kaldır
            });
        });

        // Alt Varyant Ekle Butonu Etkinleştir
        const addSubVariantButtons = document.querySelectorAll('.add-sub-variant-btn');
        addSubVariantButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                addSubVariantRow(btn.getAttribute('data-variant-id'));
            });
        });
    });

    // Alt Varyant Eklemek için İşlev
    function addSubVariantRow(parentVariantId) {
        const parentRow = document.querySelector(`[data-variant-id="${parentVariantId}"]`);
        const newSubRow = `
            <tr data-parent-variant-id="${parentVariantId}">
                <td>-- Alt Varyant</td>
                <td>
                    <input type="text" class="form-control" name="sub_variant_names[${parentVariantId}][]" placeholder="Alt Varyant Adı">
                </td>
                <td>
                    <input type="text" class="form-control" name="sub_variant_skus[${parentVariantId}][]" placeholder="Alt Varyant SKU">
                </td>
                <td>
                    <input type="file" class="form-control" name="sub_variant_images[${parentVariantId}][]">
                </td>
                <td>
                    <button type="button" class="btn btn-secondary" disabled>Alt Varyant</button>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row-btn">Sil</button>
                </td>
            </tr>
        `;

        parentRow.insertAdjacentHTML('afterend', newSubRow);

        // Silme Butonunu Etkinleştir
        const removeButtons = document.querySelectorAll('.remove-row-btn');
        removeButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                btn.closest('tr').remove(); // Satırı kaldır
            });
        });
    }
});



        // Varyant türü için tablo oluşturma işlevi
        function createVariantTable(container, variantId) {
            const tableHtml = `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Değer</th>
                            <th>Ürün Varyant Adı</th>
                            <th>Varyant Ürün Kodu</th>
                            <th>Varyasyon Görüntüleri</th>
                            <th>Alt Varyant Ekle</th>
                            <th>Sil</th>
                        </tr>
                    </thead>
                    <tbody class="variant-details-body"></tbody>
                </table>
            `;
            container.innerHTML = tableHtml;
        }

        // Varyant değerlerini tabloya ekleme işlevi
        function addVariantDetailRow(variantOptionId, variantValue, tableContainer, variantId, button) {
            const variantDetailsBody = tableContainer.querySelector('.variant-details-body');

            // Varyant tablosunda bu değer zaten var mı kontrol et
            const existingRow = variantDetailsBody.querySelector(`tr[data-variant-value="${variantValue}"]`);
            if (existingRow) {
                return; // Aynı varyantı tekrar eklemeyelim
            }

            // Buton rengini değiştirerek seçili olduğunu belirt
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-info');

            const productName = document.getElementById('product_name').value;
            const productCode = document.getElementById('product_code').value;

            const variantProductName = `${productName} - ${variantValue.toUpperCase()}`;
            const variantSku = `${productCode}-${variantValue.charAt(0).toUpperCase()}`;

            // Yeni varyant satırı ekle
            const row = document.createElement('tr');
            row.setAttribute('data-variant-value', variantValue);
            row.setAttribute('data-variant-id', variantId);
            row.setAttribute('data-variant-option-id', variantOptionId);

            row.innerHTML = `
                <td>${variantValue}</td>
                <td><input type="text" class="form-control" name="variant_names[${variantId}][${variantOptionId}]" value="${variantProductName}" placeholder="Ürün Adı"></td>
                <td><input type="text" class="form-control" name="variant_skus[${variantId}][${variantOptionId}]" value="${variantSku}" placeholder="Ürün Kodu"></td>
                <td><input type="file" name="variant_images[${variantId}][${variantOptionId}]"></td>
                <td><button type="button" class="btn btn-primary add-sub-variant-btn">Alt Varyant Ekle</button></td>
                <td><button type="button" class="btn btn-danger remove-row-btn">Sil</button></td>
            `;

            // Alt varyant ekleme işlevi
            row.querySelector('.add-sub-variant-btn').addEventListener('click', function () {
                addSubVariantRow(variantOptionId, row, tableContainer, variantValue, variantId);
            });

            // Satır silme işlevi
            row.querySelector('.remove-row-btn').addEventListener('click', function () {
                row.remove();
                button.classList.remove('btn-info');
                button.classList.add('btn-outline-primary');
            });

            variantDetailsBody.appendChild(row);
        }

        // Alt varyant satırı ekleme işlevi
        function addSubVariantRow(parentVariantOptionId, parentRow, tableContainer, parentVariantValue, variantId) {
            const subVariantRow = document.createElement('tr');
            subVariantRow.innerHTML = `
                <td>Alt Varyant</td>
                <td><input type="text" class="form-control" name="sub_variant_names[${parentVariantOptionId}][]" placeholder="Alt Varyant Adı"></td>
                <td><input type="text" class="form-control" name="sub_variant_skus[${parentVariantOptionId}][]" placeholder="Alt Varyant SKU"></td>
                <td><input type="file" name="sub_variant_images[${parentVariantOptionId}][]"></td>
                <td>
                    <select class="form-select sub-variant-select" data-parent-id="${parentVariantOptionId}" name="sub_variant_option_ids[${parentVariantOptionId}][]">
                        <option value="">Alt Varyant Seç</option>
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger remove-row-btn">Sil</button></td>
            `;

            // Alt varyant seçeneklerini ekleyelim
            const subVariantSelect = subVariantRow.querySelector('.sub-variant-select');
            variations.forEach(variation => {
                if (variation.id !== variantId) {
                    variation.options.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option.id;
                        optionElement.textContent = `${variation.name}: ${option.value}`;
                        subVariantSelect.appendChild(optionElement);
                    });
                }
            });

            subVariantRow.querySelector('.remove-row-btn').addEventListener('click', function () {
                subVariantRow.remove();
            });

            parentRow.after(subVariantRow);
        }

    </script>

</body>

</html>