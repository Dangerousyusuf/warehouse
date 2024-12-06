<!doctype html>
@include('includes.meta')
<title>teDepo | Kullanıcı Listesi</title>
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
        <div class="breadcrumb-title pe-3">Kullanıcı</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Kullanıcı Listesi</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->
      <div class="row g-3 mb-3">
        
        <div class="col-auto ms-auto">
          <div class="d-flex align-items-center gap-2 justify-content-lg-end">
            <a href="user-add" class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Kullanıcı Ekle</a>
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
                  <th>Kullanıcı Adı</th>
                  <th>Görev</th>
                  <th>E-Posta</th>
                  <th>Telefon</th>
                  <th>Görev Alanları</th>
                  <th>Eklenme Tarihi</th>
                  <th>İşlem</th>
                </tr>
              </thead>
              <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-box">
                                 <img src="assets/images/avatars/11.png" width="60" class="rounded-3" alt="">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:;" class="product-title">{{ $user->name }}</a>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>
                            @foreach($user->warehouses as $warehouse)
                                <span class="badge bg-black">{{ $warehouse->name }}</span>
                            @endforeach
                        </td>
                         <td>{{date('d M, H:i', strtotime($user->created_at))}}</td>
                        <td>
                            <div class="d-flex gap-2">
                              <a href="user-edit/{{$user->id}}" class="btn btn-primary">Düzenle</a>
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
  <!--end main wrapper-->

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