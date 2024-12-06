@php
    use Illuminate\Support\Facades\Auth;
    // Kullanıcının seçili depo ID'sini al
    $selectedWarehouseId = Auth::user()->selected_warehouse_id; // Eğer null ise boş dizi ata
    $warehouses = Auth::user()->warehouses->whereNull('deleted_at'); // Kullanıcının yetkili olduğu depolar
@endphp
<!--start header-->
<header class="top-header mb-5">
    <nav class="navbar navbar-expand align-items-center gap-4 ">
        <div class="btn-toggle">
            <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
        </div>
        <div class="search-bar flex-grow-1">
            <div class="position-relative">

                <div class="search-popup p-3">
                    <div class="card rounded-4 overflow-hidden">

                        <div class="card-body search-content">

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <ul class="navbar-nav gap-1 nav-right-links align-items-center">
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="warehouseDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $selectedWarehouseId && Auth::user()->warehouses->find($selectedWarehouseId) ? Auth::user()->warehouses->find($selectedWarehouseId)->name : 'Depolar' }}
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end" aria-labelledby="warehouseDropdown" style="padding: 10px 15px;">
                @forelse($warehouses as $warehouse)
                    <a class="dropdown-item text-center" href="{{ route('warehouse.select', ['id' => $warehouse->id]) }}" 
                       onclick="event.preventDefault(); document.getElementById('select-warehouse-{{ $warehouse->id }}').submit();">
                        {{ $warehouse->name }}
                    </a>
                    <form id="select-warehouse-{{ $warehouse->id }}" action="{{ route('warehouse.saveSelection') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="selected_warehouse_id" value="{{ $warehouse->id }}">
                    </form>
                @empty
                    <a class="dropdown-item text-center" href="#">Kullanıcının yetkili olduğu depo yok.</a>
                @endforelse
            </div>
        </div>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                    data-bs-toggle="dropdown"><img src="{{ asset('assets/images/county/01.png') }}" width="22" alt="">
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    
                    <li><a class="dropdown-item d-flex align-items-center py-2" href="javascript:;"><img
                                src="{{ asset('assets/images/county/01.png') }}" width="20" alt=""><span
                                class="ms-2">Türkçe</span></a>
                    </li>

                </ul>
            </li>
            <li class="nav-item dropdown">
                <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
                    <div class="notify-list">
                    </div>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a href="javascript:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/images/avatars/11.png') }}"
                        class="rounded-circle p-1 border border-success" width="45" height="45" alt="">
                </a>
                <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
                    <a class="dropdown-item  gap-2 py-2" href="javascript:;">
                        <div class="text-center">
                            <img src="{{ asset('assets/images/avatars/11.png') }}"
                                class="rounded-circle p-1 shadow mb-3" width="90" height="90" alt="">
                            <h5 class="user-name mb-0 fw-bold">Merhaba,
                                {{ mb_strtoupper(strlen(explode(' ', Auth::user()->name)[0]) > 8 ? substr(explode(' ', Auth::user()->name)[0], 0, 8) . '...' : explode(' ', Auth::user()->name)[0], 'UTF-8') }}
                            </h5>
                        </div>
                    </a>
                    <hr class="dropdown-divider">
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="{{route('settings.settings')}}"><i class="material-icons-outlined">settings</i>Ayarlar</a>
                    <hr class="dropdown-divider">
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('logout') }}" onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        <i class="material-icons-outlined">power_settings_new</i>Çıkış Yap
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

    </nav>
</header>
<br>
<!--end top header-->
