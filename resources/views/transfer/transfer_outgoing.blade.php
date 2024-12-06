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
            <a href="product-add" class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Ürün Ekle</a>
          </div>
        </div>
      </div><!--end row-->
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
                  <th>Hedef Depo</th>
                  <th>Hedef Raf</th>
                  <th>Transfer İşlemi</th>
                  <th>Durum</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="product-box">
                        <img src="assets/images/orders/01.png" width="70" class="rounded-3" alt="">
                      </div>
                      <div class="product-info">
                        <a href="javascript:;" class="product-title">N95 Maske</a>
                        <p class="mb-0 product-category">Kategori: Koruyucu Ekipman</p>
                      </div>
                    </div>
                  </td>
                  <td>ABC57TY</td>
                  <td>1000</td>
                  <td>Adet</td>
                  <td>Depo 1</td>
                  <td>A-1</td>
                  <td>
                    <div class="d-flex gap-2">
                      <button type="button" class="btn ripple btn-danger" data-bs-toggle="modal"
                        data-bs-target="#CancelTransferModal">İptal Et</button>
                      <button type="button" class="btn ripple btn-warning text-white" data-bs-toggle="modal"
                        data-bs-target="#SuspendTransferModal">Askıya Al</button>
                      <button type="button" class="btn ripple btn-primary" data-bs-toggle="modal"
                        data-bs-target="#TransferModal">Düzenle</button>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <span class="badge bg-success">Aktif</span>
                    </div>
                  </td>
                </tr>
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
                    <option value="Depo-1">Depo-1</option>
                    <option value="Depo-2">Depo-2</option>
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

    <!-- İptal Et Modal -->
    <div class="modal fade" id="CancelTransferModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Transferi İptal Et</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Transferi iptal etmek istediğinizden emin misiniz?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hayır</button>
            <button type="button" class="btn btn-primary">Evet, İptal Et</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Askıya Al Modal -->
    <div class="modal fade" id="SuspendTransferModal">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Transferi Askıya Al</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Transferi askıya almak istediğinizden emin misiniz?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hayır</button>
            <button type="button" class="btn btn-success">Evet, Askıya Al</button>
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
  </script>
  <script src="{{url('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
  <script src="{{url('assets/js/main.js')}}"></script>


</body>

</html>