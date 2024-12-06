<!doctype html>
<html lang="tr">
<title>teDepo | Ürün Liste </title>

@include('includes.meta')

<body>

  <!--start header-->
  @include('includes.header')
  <!--end top header-->

  <!--start sidebar-->
  @include('includes.sidebar')

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
              <li class="breadcrumb-item active" aria-current="page">Ürün Listesi</li>
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

      <div class="row g-3">
        <div class="col-auto">
          <div class="position-relative">
            <input class="form-control px-5" type="search" placeholder="Ürün Ara" aria-label="Ürün Ara" autofocus
              id="searchInput">
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
                Marka
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="javascript:;">Marka 1</a></li>
                <li><a class="dropdown-item" href="javascript:;">Marka 2</a></li>
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
            <a href="{{ route('product.product_add') }}" class="btn btn-primary px-4"><i
                class="bi bi-plus-lg me-2"></i>Ürün Ekle</a>
          </div>
        </div>
      </div><!--end row-->
      <div class="card mt-4">
        <div id="product-list" class="table-responsive">
          @include('product.partials.product_list', ['products' => $products]) <!-- İlk yüklemede tüm ürünler -->
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

    $(document).ready(function () {
      $('#example').DataTable();
      var table = $('#example2').DataTable({
        lengthChange: false,
        pageLength: 100,
        buttons: ['copy', 'excel', 'pdf', 'print'],
        "search": {
          "smart": true // Akıllı arama özelliği
        }
      });

      table.buttons().container()
        .appendTo('#example2_wrapper .col-md-6:eq(0)');
      // Arama işlevselli
      $('#searchInput').on('keyup', function () {
        table.search(this.value).draw(); // DataTable arama fonksiyonu
      });
    });
    // Seçilen kategorileri saklamak için bir dizi oluşturuyoruz
    let selectedCategories = [];

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
        url: "{{ route('products.filter') }}", // Bu route ile backend'e istek atılacak
        method: 'GET',
        data: {
          categories: selectedCategories // Seçilen kategoriler
        },
        success: function (response) {
          // Ürünlerin gösterildiği yeri güncelle
          $('#product-list').html(response);
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
  </script>


</body>

</html>