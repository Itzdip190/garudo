<div class="best-selling-card-wrap flex-shrink-0" style="width: 238px; min-width: 238px;">
    @php
        $discount = $product->discount_percent > 0 ? $product->discount_percent : ($product->mrp > $product->selling_price ? round((($product->mrp - $product->selling_price) / $product->mrp) * 100) : 10);
        $isWishlisted = \App\Services\WishlistService::has($product->id);
        $vendorName = $product->vendor->company_name ?? ($product->brand->name ?? 'Verified Seller');
    @endphp
    <div class="card garudo-product-card h-100 position-relative border bg-white shadow-xs d-flex flex-column justify-content-between" 
         style="padding: 13px 13px 12px 13px; border-color: #e2e8f0; border-radius: 16px !important; transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;">
        
        <!-- Discount Badge (Top Left) -->
        <span class="badge position-absolute top-0 start-0 m-2.5 px-2.5 py-1" 
              style="background: linear-gradient(135deg, #ef4444 0%, #ea580c 100%); color: #ffffff; font-size: 10.5px; font-weight: 800; border-radius: 6px; z-index: 3; box-shadow: 0 3px 8px -1px rgba(239, 68, 68, 0.4); letter-spacing: 0.3px;">
            {{ $discount }}% OFF
        </span>

        <!-- Wishlist / Love Icon Button (Top Right) -->
        <button type="button" 
                class="btn-wishlist-toggle position-absolute top-0 end-0 m-2.5 {{ $isWishlisted ? 'active' : '' }}" 
                data-id="{{ $product->id }}" 
                onclick="event.preventDefault(); event.stopPropagation(); toggleWishlistCard(this, {{ $product->id }})" 
                title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}" 
                style="z-index: 4; width: 32px; height: 32px; border-radius: 50%; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(6px); border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08); display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
            <i class="bi {{ $isWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }}" 
               style="font-size: 13.5px; color: {{ $isWishlisted ? '#ef4444' : '#64748b' }}; transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275), color 0.2s ease;"></i>
        </button>

        <div>
            <!-- Product Image Showcase Canvas -->
            <a href="{{ route('products.show', $product->slug) }}" 
               class="d-block text-center rounded-3 mb-2.5 garudo-card-img-canvas" 
               style="height: 140px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8fafc !important; border: 1px solid #f1f5f9; border-radius: 12px; position: relative;">
                <img src="{{ $product->primary_image_url }}" 
                     onerror="this.onerror=null; this.src='/images/product-placeholder.png';" 
                     class="img-fluid garudo-card-img" 
                     style="max-height: 122px; max-width: 90%; object-fit: contain; transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);" 
                     alt="{{ $product->name }}" 
                     loading="lazy">
            </a>

            <!-- Verified Vendor Line (✓ Vendor Name + Rating Pill) -->
            <div class="d-flex align-items-center justify-content-between mb-1.5" style="font-size: 11px;">
                <div class="d-flex align-items-center gap-1.5 text-truncate" style="max-width: 68%;">
                    <i class="bi bi-patch-check-fill" style="font-size: 12px; color: #2563eb; flex-shrink: 0;"></i>
                    <span class="text-truncate fw-bold" style="color: #2563eb; font-size: 12px; letter-spacing: -0.1px;">{{ $vendorName }}</span>
                </div>
                <div class="d-inline-flex align-items-center gap-1 flex-shrink-0" 
                     style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 11px; font-weight: 700; padding: 2px 6.5px; border-radius: 6px;">
                    <i class="bi bi-star-fill text-warning" style="font-size: 9.5px;"></i>
                    <span>{{ number_format($product->rating ?: 4.8, 1) }}</span>
                </div>
            </div>

            <!-- Product Title -->
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none d-block">
                <div class="fw-bold garudo-card-title mb-2" 
                     style="font-size: 13.5px; color: #0f172a; line-height: 1.38; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 37px; transition: color 0.18s ease;" 
                     title="{{ $product->name }}">
                    {{ $product->name }}
                </div>
            </a>

            <!-- Pricing & Unit -->
            <div class="mb-2.5 d-flex align-items-baseline gap-1.5 flex-wrap">
                <span style="font-size: 16.5px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px;">
                    ₹{{ number_format($product->selling_price) }}
                </span>
                <span style="font-size: 12.5px; font-weight: 600; color: #64748b;">
                    / {{ $product->unit ?: 'piece' }}
                </span>
                @if($product->mrp > $product->selling_price)
                    <del class="text-muted ms-auto" style="font-size: 11.5px; color: #94a3b8 !important; text-decoration: line-through; font-weight: 500;">
                        ₹{{ number_format($product->mrp) }}
                    </del>
                @endif
            </div>
        </div>

        <!-- Action Buttons: Quick View & Add to Cart -->
        <div class="d-flex gap-2 pt-2 border-top" style="border-color: #f1f5f9;">
            <button type="button" class="btn btn-sm flex-fill fw-semibold btn-quick-view-modal garudo-btn-quickview"
                data-id="{{ $product->id }}"
                data-name="{{ $product->name }}"
                data-price="₹{{ number_format($product->selling_price) }}"
                data-unit="{{ $product->unit ?: 'Piece' }}"
                data-mrp="{{ $product->mrp > $product->selling_price ? '₹' . number_format($product->mrp) : '' }}"
                data-image="{{ $product->primary_image_url }}"
                data-vendor="{{ $vendorName }}"
                data-rating="{{ number_format($product->rating ?: 4.7, 1) }}"
                data-url="{{ route('products.show', $product->slug) }}"
                style="font-size: 11.5px; padding: 7px 8px; background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);">
                <i class="bi bi-eye" style="font-size: 12px;"></i> Quick View
            </button>
            <button type="button" onclick="ajaxAddToCart({{ $product->id }}, 1)" 
                class="btn btn-sm flex-fill fw-bold d-flex align-items-center justify-content-center garudo-btn-addcart" 
                style="font-size: 11.5px; padding: 7px 8px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: #ffffff; border: none; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 4px; box-shadow: 0 3px 8px rgba(249, 115, 22, 0.28); transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);"
                title="Add to Cart">
                <i class="bi bi-cart3" style="font-size: 12px;"></i> Add to Cart
            </button>
        </div>
    </div>
</div>
