<div id="product-list">
   <div class="card-body">
      <table class="table" id="example2" class="table table-striped table-bordered">
         <thead>
            <tr>
               <th>#</th>
               <th>Ürün Adı</th>
               <th>Ürün Kodu</th>
               <th>Kategori</th>
               <th>Tip</th>
               <th>İşlemler</th>
            </tr>
         </thead>
         <tbody>
    
    @forelse($products as $product)
        @if($product->parent_id == null && $product->deleted_at == null)
        <!-- Ana ürün -->
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <div class="d-flex align-items-center gap-3">
                    <div class="product-box">
                        @php
                            $productImages = explode(',', $product->image);
                            $firstImage = $productImages[0];
                        @endphp
                        <img src="{{ asset('storage/' . $firstImage) }}" width="50" class="rounded-3" alt="">
                    </div>
                    <div class="product-info">
                        <a href="{{ route('products.edit', $product->id) }}" class="product-title">{{ $product->name }}</a>
                    </div>
                </div>
            </td>
            <td>{{ $product->product_code }}</td>
            <td>{{ $product->category->name ?? 'Kategori Yok' }}</td>
            <td>{{ is_null($product->parent_id) ? 'Ürün' : 'Varyant' }}</td>
            <td>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Düzenle</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Silmek istediğinizden emin misiniz?')">Sil</button>
                </form>
            </td>
        </tr>
        @endif
        
        <!-- Varyantlar -->
        @if($product->children->isNotEmpty())

                @foreach($product->children as $variant)
                    @if($variant->parent_id != null && $variant->parent_variation_id == null && $variant->deleted_at == null)
                    <tr>
                    <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="product-box">
                                <img src="{{ asset('storage/' . $variant->image) }}" width="50" class="rounded-3" alt="">
                            </div>
                            <div class="product-info">
                                <span class="product-title">{{ $variant->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td>{{ $variant->product_code }}</td>
                    <td>{{ $product->category->name ?? 'Kategori Yok' }}</td>
                    <td>Varyant</td>
                    <td>
                        <form action="{{ route('products.destroy', $variant->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bu varyantı silmek istediğinizden emin misiniz?')">
                                Sil
                            </button>
                        </form>
                    </td>
                </tr>
                @endif
                <!-- Alt varyantlar -->
                @if($variant->children->isNotEmpty())
                   
                    @foreach($variant->children as $subVariant)
                        @if($subVariant->parent_variation_id != null && $subVariant->deleted_at == null)
                        <tr>
                            <td>{{ $loop->parent->iteration }}.{{ $loop->iteration + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="product-box">
                                        <img src="{{ asset('storage/' . $subVariant->image) }}" width="50" class="rounded-3" alt="">
                                    </div>
                                    <div class="product-info">
                                        <span class="product-title">{{ $subVariant->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $subVariant->product_code }}</td>
                            <td>{{ $product->category->name ?? 'Kategori Yok' }}</td>
                            <td>Alt Varyant</td>
                            <td>
                                <form action="{{ route('products.destroy', $subVariant->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bu alt varyantı silmek istediğinizden emin misiniz?')">
                                        Sil
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif
    @empty
        <tr>
            <td colspan="6" class="text-center">Ürün bulunamadı.</td>
        </tr>
    @endforelse
</tbody>




      </table>
   </div>
</div>