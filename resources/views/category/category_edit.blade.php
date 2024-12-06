<!doctype html>
@include('includes.meta')
<title>teDepo | Kategori Düzenle </title>

<body>

    <!--start header-->
    @include('includes.header')
    <!--end top header-->

    <!--start sidebar-->
    @include('includes.sidebar')
    <!--end sidebar-->


    <!--start main wrapper-->
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Kategori</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kategori Düzenle</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <!-- Kategori düzenleme formu -->
            <form id="categoryForm" method="POST" action="{{ route('categories.update', $category->id) }}">
                @csrf
                @method('PUT') <!-- PUT metodunu belirtmek için -->
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <h5 class="mb-3">Kategori Adı</h5>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $category->name }}" required>
                                </div>
                                <div class="mb-4">
                                    <h5 class="mb-3">Açıklama</h5>
                                    <textarea class="form-control" id="description"
                                        name="description">{{ $category->description }}</textarea>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <a href="{{ route('categories.index') }}" class="btn btn-outline-danger flex-fill">
                                        <i class="bi bi-x-circle me-2"></i>İptal
                                    </a>
                                    <button type="submit" class="btn btn-outline-primary flex-fill">
                                        <i class="bi bi-cloud-download me-2"></i>Güncelle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div><!--end row-->
    </main>
    <!--end main wrapper-->


    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

    <!--start footer-->
    @include('includes.footer')
    <!--top footer-->


    <!--start switcher-->
    @include('includes.switcher')
    <!--start switcher-->

</body>

</html>