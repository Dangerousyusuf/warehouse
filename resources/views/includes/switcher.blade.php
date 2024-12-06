<!--start switcher-->
<button class="btn btn-grd btn-grd-primary position-fixed bottom-0 end-0 m-3 d-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop">
    <i class="material-icons-outlined">tune</i>Tema
</button>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="staticBackdrop">
    <div class="offcanvas-header border-bottom h-70">
        <div class="">
            <h5 class="mb-0">Tema Düzenleyici</h5>
            <p class="mb-0">Temanızı Özelleştirin</p>
        </div>
        <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
            <i class="material-icons-outlined">close</i>
        </a>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('save.theme') }}" method="POST" id="theme-form">
            @csrf
            <div>
                <p>Tema Varyasyonu</p>

                @php
                    $userTheme = Auth::check() ? Auth::user()->theme : 'light';
                @endphp

                <div class="row g-3">
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme" id="blue-theme" value="blue-theme" {{ $userTheme == 'blue-theme' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="blue-theme">
                            <span class="material-icons-outlined">contactless</span>
                            <span>Blue</span>
                        </label>
                    </div>
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme" id="light" value="light" {{ $userTheme == 'light' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="light">
                            <span class="material-icons-outlined">light_mode</span>
                            <span>Light</span>
                        </label>
                    </div>
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme" id="dark" value="dark" {{ $userTheme == 'dark' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="dark">
                            <span class="material-icons-outlined">dark_mode</span>
                            <span>Dark</span>
                        </label>
                    </div>
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme" id="semi-dark" value="semi-dark" {{ $userTheme == 'semi-dark' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="semi-dark">
                            <span class="material-icons-outlined">contrast</span>
                            <span>Semi Dark</span>
                        </label>
                    </div>
                    <div class="col-12 col-xl-6">
                        <input type="radio" class="btn-check" name="theme" id="bodered-theme" value="bodered-theme" {{ $userTheme == 'bodered-theme' ? 'checked' : '' }} onchange="this.form.submit()">
                        <label class="btn btn-outline-secondary d-flex flex-column gap-1 align-items-center justify-content-center p-4" for="bodered-theme">
                            <span class="material-icons-outlined">border_style</span>
                            <span>Bordered</span>
                        </label>
                    </div>
                </div><!--end row-->
            </div>
        </form>
    </div>
</div>
<!--end switcher-->
