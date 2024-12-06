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

      <div class="product-count d-flex align-items-center gap-3 gap-lg-4 mb-4 fw-medium font-text1">
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
            <form action="{{ route('transfer.bulkStoreIncoming') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Toplu Onay</button>
          </div>
        </div>
      </div><!--end row-->

      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="incomingTable" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th><input type="checkbox" id="selectAll"></th>
                  <th>Ürün Adı</th>
                  <th>Ürün Kodu</th>
                  <th>Transfer Miktarı</th>
                  <th>Tür</th>
                  <th>Gelen Depo</th>
                  <th>Hedef Raf</th>
                  <th>Tarih</th>
                  <th>Düzenle</th>
                </tr>
              </thead>
              <tbody>
                @forelse($products as $product)
          <tr>
            <td>
            <input type="checkbox" name="selected[]" value="{{ $product['transferList_id'] }}">
            </td>
            <td>{{ $product['name'] }}</td>
            <td>{{ $product['product_code'] }}</td>
            <td>{{ $product['quantity'] }}</td>
            <td>{{ $product['type'] }}</td>
            <td>{{ $product['warehouse'] }}</td>
            <td>
            <select name="shelves[{{ $product['transferList_id'] }}]" class="form-select">
              <option value="" selected disabled>Seçiniz...</option>
              @foreach ($shelves as $shelf)
          <option value="{{ $shelf->id }}">{{ $shelf->name }}</option>
        @endforeach
            </select>
            </td>
            <td>{{ $product['created_at'] }}</td>
            <td>
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
              data-bs-target="#cancelTransferModal" data-transfer-id="{{ $product['transferList_id'] }}">
              İptal
            </button>
            </td>
          </tr>
        @empty
      <tr>
        <td colspan="8" class="text-center">Gelen transfer bulunamadı.</td>
      </tr>
    @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      </form>

    </div>

    <div class="modal fade" id="cancelTransferModal" tabindex="-1" aria-labelledby="cancelTransferModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="cancelTransferModalLabel">Ürün İptali</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="cancelTransferForm" action="{{ route('transfer.transferCancel') }}" method="POST">
              @csrf
              <input type="hidden" name="transfer_id" id="cancelTransferId">
              <div class="mb-3">
                <label for="cancelReason" class="form-label">İptal Notu</label>
                <textarea class="form-control" name="note" id="cancelReason" rows="3"
                  placeholder="İptal gerekçesini yazınız..." required></textarea>
              </div>
              <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">İptal</button>
                <button type="submit" class="btn btn-danger">Onayla</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const currentStock = 1000; // Bu değer dinamik olarak ayarlanmalıdır
        const stockInput = document.getElementById('inputStockAmount');
        const decreaseBtn = document.getElementById('decreaseStock');
        const increaseBtn = document.getElementById('increaseStock');

        function updateStockInput(value) {
          stockInput.value = Math.max(0, Math.min(value, currentStock));
        }

        decreaseBtn.addEventListener('click', () => updateStockInput(parseInt(stockInput.value || 0) - 1));
        increaseBtn.addEventListener('click', () => updateStockInput(parseInt(stockInput.value || 0) + 1));

        stockInput.addEventListener('input', () => updateStockInput(parseInt(stockInput.value || 0)));
      });
    </script>
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
  <script src="{{url('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
  <script>
    $(document).ready(function () {
      $('#example').DataTable();
    });
  </script>
  <script>
    $(document).ready(function () {
      var table = $('#example2').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'print']
      });

      table.buttons().container()
        .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });

    document.addEventListener('DOMContentLoaded', function () {
      const selectAllCheckbox = document.getElementById('selectAll');
      const checkboxes = document.querySelectorAll('input[name="selected[]"]');

      selectAllCheckbox.addEventListener('change', function () {
        checkboxes.forEach(checkbox => {
          checkbox.checked = selectAllCheckbox.checked;
        });
      });

      const cancelModal = document.getElementById('cancelTransferModal');
      const cancelTransferIdInput = document.getElementById('cancelTransferId');

      cancelModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Modal'ı açan buton
        const transferId = button.getAttribute('data-transfer-id'); // Butondan transfer ID'sini al
        cancelTransferIdInput.value = transferId; // Transfer ID'yi formdaki gizli inputa ata
      });

    });
  </script>
  <script src="{{url('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
  <script src="{{url('assets/js/main.js')}}"></script>


</body>

</html>