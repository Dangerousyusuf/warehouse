<!doctype html>
@include('includes.meta')
<title>teDepo | Depo Düzenle </title>

<body>

    <!--start header-->
    @include('includes.header')
    <!--end top header-->

    <!--start sidebar-->
    @include('includes.sidebar')
    <!--end sidebar-->


    @php
        $warehouseData = [];

        $warehouseData = [
            'id' => $warehouse->id,
            'name' => $warehouse->name,
            'created_at' => date('d M, H:i', strtotime($warehouse->created_at)),
            'shelves' => []
        ];

        foreach ($shelves as $shelf) {
            $warehouseData['shelves'][] = [
                'id' => $shelf->id,
                'name' => $shelf->name,
                'stock_limit' => $shelf->stock_limit
            ];
        }
    @endphp
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Depo</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Depo Düzenle</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->
            <form action="{{ route('warehouse.update', $warehouse->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="items">
                    <div class="card">
                        <div class="card-body">
                            <!-- Repeater Content -->
                            <div class="item-content">
                                <div class="mb-3">
                                    <label for="inputName1" class="form-label">Depo Adı</label>
                                    <input type="hidden" name="id" value="{{$warehouse->id}}">
                                    <input type="text" class="form-control" id="inputName1" name="name"
                                        placeholder="Depo Adı" value="{{$warehouse->name}}">
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <button type="reset" class="btn btn-outline-danger flex-fill"><i
                                            class="bi bi-x-circle me-2"></i>İptal</button>
                                    <button type="submit" class="btn btn-success flex-fill ">
                                        <i class="bi bi-cloud-download me-2"></i>Güncelle
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Repeater Html Start -->
                <div id="repeater">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Raflar</h5>
                                <button type="button" class="btn btn-primary repeater-add-btn px-4"
                                    onclick="addShelf()">Ekle</button>
                            </div>
                        </div>
                    </div>
                    <div id="shelves" class="row">
                        <!-- Dinamik olarak eklenen raflar burada görüntülenecek -->
                    </div>
                    <!-- Repeater Items -->
                    <div class="items row" data-group="shelves">
                        @foreach($shelves as $index => $shelf)
                            <div class="col-md-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <input type="hidden" name="shelves[{{ $index }}][id]" value="{{ $shelf->id }}">
                                        <div class="mb-3">
                                            <label class="form-label">Raf Adı</label>
                                            <input type="text" class="form-control" name="shelves[{{ $index }}][name]"
                                                placeholder="Raf Adı" value="{{ $shelf->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Stok Limiti</label>
                                            <input type="number" class="form-control"
                                                name="shelves[{{ $index }}][stock_limit]" placeholder="Stok Limiti"
                                                value="{{ $shelf->stock_limit }}">
                                        </div>
                                        <button type="button" class="btn btn-danger"
                                            onclick="openDeleteModal({{ $shelf->id }})">Sil</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Repeater End -->
            </form>
            <!-- Silme Modalı -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Rafı Sil</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                        </div>
                        <div class="modal-body">
                            Bu rafı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
                        </div>
                        <div class="modal-footer">
                            <form id="deleteShelfForm" method="POST" data-route="{{ route('shelf.delete', ':id') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="shelf_id" id="shelf_id" value="">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-danger">Sil</button>
                            </form>
                        </div>
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
    @include('includes.footer');
    <!--top footer-->

    <!--start switcher-->
    @include('includes.switcher')
    <!--end switcher-->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Mevcut raf sayısına göre shelfIndex başlatılır
            let shelfIndex = {{ count($shelves) }};

            // Yeni raf ekleme fonksiyonu
            function addShelf() {
                const shelvesContainer = document.getElementById("shelves");

                // shelvesContainer öğesini kontrol edin
                if (!shelvesContainer) {
                    console.error("Shelves container not found.");
                    return;
                }

                const newShelf = `
            <div class="col-md-4 mb-3 shelf-item">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Raf Adı</label>
                            <input type="text" class="form-control" name="shelves[${shelfIndex}][name]" placeholder="Raf Adı" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok Limiti</label>
                            <input type="number" class="form-control" name="shelves[${shelfIndex}][stock_limit]" placeholder="Stok Limiti" required>
                        </div>
                        <button type="button" class="btn btn-danger remove-btn" onclick="removeShelf(this)">Sil</button>
                    </div>
                </div>
            </div>`;

                shelvesContainer.insertAdjacentHTML('beforeend', newShelf);
                shelfIndex++;  // Her yeni raf eklendiğinde index'i artırın
            }

            // Raf silme fonksiyonu
            function removeShelf(button) {
                const shelfCard = button.closest(".col-md-4");
                if (shelfCard) {
                    shelfCard.remove();
                }
            }

            // addShelf ve removeShelf fonksiyonlarını global hale getirin
            window.addShelf = addShelf;
            window.removeShelf = removeShelf;
        });
        function openDeleteModal(shelfId) {
            // Modal içindeki formu seçin
            const deleteForm = document.getElementById('deleteShelfForm');
            const routeTemplate = deleteForm.getAttribute('data-route');

            // :id kısmını gerçek shelfId ile değiştir
            deleteForm.action = routeTemplate.replace(':id', shelfId);
            document.getElementById('shelf_id').value = shelfId;

            // Modal'ı aç
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {});
            deleteModal.show();
        }


    </script>


</body>

</html>