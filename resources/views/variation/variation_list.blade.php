<!doctype html>
@include('includes.meta')
<title>teDepo | Varyant Listesi</title>
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
                <div class="breadcrumb-title pe-3">Varyantlar</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Varyant Listesi</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row g-3">
                <div class="col-auto">
                    <div class="position-relative">
                        <input class="form-control px-5" type="search" placeholder="Varyant Ara" aria-label="Varyant Ara" autofocus id="searchInput">
                        <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>

                <div class="col-auto ms-auto">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Dışa Aktar</button>
                        <a href="variation-add" class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Varyant Ekle</a>
                    </div>
                </div>
            </div><!--end row-->

            <div class="card mt-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Varyant Adı</th>
                                    <th>Varyant Değerleri</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>Düzenle</th>
                                    <th>Sil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($variations as $variation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="product-info">
                                                <a href="javascript:;" class="product-title">{{ $variation->name }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($variation->options->isNotEmpty())
                                            {{ $variation->options->pluck('value')->implode(', ') }}
                                        @else
                                            Değer yok
                                        @endif
                                    </td>
                                    <td>{{ $variation->created_at }}</td>
                                    <td>
                                    <a href="{{ route('variations.edit', $variation->id) }}" class="btn btn-warning">Düzenle</a>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger" title="Sil" onclick="deleteVariation({{ $variation->id }})">Sil</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">Varyant bulunamadı.</td>
                                </tr>
                                @endforelse
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

    <!-- Silme Onay Modalı -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Varyant Sil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bu varyantı silmek istediğinize emin misiniz?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <form id="deleteForm" action="" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Sil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let variationIdToDelete = null; // Silinecek varyantın ID'si

        $(document).ready(function () {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print'],
                "order": [[1, 'asc']]
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');

            // Sil butonuna tıklandığında modalı aç
            window.deleteVariation = function(variationId) {
                variationIdToDelete = variationId; // Silinecek varyantın ID'sini sakla
                $('#deleteForm').attr('action', '/variation/' + variationIdToDelete); // Formun action'ını güncelle
                $('#deleteModal').modal('show'); // Modalı göster
            };

            $('#searchInput').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('#example2 tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

    <style>
        td.details-control {
            cursor: pointer;
            text-align: center;
        }
    </style>

</body>
</html>
