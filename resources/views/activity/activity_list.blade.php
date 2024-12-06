<!doctype html>

<!--start meta-->
@include('includes.meta')
<!--end top meta-->
<title>teDepo | Aktivite Listesi</title>
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
        <div class="breadcrumb-title pe-3">Aktivite</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Aktivite Listesi</li>
            </ol>
          </nav>
        </div>
      </div>
      <!--end breadcrumb-->

      <div class="card">
        <div class="card-body">
          <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Log Id</th>
                  <th>İşlem Yapan</th>
                  <th>İşlem Yapılan Model</th>
                  <th>İşlem Detay</th>
                  <th>Açıklama</th> <!-- Açıklama sütununu ekledik -->
                  <th>Tarih</th>
                </tr>
              </thead>
              <tbody>
              @foreach($activityLogs as $log)  <!-- Tarihe göre azalan sıralama --> <!-- Tarihe göre artan sıralama -->
                    <tr>
                      <td>#{{ $loop->iteration }}</td>
                       <td>
                        <a href="javascript:;">#{{ $log->id }}</a>
                       </td>
                       <td>
                        <a class="d-flex align-items-center gap-3" href="javascript:;">
                          <div class="customer-pic">
                            <img src="assets/images/avatars/11.png" class="rounded-circle" width="40" height="40" alt="">
                          </div>
                          <p class="mb-0 customer-name fw-bold">{{ $log->user->name ?? 'Bilinmiyor' }}</p> <!-- İşlem yapan kullanıcı adı -->
                        </a>
                       </td>
                       <td>{{ $log->model === 'FactorySettings' ? 'Fabrika Ayarları' : $log->model }} </td> <!-- İşlem yapılan model -->
                       <td>
                        <span class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">
                            {{ $log->action === 'create' ? 'Ekleme' : ($log->action === 'update' ? 'Güncelleme' : $log->action) }}
                        </span>
                       </td> <!-- İşlem detay -->
                       <td>{{ $log->description ?? 'Açıklama yok' }}</td> <!-- Açıklama alanını ekledik -->
                       <td>{{ $log->created_at->translatedFormat('d F, H:i') }}</td>
                     </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </main>


  <div class="overlay btn-toggle"></div>

  @include('includes.footer')
  @include('includes.switcher')
  <script>
    $(document).ready(function () {
        var table = $('#example2').DataTable({
        
            buttons: ['copy', 'excel', 'pdf', 'print'],
          
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
  </script>
</body>
</html>
