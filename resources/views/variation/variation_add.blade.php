<!doctype html>
@include('includes.meta')
<title>teDepo | Varyant Ekle </title>
<body>

    <!--start header-->
    @include('includes.header')
    <!--end top header-->

    <!--start sidebar-->
    @include('includes.sidebar')
    <!--end sidebar-->


    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <!--end breadcrumb-->
            <form id="variantForm" action="{{ route('variations.store') }}" method="POST">
                @csrf
                <div class="items">
                    <div class="card">
                        <div class="card-body">
                            <!-- Repeater Content -->
                            <div class="item-content">
                                
                                <div class="mb-3">
                                    <label for="inputName1" class="form-label fw-bold" style="font-size: 1.2rem;">Varyant Adı</label>
                                    <button type="button" class="btn btn-primary repeater-add-btn px-4 ms-auto float-end">
                                    <i class="bi bi-plus-lg me-2"></i> Varyant Değeri Ekle
                                    </button>
                                </div>
                                <input type="text" class="form-control mt-2" id="inputName1" name="name"
                                    placeholder="Varyant Adı" value="{{ old('name') }}">

                                <hr>
                                <div class="items row mt-3" data-group="variants">
                                    <!-- Tekrarlanan öğeler buraya eklenecek -->
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 col-md-3 ms-auto">
                                <button type="submit" class="btn btn-success flex-fill mt-3">
                                    <i class="bi bi-cloud-download me-2"></i>Kaydet
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </main>
    <div class="overlay btn-toggle"></div>
    @include('includes.footer')
    @include('includes.switcher')
    <script>
        $(document).ready(function () {
            var variantIndex = 0;

            function addVariant() {
                variantIndex++; // Her yeni varyant eklendiğinde indexi artır
                var newVariant = `
                    <div class="mt-3 variant-item d-flex align-items-center">
                        <label class="form-label">${variantIndex}. Varyant Değeri</label>
                        <input type="text" class="form-control variant-name" name="variants[${variantIndex}][value]" placeholder="Varyant Değeri">
                        <button type="button" class="btn btn-danger remove-variant ms-2">Sil</button>
                    </div>
                `;
                $('.items[data-group="variants"]').append(newVariant);
            }

            addVariant(); // İlk varyantı ekle

            $('.repeater-add-btn').click(function () {
                addVariant();
            });

            // Varyant silme işlemi
            $(document).on('click', '.remove-variant', function () {
                $(this).closest('.variant-item').remove();
                updateVariantIndexes(); // Varyantları silindikten sonra indeksleri güncelle
            });

            function updateVariantIndexes() {
                variantIndex = 0; // İndeksi sıfırla
                $('.variant-item').each(function (index) {
                    variantIndex++; // Her bir varyant için indeksi artır
                    $(this).find('label').text(variantIndex + '. Varyant Değeri'); // Label'ı güncelle
                    $(this).find('input').attr('name', `variants[${index}][value]`); // Input name'ini güncelle
                });
            }

            // Form gönderildiğinde aynı varyant değerlerini kontrol et
            $('#variantForm').on('submit', function (e) {
                var variantValues = []; // Mevcut varyant değerlerini tutacak dizi
                var hasDuplicate = false; // Aynı varyant değeri kontrolü için bayrak

                // Tüm varyant değerlerini kontrol et
                $('input[name^="variants"]').each(function () {
                    var value = $(this).val().trim();
                    if (value) {
                        if (variantValues.includes(value)) {
                            hasDuplicate = true; // Aynı değer bulundu
                        } else {
                            variantValues.push(value); // Değeri diziye ekle
                        }
                    }
                });

                if (hasDuplicate) {
                    e.preventDefault(); // Formun gönderilmesini engelle
                    errorToast('Aynı varyant değeri eklenemez!'); // Hata mesajı göster
                }
            });
        });
    </script>

</body>
</html>
