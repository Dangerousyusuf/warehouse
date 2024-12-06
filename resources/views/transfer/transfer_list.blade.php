<!doctype html>
@include('includes.meta')
<title>teDepo | Transfer Başlat </title>

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
        <div class="breadcrumb-title pe-3">Transfer</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Transfer Listesi</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->

      <div class="product-count d-flex align-items-center gap-3 gap-lg-4 mb-4 fw-medium flex-wrap font-text1">
        <a href="javascript:;"><span class="me-1">Tümü</span><span class="text-secondary">(88754)</span></a>
        <a href="javascript:;"><span class="me-1">Aktif</span><span class="text-secondary">(56242)</span></a>
        <a href="javascript:;"><span class="me-1">Pasif</span><span class="text-secondary">(17)</span></a>
        <a href="javascript:;"><span class="me-1">Stok Dışı</span><span class="text-secondary">(88754)</span></a>
      </div>

      <div class="row g-3 mb-2">
        <div class="col-auto">
          <div class="position-relative">
            <input class="form-control px-5" type="search" placeholder="Ürün Ara">
            <span
              class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
          </div>
        </div>
        <div class="col-auto flex-grow-1 overflow-auto">
          <div class="btn-group position-static">
            <div class="btn-group position-static">
              <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown"
                aria-expanded="false">
                Kategori
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;">Maske</a></li>
                <li><a class="dropdown-item" href="javascript:;">Eldiven</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="javascript:;">Tümü</a></li>
              </ul>
            </div>
            <div class="btn-group position-static">
              <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown"
                aria-expanded="false">
                Depo
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;">Depo 1</a></li>
                <li><a class="dropdown-item" href="javascript:;">Depo 2</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="javascript:;">Tümü</a></li>
              </ul>
            </div>
            <div class="btn-group position-static">
              <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown"
                aria-expanded="false">
                Raf
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;">A-1</a></li>
                <li><a class="dropdown-item" href="javascript:;">A-2</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="javascript:;">Tümü</a></li>
              </ul>

            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="d-flex align-items-center gap-2 justify-content-lg-end">
            <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Dışa Aktar</button>
            <form>
              <a href="#" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#startTransferModal">
                <i class="bi bi-plus-lg me-2"></i>Transferi Başlat
              </a>
          </div>
        </div>
      </div><!--end row-->

      @php
    //dd($items);
    @endphp
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>Ürün Adı</th>
                  <th>Ürün Kodu</th>
                  <th>Stok</th>
                  <th>Tür</th>
                  <th>Depo</th>
                  <th>Raf</th>
                  <th>Ürün Transfer</th>
                  <th>Sil</th>
                </tr>
              </thead>
              <tbody>
                @forelse($products as $product)
          <tr>
            <td>
            <div class="d-flex align-items-center gap-3">
              <div class="product-box">
              <img src="{{ asset('storage/' . $product['image']) }}" class="rounded-3" width="50" alt="">
              </div>
              <div class="product-info">
              <a href="javascript:;" class="product-title">{{ $product['name'] }}</a>
              <input type="hidden" name="stock_id" value="{{ $product['stock_id'] }}">
              <p class="mb-0 product-category">Kategori: {{ $product['type'] }}</p>
              </div>
            </div>
            </td>
            <td>{{ $product['product_code'] }}</td>
            <td>{{ $product['quantity'] }}</td>
            <td>{{ $product['type'] }}</td>
            <td>{{ $product['warehouse'] }}</td>
            <td>{{ $product['shelf'] }}</td>
            <td>
            <div class="d-flex gap-2">
              <input type="number" name="quantity" class="form-control" placeholder="Transfer Edilecek Adet"
              min="1" max="{{ $product['quantity'] }}" required
              oninput="this.value = Math.max(Math.min(this.value, {{ $product['quantity'] }}), 1)"
              value="{{ old('quantity') }}">
              <input type="hidden" name="stock_id" value="{{ $product['stock_id'] }}">
            </div>
            </td>
            <td>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
              data-bs-target="#deleteModal" data-stock-id="{{ $product['stock_id'] }}">
              Sil
              </button>
            </div>
            </td>
          </tr>
          </form>
        @empty
      <tr>
        <td colspan="8" class="text-center">Veri bulunamadı.</td>
      </tr>
    @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="TransferModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 py-2 bg-grd-info">
            <h5 class="modal-title text-white">Ürün Transferi</h5>
            <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
              <i class="material-icons-outlined">close</i>
            </a>
          </div>
          <div class="modal-body">
            <div class="form-body">
              <form class="row g-3">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <h6>Depo 1</h6>
                      <p>Ürün Adı: <span id="depo1ProductName">N95 Maske</span></p>
                      <p>Ürün Kodu: <span id="depo1ProductCode">ABC57TY</span></p>
                      <p>Mevcut Stok: <span id="depo1CurrentStock">1000</span></p>
                      <p style="color: red;">Transfer Sonrası Stok: 1000</p>
                    </div>
                    <div class="col-md-6">
                      <h6>Depo 2</h6>
                      <p>Ürün Adı: <span id="depo2ProductName">N95 Maske</span></p>
                      <p>Ürün Kodu: <span id="depo2ProductCode">ABC57TY</span></p>
                      <p>Mevcut Stok: <span id="depo2CurrentStock">500</span></p>
                      <p style="color: green;">Transfer Sonrası Stok: 500</p>
                    </div>
                  </div>


                </div>
                <div class="col-md-12">
                  <label for="inputStockAmount" class="form-label">Transfer Miktarı</label>
                  <div class="input-group">
                    <button type="button" class="btn btn-danger" id="decreaseStock">-</button>
                    <input type="number" class="form-control text-center fs-4" id="inputStockAmount"
                      placeholder="Transfer miktarını giriniz" min="0" max="1000" style="font-weight: bold;">
                    <button type="button" class="btn btn-success" id="increaseStock">+</button>
                  </div>
                </div>

                <div class="col-md-12" id="warehouseSection">
                  <label for="warehouseLocation" class="form-label">Hedef Depo</label>
                  <select id="warehouseLocation" class="form-select">
                    <option selected="">Seçiniz...</option>

                  </select>
                </div>

                <div class="col-md-12">
                  <label for="inputStockNote" class="form-label">Not</label>
                  <textarea class="form-control" id="inputStockNote" placeholder="Transfer notu..." rows="3"></textarea>
                </div>
                <div class="col-md-12">
                  <div class="d-md-flex d-grid align-items-center gap-3 justify-content-end">
                    <button type="button" class="btn btn-grd-secondary px-4" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-grd-success px-4 text-white">Transfer Et</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Transfer Başlat Modalı -->
    <div class="modal fade" id="startTransferModal" tabindex="-1" aria-labelledby="startTransferModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="startTransferModalLabel">Transfer İşlemi Başlat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <form id="startTransferForm" action="{{ route('transfer.store') }}" method="POST">
          @csrf
              <div class="mb-3">
                <label for="warehouseLocation" class="form-label">Hedef Depo</label>
                <select id="warehouseLocation" name="to_warehouse" class="form-select" required>
                  <option value="" selected disabled>Seçiniz...</option>
                  @foreach ($warehouses as $warehouse)
            <option value="{{ $warehouse->id }}">{{ $warehouse->name}}</option>
          @endforeach
                  <!-- Diğer depoları buraya ekleyin -->
                </select>
              </div>
              <input type="hidden" name="products" id="productsData">
              <input type="hidden" name="transferListId" value="{{$transferListId}}">
              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                <button type="submit" class="btn btn-primary">Onayla</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Silme Modalı -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Silme Onayı</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Bu ürünün transferini silmek istediğinizden emin misiniz?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
            <form id="deleteForm" action="{{ route('transfer.destroy') }}" method="POST">
              @csrf
              @method('DELETE')
              <input type="hidden" name="stock_id" id="stockId">
              <button type="submit" class="btn btn-danger" onclick="deleteFromLocalStorage()">Sil</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>


  <!--start overlay-->
  <div class="overlay btn-toggle"></div>
  <!--end overlay-->

  <!--start footer-->
  @include('includes.footer');
  <!--top footer-->

  <!--start switcher-->
  @include('includes.switcher')
  <!--end switcher-->


  <script>


    $(document).ready(function () {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      console.log(cart);
      $('#example').DataTable();
      var table = $('#example2').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'print']
      });

      table.buttons().container()
        .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });

    // Modal açıldığında stock_id'yi ayarlama
    document.addEventListener('DOMContentLoaded', function () {

      const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteModal"]');
      deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
          const stockId = this.getAttribute('data-stock-id');
          document.getElementById('stockId').value = stockId;
          deleteFromLocalStorage(stockId);

        });
      });

      const startTransferButton = document.querySelector('[data-bs-target="#startTransferModal"]');

      startTransferButton.addEventListener('click', function () {
        const products = [];
        const productRows = document.querySelectorAll('tbody tr'); // Ürün satırlarını seçin

        productRows.forEach(row => {
          const quantityInput = row.querySelector('input[name="quantity"]'); // Adet inputunu seçin
          const stockIdInput = row.querySelector('input[name="stock_id"]'); // Stok ID'sini alın
          if (quantityInput && quantityInput.value > 0) {
            products.push({
              stock_id: stockIdInput.value,
              quantity: quantityInput.value,
            });
          }
        });

        document.getElementById('productsData').value = JSON.stringify(products); // Ürün verilerini JSON formatında saklayın
      });
    });

    function deleteFromLocalStorage(stockId) {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      cart = cart.filter(item => item.stock_id != stockId); // Stock ID'ye göre ürünü çıkar
      localStorage.setItem('cart', JSON.stringify(cart)); // Güncellenmiş listeyi kaydet
      console.log(`Stock ID ${stockId} silindi.`);
    }

  </script>

</body>

</html>
