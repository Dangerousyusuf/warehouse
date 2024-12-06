<!doctype html>

<!--start meta-->
@include('includes.meta')
<!--end top meta-->

<title>teDepo | Kategori Liste </title>

<body>

  <!--start header-->
  @include('includes.header')
  <!--end top header-->

  <!--start sidebar-->
  @include('includes.sidebar')
  <!--end sidebar-->


  <main class="main-wrapper">
    <div class="main-content">
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Kategori</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Kategori Listesi</li>
            </ol>
          </nav>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Kategori Listesi</h5>
          <div class="table-responsive">
            <table class="table table-striped table-bordered" id="example2">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Kategori Adı</th>
                  <th>Açıklama</th>
                  <th>İşlemler</th>
                </tr>
              </thead>
              <tbody>
                @foreach($categories as $category)
              <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $category->name }}</td>
              <td>{{ $category->description }}</td>
              <td>
                <div class="dropdown">
                  <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton{{ $category->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                    İşlemler
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $category->id }}">
                    <li><a class="dropdown-item" href="{{ route('categories.edit', $category->id) }}">Düzenle</a></li>
                    <li>
                      <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">Sil</button>
                    </li>
                  </ul>
                </div>
                <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header border-bottom-0 py-2">
                        <h5 class="modal-title">Kategori Sil</h5>
                        <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="modal">
                          <i class="material-icons-outlined">close</i>
                        </a>
                      </div>
                      <div class="modal-body">Bu kategoriyi silmek istediğinize emin misiniz?</div>
                      <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">İptal</button>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-outline-danger">Sil</button>
                        </form>
                      </div>
                    </div>
                  </div>
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
  </main>


  <!--start overlay-->
  <div class="overlay btn-toggle"></div>
  <!--end overlay-->


  <!--top footer-->
  @include('includes.footer')
  <!--start switcher-->
  @include('includes.switcher')
  <!--end switcher-->

  <script>
    /* Create Repeater */
    $("#repeater").createRepeater({
      showFirstItemToDefault: true,
    });

    $(document).ready(function () {
      $('#example').DataTable();
      var table = $('#example2').DataTable({
        lengthChange: false,
        buttons: ['copy', 'excel', 'pdf', 'print']
      });

      table.buttons().container()
        .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });


  </script>



</body>

</html>

</html>