<!doctype html>
@include('includes.meta')
<title>teDepo | Depo Ekle</title>

<body>
    @include('includes.header')
    @include('includes.sidebar')

    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Depo</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('warehouse.warehouse_list') }}"><i
                                        class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Depo Ekle</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <form action="{{ route('warehouse.warehouse_store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="inputName1" class="form-label">Depo Adı</label>
                            <input type="text" class="form-control" id="inputName1" name="name" placeholder="Depo Adı"
                                required>
                        </div>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <button type="reset" class="btn btn-outline-danger flex-fill"><i
                                    class="bi bi-x-circle me-2"></i>İptal</button>
                            <button type="submit" class="btn btn-success flex-fill"><i
                                    class="bi bi-cloud-download me-2"></i>Kaydet</button>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Raflar</h5>
                            <button type="button" class="btn btn-primary repeater-add-btn px-4"
                                onclick="addShelf()">Ekle</button>
                        </div>
                    </div>

                </div>
                <div class="items row" id="shelves" data-group="shelves">
                    <!-- Tekrarlanan öğeler buraya eklenecek -->
                </div>
        </div>
        <!-- Add Shelf Button -->






        </form>
        </div>
    </main>

    @include('includes.footer')
    @include('includes.switcher')

    <script>
        let shelfIndex = 0;

        function addShelf() {
            const shelfHtml = `
              
                 <div class="col-md-4 mb-3 shelf-item">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="item-content">
                                    <div class="mb-3">
                                        <label class="form-label">Raf Adı</label>
                                        <input type="text" class="form-control shelf-name" name="shelves[${shelfIndex}][name]" placeholder="Raf Adı">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stok Limiti</label>
                                        <input type="number" class="form-control shelf-stock-limit" name="shelves[${shelfIndex}][stock_limit]" placeholder="Stok Limiti">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger remove-btn px-4" onclick="removeShelf(this)" >Sil</button>
                            </div>
                        </div>
                    </div>
            `;
            document.getElementById('shelves').insertAdjacentHTML('beforeend', shelfHtml);
            shelfIndex++;
        }

        function removeShelf(button) {
            button.closest('.shelf-item').remove();
        }
    </script>
</body>

</html>