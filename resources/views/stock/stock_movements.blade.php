<!doctype html>
@include('includes.meta')
<title>teDepo | Stok Hareketleri</title>

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
              <li class="breadcrumb-item active" aria-current="page">Stok Hareketleri</li>
            </ol>
          </nav>
        </div>
      </div>

      <!--end breadcrumb-->
      <div class="row g-3 mb-3">
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
                Kullanıcı
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;">Yusuf Nafiz GÜNSEL</a></li>
                <li><a class="dropdown-item" href="javascript:;">Doğukan BAHADIR</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="javascript:;">Tümü</a></li>
              </ul>
            </div>
            <div class="btn-group position-static">
              <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown"
                aria-expanded="false">
                İşlem Tipi
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;">Stok Ekleme</a></li>
                <li><a class="dropdown-item" href="javascript:;">Stok Düşme</a></li>
                <li><a class="dropdown-item" href="javascript:;">Transfer</a></li>
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
            <label class="form-label">Tarih Aralığı</label>
            <input type="text" class="form-control date-range" value="14.09.2024">
          </div>
        </div>
      </div><!--end row-->


      <!--end row-->
      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>İşlem No</th>
                  <th>İşlem Yapan</th>
                  <th>İşlem Yapılan Ürün</th>
                  <th>İşlem Detay</th>
                  <th>İşlem Tipi</th>
                  <th>Depo</th>
                  <th>Raf</th>

                  <th>Tarih</th>
                  <th>Durum</th>
                </tr>
              </thead>
              <tbody>
                @php
          $i = 1;
          // dd($stockMovements);
        @endphp
                @forelse($stockMovements as $movement)

          <tr>
            <td>
            <a href="javascript:;">#{{$i++}}</a>
            </td>
            <td>
            <a class="d-flex align-items-center gap-3" href="javascript:;">
              <div class="customer-pic">
              <img src="{{ asset('assets/images/avatars/11.png') }}" class="rounded-circle" width="40"
                height="40" alt="">
              </div>

              <p class="mb-0 customer-name fw-bold">{{ $movement->user ? $movement->user->name : 'Bilinmiyor' }}
              </p>
            </a>
            </td>
            <td>
            <div class="d-flex align-items-center gap-3">
              <div class="product-box">
              <img src="{{ asset('storage/' . $movement->firstImage) }}" class="rounded-3" width="50" alt="">
              </div>
              <div class="product-info">
              <a href="javascript:;"
                class="product-title">{{ $movement->product ? $movement->product->name : '' }}</a>
              <p class="mb-0 product-category">Kategori:
                {{ $movement->product ? $movement->product->category->name : '' }}
              </p>
              </div>
            </div>
            </td>
            <td>
            @if($movement->type == 'in')
        <span
          class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">
          {{ $movement->quantity }} Adet Stok Eklendi
        </span>
      @elseif($movement->type == 'out')
    <span
      class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">
      {{ $movement->quantity }} Adet Stok Düşüldü
    </span>
  @elseif($movement->type == 'transfer')
  <span
    class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">
    {{ $movement->quantity }} Adet Stok Transfer Edildi
  </span>
@endif
            </td>
            <td>
            @if($movement->type == 'in')
        <span
          class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">
          Stok Ekleme<i class="bi bi-arrow-up ms-1"></i>
        </span>
      @elseif($movement->type == 'out')
    <span
      class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Stok
      Düşme<i class="bi bi-arrow-down ms-1"></i></span>
  @elseif($movement->type == 'transfer')
  <span
    class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Transfer<i
    class="bi bi-arrow-clockwise ms-1"></i></span>
@endif

            </td>
            <td>

            @if($movement->type == 'in')
        <span
          class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">{{ $movement->fromWarehouse ? $movement->fromWarehouse->name : '' }}</span>
      @elseif($movement->type == 'out')
    <span
      class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">{{ $movement->fromWarehouse ? $movement->fromWarehouse->name : '' }}</span><i
      class="bi bi-arrow-right ms-2"></i>
    @if(!empty($movement->stock_out))
    <span
      class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">{{$movement->stock_out}}</span>
  @endif
  @elseif($movement->type == 'transfer')
  <span
    class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">{{$movement->fromWarehouse ? $movement->fromWarehouse->name : ''}}</span><i
    class="bi bi-arrow-right ms-2"></i>
  <span
    class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">{{$movement->toWarehouse ? $movement->toWarehouse->name : ''}}</span>
@endif
            </td>
            <td>
            @if(!empty($movement->to_shelves_id))
        <span
          class="lable-table bg-dark text-white rounded border border-dark font-text2 fw-bold">{{$movement->fromShelves ? $movement->fromShelves->name : ''}}</span><i
          class="bi bi-arrow-right ms-2"></i>
        <span
          class="lable-table bg-white text-dark rounded border border-white font-text2 fw-bold">{{$movement->toShelves ? $movement->toShelves->name : ''}}</span>
      @else
    <span
      class="lable-table bg-dark text-white rounded border border-dark font-text2 fw-bold">{{$movement->fromShelves ? $movement->fromShelves->name : ''}}</span>
  @endif


            </td>

            <td>{{ \Carbon\Carbon::parse($movement->created_at)->format('d-m-Y H:i:s') }}</td>
            <td>
              <div class="d-flex justify-content-between">
                <span
                  class="lable-table {{ $movement->transfer_status ? 'bg-success-subtle text-success' : 'bg-info-subtle text-info' }} rounded border font-text2 fw-bold fs-4">
                  {!! $movement->transfer_status ? '<i class="bi bi-check-circle fs-4"></i>' : '<i class="bi bi-hourglass-bottom fs-4"></i>' !!}
                </span>
                @if($movement->note)
                  <button type="button" class="btn btn-primary btn-sm" title="Notu Gör" data-bs-toggle="modal"
                    data-bs-target="#noteModal" data-note="{{ $movement->note }}">Not</button>
                @endif
              </div>
            </td>

          </tr>

        @empty
      <tr>
        <td colspan="16" class="text-center">Ürün bulunamadı.</td>
      </tr>
    @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!--end main wrapper-->

  <!-- Modal -->
  <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="noteModalLabel">İşlem Notu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="noteContent">Not yükleniyor...</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
        </div>
      </div>
    </div>
  </div>

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
      $('#example').DataTable();
    });
  </script>
  <script>
    $(document).ready(function () {
      var table = $('#example2').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'print'],
        "autoWidth": true  // Otomatik genişlik ayarını kapatır
      });

      table.buttons().container()
        .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });

    document.addEventListener('DOMContentLoaded', function () {
      const noteModal = document.getElementById('noteModal');
      const noteContent = document.getElementById('noteContent');

      noteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Modal'ı açan buton
        const note = button.getAttribute('data-note'); // Butondan notu al
        noteContent.textContent = note; // Notu modal içine yaz
      });
    });
  </script>
  <script>
    $(".datepicker").flatpickr();
    $(".date-range").flatpickr({
      mode: "range",
      altInput: true,
      altFormat: "F j, Y",
      dateFormat: "Y-m-d",
    });
  </script>



</body>

</html>