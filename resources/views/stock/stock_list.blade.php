<!doctype html>
@include('includes.meta')
<title>teDepo | Stok Liste </title>

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
        <div class="breadcrumb-title pe-3">Stok</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Stok Listesi</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->

      <div class="product-count d-flex align-items-center gap-3 gap-lg-4 mb-4 fw-medium flex-wrap font-text1">
        <a href="{{ route('product.product_list') }}">
          <span class="me-1">Tümü</span>
          <span class="text-secondary">({{ $totalAllProducts   }})</span>
        </a>
        <a href="{{ route('product.product_list', ['status' => 'active']) }}">
          <span class="me-1">Ana Ürün</span>
          <span class="text-secondary">({{ $activeProducts }})</span>
        </a>
        <a href="{{ route('product.product_list', ['status' => 'inactive']) }}">
          <span class="me-1">Pasif</span>
          <span class="text-secondary">({{ $inactiveProducts }})</span>
        </a>
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
                @foreach($categories as $category)
          <li>
            <a class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="category-{{ $category->id }}"
              onchange="toggleCategory('{{ $category->slug }}')">
            <label for="category-{{ $category->id }}">{{ $category->name }}</label>
            </a>
          </li>
        @endforeach
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="javascript:void(0);" onclick="resetFilter()">Tümü</a></li>
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
          </div>
        </div>
      </div><!--end row-->
      <div class="card mt-4">
        <div id="stock-list">
          @include('stock.partials.stock_list', ['products' => $products]) <!-- İlk yüklemede tüm ürünler -->
        </div>

      </div>
    </div>

    <div class="modal fade" id="FormModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 py-2 bg-grd-info">
            <h5 class="modal-title text-white">Stok Hareketi Ekle</h5>
            <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
              <i class="material-icons-outlined">close</i>
            </a>
          </div>
          <div class="modal-body">
            <div class="form-body">
              <form class="row g-3" action="{{ route('update.stock') }}" method="POST" id="stockForm">
                @csrf
                <input type="hidden" id="productIdInput" name="product_id">
                <input type="hidden" id="stockId" name="stockId">

                <div class="col-md-12">
                  <h6>Ürün Adı: <span id="currentProductName">N95 Maske</span></h6>
                </div>
                <div class="col-md-12">
                  <h6>Ürün Kodu: <span id="currentProductCode">ABC57TY</span></h6>
                </div>
                <div class="col-md-12">
                  <h6>Depo: <span id="currentWarehouse">Depo-1</span></h6>
                </div>
                <div class="col-md-12">
                  <h6>Raf: <span id="currentShelve">Raf-1</span></h6>
                </div>
                <div class="col-md-12 mb-1">
                  <h6>Mevcut Stok: <span id="currentStock">1000</span></h6>
                </div>

                <div class="col-md-12">
                  <label for="inputStockAmount" class="form-label">Stok Miktarı</label>
                  <div class="input-group">
                    <button type="button" class="btn btn-danger" id="decreaseStock">-</button>
                    <input type="number" class="form-control text-center fs-4" id="inputStockAmount" name="stock_amount"
                      placeholder="Stok miktarını giriniz" min="0" max="1000" style="font-weight: bold;">
                    <button type="button" class="btn btn-success" id="increaseStock">+</button>
                  </div>
                </div>
                <div class="col-md-12">
                  <label for="inputStockAction" class="form-label">İşlem</label>
                  <select id="inputStockAction" class="form-select" name="stock_action" onchange="toggleStockOutput()">
                    <option selected="">Seçiniz...</option>
                    <option value="add">Stok Ekle</option>
                    <option value="remove" selected>Stok Çıkar</option>
                  </select>
                </div>
                <div id="stockOutputSection" class="col-md-12">
                  <label for="transferType" class="form-label">Stok Çıkışı</label>
                  <select id="transferType" class="form-select" name="stock_out">
                    <option selected="">Seçiniz...</option>
                    @foreach(explode(',', $stockDropCategory) as $category)
            <option value="{{ $category }}">{{ $category }}</option>
          @endforeach
                  </select>
                </div>
                <div class="col-md-12">
                  <label for="inputStockNote" class="form-label">Not</label>
                  <textarea class="form-control" id="inputStockNote" name="note" placeholder="İşlem notu..."
                    rows="3"></textarea>
                </div>
                <div class="col-md-12">
                  <div class="d-md-flex d-grid align-items-center gap-3 justify-content-end">
                    <button type="button" class="btn btn-grd-secondary px-4" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" id="saveStockButton"
                      class="btn btn-grd-success px-4 text-white">Kaydet</button>
                  </div>
                </div>
              </form>
            </div>
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
              <form class="row g-3" action="{{ route('update.stock') }}" method="POST" name="transferForm">
                @csrf
                <input type="hidden" id="transferproductIdInput" name="product_id">
                <input type="hidden" id="transferStockId" name="stockId">
                <input type="hidden" id="stockAction" name="stock_action" value="transfer">

                <div class="col-md-12">
                  <h6>Ürün Adı: <span id="currentProductName">N95 Maske</span></h6>
                </div>
                <div class="col-md-12">
                  <h6>Ürün Kodu: <span id="currentProductCode">ABC57TY</span></h6>
                </div>
                <div class="col-md-12">
                  <h6>Depo: <span id="currentWarehouse">DEPO-1</span></h6>
                </div>
                <div class="col-md-12 mb-3">
                  <h6>Mevcut Stok: <span id="currentStock">1000</span></h6>
                </div>
                <div class="col-md-12">
                  <label for="transferinputStockAmount" class="form-label">Transfer Miktarı</label>
                  <div class="input-group">
                    <button type="button" class="btn btn-danger" id="decreaseStock">-</button>
                    <input type="number" class="form-control text-center fs-4" id="transferinputStockAmount"
                      name="stock_amount" placeholder="Transfer miktarını giriniz" min="0" style="font-weight: bold;">
                    <button type="button" class="btn btn-success" id="increaseStock">+</button>
                  </div>
                </div>

                <div class="col-md-12" id="warehouseSection">
                  <label for="toWarehouse" class="form-label">Transfer Hedefi</label>
                  <select id="toWarehouse" class="form-select" name="to_warehouse">
                    <option selected="">Seçiniz...</option>
                    @foreach($warehouses as $warehouse)
            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
          @endforeach
                  </select>
                </div>
                <div class="col-md-12">
                  <label for="transferinputStockNote" class="form-label">Not</label>
                  <textarea class="form-control" id="transferinputStockNote" name="note" placeholder="Transfer notu..."
                    rows="3"></textarea>
                </div>
                <div class="col-md-12">
                  <div class="d-md-flex d-grid align-items-center gap-3 justify-content-end">
                    <button type="button" class="btn btn-grd-secondary px-4" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" id="saveTransferButton" class="btn btn-grd-success px-4 text-white">Transfer
                      Et</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>


    <script>

      function setupFormModal(formModal) {
        formModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;

          const productId = button.getAttribute('data-product-id'); // Ürün ID'sini al
          const productName = button.getAttribute('data-product-name');
          const productCode = button.getAttribute('data-product-code');
          const stockQuantity = button.getAttribute('data-stock-quantity');
          const warehouseName = button.getAttribute('data-warehouse-name');
          const shelveName = button.getAttribute('data-shelve-name');
          const stockId = button.getAttribute('data-stock-id');

          formModal.querySelector('#currentStock').textContent = stockQuantity;
          formModal.querySelector('#currentProductName').textContent = productName;
          formModal.querySelector('#currentProductCode').textContent = productCode;
          formModal.querySelector('#currentWarehouse').textContent = warehouseName;
          formModal.querySelector('#currentShelve').textContent = shelveName;

          formModal.querySelector('#productIdInput').value = productId;  // Form içinde productId saklanır
          formModal.querySelector('#stockId').value = stockId;  // Form içinde productId saklanır
        });
      }

      function setupTransferFormModal(transferFormModal) {
        transferFormModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;

          const productId = button.getAttribute('data-product-id'); // Ürün ID'sini al
          const productName = button.getAttribute('data-product-name');
          const productCode = button.getAttribute('data-product-code');
          const stockQuantity = button.getAttribute('data-stock-quantity');
          const warehouseName = button.getAttribute('data-warehouse-name');
          const stockId = button.getAttribute('data-stock-id');

          transferFormModal.querySelector('#currentStock').textContent = stockQuantity;
          transferFormModal.querySelector('#currentProductName').textContent = productName;
          transferFormModal.querySelector('#currentProductCode').textContent = productCode;
          transferFormModal.querySelector('#currentWarehouse').textContent = warehouseName;
          transferFormModal.querySelector('#transferproductIdInput').value = productId;
          transferFormModal.querySelector('#transferStockId').value = stockId;  // Form içinde productId saklanır
        });
      }

      const formModal = document.getElementById('FormModal');
      setupFormModal(formModal);

      const transferFormModal = document.getElementById('TransferModal');
      setupTransferFormModal(transferFormModal);

      const currentStock = 10000; // Bu değer dinamik olarak ayarlanmalıdır
      const stockInput = document.getElementById('inputStockAmount');
      const decreaseBtn = document.getElementById('decreaseStock');
      const increaseBtn = document.getElementById('increaseStock');

      function updateStockInput(value) {
        stockInput.value = Math.max(0, Math.min(value, currentStock));
      }

      decreaseBtn.addEventListener('click', () => updateStockInput(parseInt(stockInput.value || 0) - 1));
      increaseBtn.addEventListener('click', () => updateStockInput(parseInt(stockInput.value || 0) + 1));

      stockInput.addEventListener('input', () => updateStockInput(parseInt(stockInput.value || 0)));

      function toggleStockOutput() {
        const stockAction = document.getElementById('inputStockAction').value;
        const stockOutputSection = document.getElementById('stockOutputSection');
        stockOutputSection.style.display = stockAction === 'remove' ? 'block' : 'none';
      }

    </script>
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
    $(document).ready(function () {
      // Eer tablo daha önce başlatılmamışsa DataTable başlat
      if (!$.fn.DataTable.isDataTable('#example2')) {
        var table = $('#example2').DataTable({
          lengthChange: false,
          buttons: ['copy', 'excel', 'pdf', 'print']
        });
      }

      table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

      var productsData = @json($products);

      // "Depo/Raf Bilgisi" butonuna basıldığında detayları açma/kapama
      $('#example2 tbody').on('click', 'button.toggle-details', function () {

        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var productId = $(this).data('product-id');

        if (row.child.isShown()) {
          // Detay satırını gizle
          row.child.hide();
          tr.removeClass('shown');
        } else {
          // Detay içeriğini yükle
          var detailContent = getDetailContent(productId);  // Dinamik içerik fonksiyonu
          row.child(detailContent).show();
          tr.addClass('shown');
        }
      });

      // Ürüne göre dinamik detay içeriğini döndürme fonksiyonu
      function getDetailContent(productId) {
        // Örnek veri; productsData'nın JSON olarak yüklendiğini varsayıyoruz
        var product = productsData.find(p => p.id === productId);
        var warehouseId = @json($warehouseId);

        if (!product) return '<div>Ürün bilgisi bulunamadı.</div>';

        // Stok bilgilerini detay satırı olarak ekleme
        var detailRows = product.stocks.filter(stock => stock.warehouse.id === warehouseId).map(stock => `
            <tr>
                <td>${stock.warehouse.name}</td>
                <td>${stock.shelf ? stock.shelf.name : ''}</td>
                <td>${stock.stock_quantity}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                        data-bs-target="#FormModal" data-product-id="${product.id}"
                        data-stock-id="${stock.id}" data-product-name="${product.name}"
                        data-product-code="${product.product_code}"
                        data-stock-quantity="${stock.stock_quantity}"
                        data-warehouse-name="${stock.warehouse.name}"
                        data-shelve-name="${stock.shelf ? stock.shelf.name : ''}">
                        <i class="bi bi-plus"></i> Stok Ekle/Çıkar
                    </button>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                        data-bs-target="#TransferModal" data-product-id="${product.id}"
                        data-stock-id="${stock.id}" data-product-name="${product.name}"
                        data-product-code="${product.product_code}"
                        data-stock-quantity="${stock.stock_quantity}"
                        data-warehouse-name="${stock.warehouse.name}"
                        data-shelve-name="${stock.shelf ? stock.shelf.name : ''}">
                        <i class="bi bi-arrow-down-up"></i> Transfer Başlat
                    </button>
                    
                    <a type="button" class="btn btn-sm ${localStorage.getItem('cart') && localStorage.getItem('cart').includes(`"product_id":${product.id}`) ? 'btn-danger' : 'btn-info'} text-white transfer-button" 
                       onclick="toggleCart(${product.id}, ${stock.id}, this)">
                        <i class="${localStorage.getItem('cart') && localStorage.getItem('cart').includes(`"product_id":${product.id}`) ? 'bi bi-dash-circle' : 'bi bi-plus-circle'}"></i> ${localStorage.getItem('cart') && localStorage.getItem('cart').includes(`"product_id":${product.id}`) ? 'Transfer Listesinden Çıkar' : 'Transfer Listesine Ekle'}
                    </a>
                    <form action="{{ route('stock.destroy') }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="stock_id" value="${stock.id}">
                        <button type="submit" class="btn btn-sm btn-danger" title="Sil" onclick="return confirm('Bu stok bilgisini silmek istediğinizden emin misiniz?')">Sil</button>
                    </form>
                </td>
            </tr>
        `).join('');

        return `
            <div class="card card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Depo Adı</th>
                            <th>Raf Adı</th>
                            <th>Stok Miktarı</th>
                            <th>Stok Ekle/Çıkar</th>
                            <th>Ürün Transfer</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${detailRows}
                    </tbody>
                </table>
            </div>`;
      }
    });
    // Seçilen kategorileri saklamak için bir dizi oluşturuyoruz
    let selectedCategories = [];

    function addToCart(productId, stockId) {
      // LocalStorage'dan mevcut sepeti al
      let cart = JSON.parse(localStorage.getItem('cart')) || [];

      // Ürün zaten sepette mi kontrol et
      const existingProductIndex = cart.findIndex(item => item.product_id === productId);

      if (existingProductIndex > -1) {
        // Ürün zaten sepette, miktarını artır
      } else {
        // Yeni ürün ekle
        cart.push({
          product_id: productId,
          stock_id: stockId,
        });
      }

      // Güncellenmiş sepeti LocalStorage'a kaydet
      localStorage.setItem('cart', JSON.stringify(cart));
      saveCartToCookie(); // Cookie'yi güncelle
      let cartCookie = document.cookie.match(/cart=([^;]*)/);
      if (cartCookie) {
        let cart = JSON.parse(cartCookie[1]);
        console.log(cart);
      }
      console.log('Sepetteki ürünler:', cart);
    }

    $(document).ready(function () {
      $('.transfer-form').on('submit', function (e) {
        e.preventDefault(); // Formun normal gönderimini engelle
        const productId = $(this).find('input[name="product_id"]').val();
        const stockId = $(this).find('input[name="stock_id"]').val();
        addToCart(productId, stockId); // Sepete ekle
      });
    });

    // Kategori seçildiğinde tetiklenen fonksiyon
    function toggleCategory(slug) {
      // Kategori zaten seçilmişse diziden çıkar, değilse diziye ekle
      if (selectedCategories.includes(slug)) {
        selectedCategories = selectedCategories.filter(category => category !== slug);
      } else {
        selectedCategories.push(slug);
      }

      // Seçilen kategorilere göre ürünleri filtrele
      filterProducts();
    }

    // AJAX ile kategorilere göre ürünleri filtreleme
    function filterProducts() {
      $.ajax({
        url: "{{ route('stock.filter') }}", // Bu route ile backend'e istek atılacak
        method: 'GET',
        data: {
          categories: selectedCategories // Seçilen kategoriler
        },
        success: function (response) {
          // Ürünlerin gösterildiği yeri güncelle
          $('#stock-list').html(response);
        },
        error: function (error) {
          console.error('Filtreleme hatası:', error);
        }
      });
    }

    // Tüm filtreyi sıfırlama
    function resetFilter() {
      selectedCategories = []; // Tüm seçilen kategorileri temizle
      $('input[type="checkbox"]').prop('checked', false); // Checkbox'ları sıfırla
      filterProducts(); // Ürün listesini güncelle
    }

    function toggleCart(productId, stockId, button) {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      const existingProductIndex = cart.findIndex(item => item.product_id === productId);

      if (existingProductIndex > -1) {
        // Ürün zaten sepette, miktarını azalt ve listeden çıkar
        $.ajax({
          url: '{{ route("transfer.remove") }}', // TransferListController'daki add metoduna yönlendirin
          method: 'POST',
          data: {
              product_id: productId,
              stock_id: stockId,
              _token: '{{ csrf_token() }}' // CSRF koruması için token ekleyin
          },
          success: function (response) {
              alert(response.message); // Başarılı yanıtı göster
          },
          error: function (xhr) {
              alert('Bir hata oluştu: ' + xhr.responseJSON.message); // Hata mesajını göster
          }
       });
        cart.splice(existingProductIndex, 1); // Ürünü listeden çıkar
        button.innerHTML = '<i class="bi bi-plus-circle"></i> Transfer Listesine Ekle'; // Buton metnini değiştir
        button.classList.remove('btn-danger'); // Kırmızı sınıfı kaldır
        button.classList.add('btn-info'); // Mavi sınıfı ekle
      } else {
        // Yeni ürün ekle
        cart.push({
          product_id: productId,
          stock_id: stockId,
          quantity: 0
        });
        $.ajax({
            url: '{{ route("transfer.add") }}', // TransferListController'daki add metoduna yönlendirin
            method: 'POST',
            data: {
                product_id: productId,
                stock_id: stockId,
                _token: '{{ csrf_token() }}' // CSRF koruması için token ekleyin
            },
            success: function (response) {
                alert(response.message); // Başarılı yanıtı göster
            },
            error: function (xhr) {
                alert('Bir hata oluştu: ' + xhr.responseJSON.message); // Hata mesajını göster
            }
        });
        button.innerHTML = '<i class="bi bi-dash-circle"></i> Transfer Listesinden Çıkar'; // Buton metnini değiştir
        button.classList.remove('btn-info'); // Mavi sınıfı kaldır
        button.classList.add('btn-danger'); // Kırmızı sınıfı ekle
      }

      // Güncellenmiş sepeti LocalStorage'a kaydet
      localStorage.setItem('cart', JSON.stringify(cart));
    }

    // LocalStorage'deki cart verisini cookie'ye yazma fonksiyonu
    function saveCartToCookie() {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const cartString = JSON.stringify(cart);

      // Cookie'ye yaz (path ve SameSite ayarları yapılmış şekilde)
      document.cookie = `cart=${encodeURIComponent(cartString)}; path=/; SameSite=Lax;`;
      console.log(document.cookie);
    }

  </script>
  </script>


</body>

</html>