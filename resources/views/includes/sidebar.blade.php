<!--start sidebar-->
<aside class="sidebar-wrapper" data-simplebar="true">
  <!-- Loader -->
  <div id="sidebarLoader" class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
    </div>
  </div>

  <div class="sidebar-content">
    <!-- Mevcut sidebar içeriğiniz buraya gelecek -->
    <div class="sidebar-header">
      <div class="logo-icon">
        <img src="assets/images/logo2.png" class="logo-img" alt="">
      </div>
      <div class="logo-name flex-grow-1">
        <h5 class="mb-0">teDepo</h5>
      </div>
      <div class="sidebar-close">
        <span class="material-icons-outlined">close</span>
      </div>
    </div>
    <div class="sidebar-nav">
      <!--navigation-->
      <ul class="metismenu" id="sidenav">
        <li>
          <a href="{{route('index')}}">
            <div class="parent-icon"><i class="material-icons-outlined">home</i>
            </div>
            <div class="menu-title">Ana Sayfa</div>
          </a>
        </li>
        @php
        $userRole = Auth::user()->role ?? 'Tanımsız Rol';
        @endphp
        @if(in_array($userRole, ['Yönetici', 'Müdür']))
        <li class="menu-label">İşletme Ayarları</li>
        <li>
          <a href="{{route('factory.settings')}}">
            <div class="parent-icon"><i class="material-icons-outlined">apartment</i>
            </div>
            <div class="menu-title">Fabrika Ayarları</div>
          </a>
        </li>
  
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="material-icons-outlined">widgets</i>
            </div>
            <div class="menu-title">Depo</div>
          </a>
          <ul>
            <li><a href="{{route('warehouse.warehouse_add')}}"><i class="material-icons-outlined">arrow_right</i>Depo Ekle</a>
            </li>
            <li><a href="{{route('warehouse.warehouse_list')}}"><i class="material-icons-outlined">arrow_right</i>Depo Liste</a>
            </li>
          </ul>
        </li>
        @endif
       
        @if(in_array($userRole, ['Yönetici', 'Müdür', 'Depo Sorumlusu']))
        <li class="menu-label">Ürün İşlemleri</li>
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="material-icons-outlined">layers</i>
            </div>
            <div class="menu-title">Kategori</div>
          </a>
          <ul>
          <li><a href="{{ route('categories.add') }}"><i class="material-icons-outlined">arrow_right</i>Kategori Ekle</a></li>
          <li><a href="{{ route('categories.index') }}"><i class="material-icons-outlined">arrow_right</i>Kategori Liste</a></li>
          </ul>
        </li>
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="material-icons-outlined">shopping_bag</i>
            </div>
            <div class="menu-title">Ürün</div>
          </a>
          <ul>
            <li><a href="{{route('product.product_add')}}"><i class="material-icons-outlined">arrow_right</i>Ürün Ekle</a>
            </li>
            <li><a href="{{route('product.product_list')}}"><i class="material-icons-outlined">arrow_right</i>Ürün Liste</a>
            </li>
            <li><a href="{{route('variations.index')}}"><i class="material-icons-outlined">arrow_right</i>Varyant Liste</a>
            </li>
          </ul>
        </li>
        <li class="menu-label">Stok İşlemleri</li>
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="material-icons-outlined">inventory_2</i>
            </div>
            <div class="menu-title">Stok</div>
          </a>
          <ul>
            <li><a href="{{route('stock.stock_list')}}"><i class="material-icons-outlined">arrow_right</i>Stok Liste</a>
            </li>
            <li><a href="{{route('stock.stock_movements')}}"><i class="material-icons-outlined">arrow_right</i>Stok Hareketleri</a>
            </li>
          </ul>
        </li>
        
        <li class="menu-label">Transfer İşlemleri</li>
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="material-icons-outlined">swap_horiz</i>
            </div>
            <div class="menu-title ">
              Transfer
            </div>
          </a>
          <ul>
            <li>
              <a href="{{route('transfer.transfer_list')}}"><i class="material-icons-outlined">arrow_right</i>Transfer Başlat</a>
            </li>
            <li>
              <a href="{{route('transfer.transfer_incoming')}}"><i class="material-icons-outlined">arrow_right</i>Gelen Transfer
                <span class="badge bg-primary rounded-pill ms-4">3</span></a>
            </li>
            <li><a href="{{route('transfer.transfer_outgoing')}}"><i class="material-icons-outlined">arrow_right</i>Giden Transfer <span
                  class="badge bg-primary rounded-pill ms-4">2</span></a>
            </li>
          </ul>
        </li>
        @endif
        @if(in_array($userRole, ['Yönetici', 'Müdür']))
        <li class="menu-label">Kullanıcı İşlemleri</li>
        <li>
          <a href="javascript:;" class="has-arrow">
            <div class="parent-icon"><i class="material-icons-outlined">person</i>
            </div>
            <div class="menu-title">Kullanıcı</div>
          </a>
          <ul>
            <li><a href="{{route('users.user_add')}}"><i class="material-icons-outlined">arrow_right</i>Kullanıcı Ekle</a>
            </li>
            <li><a href="{{route('users.user_list')}}"><i class="material-icons-outlined">arrow_right</i>Kullanıcı Liste</a>
            </li>
          </ul>
        </li>
        <li>
          <a href="{{route('activity.activity_list')}}">
            <div class="parent-icon"><i class="material-icons-outlined">join_right</i>
            </div>
            <div class="menu-title">Aktiviteler</div>
          </a>
        </li>
        @endif
      </ul>
      <!--end navigation-->
    </div>
  </div>
  
  <!-- Loader (sidebar dışında) -->

</aside>
<!--end sidebar-->

<style>
  #sidebarLoader {
    opacity: 1;
    visibility: visible;
    transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
    z-index: 9999;
  }

  .sidebar-wrapper.loaded #sidebarLoader {
    opacity: 0;
    visibility: hidden;
  }

  /* Tema-spesifik arka plan renkleri */
  [data-bs-theme="blue-theme"] #sidebarLoader {
    background-color: #0f1535;
  }

  [data-bs-theme="light"] #sidebarLoader {
    background-color: #f8f9fa;
  }

  [data-bs-theme="dark"] #sidebarLoader {
    background-color: #212529;
  }

  [data-bs-theme="semi-dark"] #sidebarLoader {
    background-color: #343a40;
  }

  [data-bs-theme="bodered-theme"] #sidebarLoader {
    background-color: #ffffff;
    border: 1px solid #dee2e6;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const htmlElement = document.documentElement;
    const sidebarLoader = document.getElementById('sidebarLoader');
    const spinnerBorder = sidebarLoader.querySelector('.spinner-border');

    function updateLoaderColors() {
      const theme = htmlElement.getAttribute('data-bs-theme');
      if (theme === 'light' || theme === 'bodered-theme') {
        spinnerBorder.classList.remove('text-light');
        spinnerBorder.classList.add('text-primary');
      } else {
        spinnerBorder.classList.remove('text-primary');
        spinnerBorder.classList.add('text-light');
      }
    }

    // İlk yüklemede renkleri güncelle
    updateLoaderColors();

    // Tema değiştiğinde renkleri güncelle
    const observer = new MutationObserver(updateLoaderColors);
    observer.observe(htmlElement, { attributes: true, attributeFilter: ['data-bs-theme'] });

    // Sayfa yüklendiğinde loader'ı gizle
    window.addEventListener('load', function() {
  
        document.querySelector('.sidebar-wrapper').classList.add('loaded');

    });
  });
</script>
<!-- Sayfanın en altına ekleyin -->


