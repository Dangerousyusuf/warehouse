<div class="card-body">
    <div class="table-responsive">
        <table id="example2" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ürün Adı</th>
                    <th>Ürün Kodu</th>
                    <th>Toplam Stok</th>
                    <th>Tür</th>
                    <th>Eklenme Tarihi</th>
                    <th>Depo İşlemleri</th>
                    <th>Stok Hareketleri</th>
                    <th>Sipariş Talep</th>
                    <th>Sil</th>
                </tr>
            </thead>

            <tbody>
              
                @forelse($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-box">
                                    <img src="{{ asset('storage/' . $product->firstImage) }}" class="rounded-3" width="50"
                                        alt="">
                                </div>
                                <div class="product-info">
                                    <a href="javascript:;" class="product-title">{{ $product->name }}</a>
                                    <p class="mb-0 product-category">Kategori: {{ $product->category->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ $product->totalStock }}</td>
                        <td>{{ $product->product_type }}</td>
                        <td>{{ strftime('%d %B, %H:%M', strtotime($product->created_at)) }}</td>
                        <td>
                            <button class="btn btn-success btn-sm toggle-details" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $product->id }}" aria-expanded="false"
                                aria-controls="collapse-{{ $product->id }}" data-product-id="{{ $product->id }}">
                                Depo/Raf Bilgisi
                            </button>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <form action="{{ route('stock.stock_movement', $product->id) }}" method="GET">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button class="btn btn-primary btn-sm" type="submit">
                                        Stok Hareketleri
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-danger" title="Sipariş Ver">Sipariş Ver</button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                @if($product->totalStock == 0)
                                <form action="{{ route('stock.destroy', [$product->warehouse_id, $product->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Sil">Sil</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    
                    @if($product->variations->isNotEmpty())
                        <!-- Ürüne ait varyantları doğrudan ürün gibi gösterelim -->
                        @foreach($product->variations as $variant)
                            <tr>
                                <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="product-box">
                                            <img src="{{ asset('storage/' . $variant->firstImage) }}" class="rounded-3" width="50"
                                                alt="">
                                        </div>
                                        <div class="product-info">
                                            <a href="javascript:;" class="product-title">{{ $variant->name }}</a>
                                            <p class="mb-0 product-category">Kategori: {{ $product->category->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $variant->sku }}</td>
                                <td>{{ $variant->totalStock }}</td>
                                <td>{{ $product->product_type }}</td>
                                <td>{{ strftime('%d %B, %H:%M', strtotime($variant->created_at)) }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm toggle-details" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $variant->id }}" aria-expanded="false"
                                        aria-controls="collapse-{{ $variant->id }}" data-product-id="{{ $variant->id }}">
                                        Depo/Raf Bilgisi
                                    </button>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                    
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-danger" title="Sipariş Ver">Sipariş Ver</button>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('stock.destroy', [$variant->warehouse_id, $variant->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Sil">Sil</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif

                @empty
                    <tr>
                        <td colspan="9" class="text-center">Ürün bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>