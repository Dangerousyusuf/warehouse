<!doctype html>
@include('includes.meta')
<title>teDepo | Varyant Düzenle</title>
<body>

    <!--start header-->
    @include('includes.header')
    <!--end top header-->

    <!--start sidebar-->
    @include('includes.sidebar')
    <!--end sidebar-->
    
    @if($errors->has('variants'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                errorToast("{{ $errors->first('variants') }}");
            });
        </script>
    @endif

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Varyantlar</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Varyant Düzenle</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <form id="variantForm" action="{{ route('variations.update', $variation->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- PUT method for update -->
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
                                    placeholder="Varyant Adı" value="{{ old('name', $variation->name) }}">

                                <hr>
                                <div class="items row mt-3" data-group="variants">
                                    <!-- Tekrarlanan öğeler buraya eklenecek -->
                                    @foreach($variation->options as $index => $option)
                                        <div class="mt-3 variant-item d-flex align-items-center" data-id="{{ $option->id }}">
                                            <label class="form-label">{{ $index + 1 }}. Varyant Değeri</label>
                                            <input type="text" class="form-control variant-name" name="variants[{{ $index }}][value]" placeholder="Varyant Değeri" value="{{ old('variants.' . $index . '.value', $option->value) }}">
                                            <button type="button" class="btn btn-danger remove-variant ms-2">Sil</button>
                                        </div>
                                    @endforeach
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
            var variantIndex = {{ count($variation->options) }}; // Mevcut varyant sayısını al
            var deletedVariants = []; // Silinen varyant ID'lerini tutacak dizi

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

            $('.repeater-add-btn').click(function () {
                addVariant();
            });

            // Varyant silme işlemi
            $(document).on('click', '.remove-variant', function () {
                var variantItem = $(this).closest('.variant-item');
                var variantId = variantItem.data('id'); // Varyant ID'sini data-attribute'dan al

                if (variantId) {
                    // Eğer ID varsa, bu varyantı silmek için diziye ekleyin
                    deletedVariants.push(variantId);
                }

                variantItem.remove(); // Varyantı DOM'dan kaldır
                updateVariantIndexes(); // Varyantları silindikten sonra indeksleri güncelle
            });

            function updateVariantIndexes() {
                variantIndex = 0; // İndeksi sıfırla
                $('.variant-item').each(function (index) {
                    variantIndex++; // Her bir varyant için indeksi artır
                    $(this).find('label').text(variantIndex + '. Varyant Değeri'); // Label'ı güncelle
                });
            }

            // Form gönderildiğinde silinen varyantları ekleyin
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

                // Silinen varyantları gizli bir input olarak ekleyin
                if (deletedVariants.length > 0) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'deleted_variants[]',
                        value: deletedVariants.join(',')
                    }).appendTo('#variantForm');
                }
            });
        });
    </script>

</body>
</html>
