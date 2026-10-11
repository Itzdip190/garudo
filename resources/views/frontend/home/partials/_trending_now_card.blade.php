<div class="trending-now-card-wrap flex-shrink-0" style="width: 185px;">
    @php
        $isWishlisted = !empty($product->id) ? \App\Services\WishlistService::has($product->id) : false;
    @endphp
    <div class="card garudo-product-card h-100 p-2.5 position-relative border bg-white rounded-3 shadow-xs d-flex flex-column justify-content-between trending-product-card" 
         style="border-color: #e2e8f0; border-radius: 14px !important; min-height: 250px; transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;">
        
        <!-- Top Badges Row -->
        <div class="d-flex align-items-center justify-content-between mb-1.5 px-0.5 position-relative" style="z-index: 2;">
            @if($product->discount_percent > 0)
                <span class="badge rounded-pill fw-bold" style="font-size: 9.5px; background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 3px 7px;">
                    {{ $product->discount_percent }}% OFF
                </span>
            @else
                <span class="badge rounded-pill fw-bold" style="font-size: 9px; background: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; padding: 3px 7px;">
                    <i class="bi bi-fire me-0.5"></i> TRENDING
                </span>
            @endif

            <!-- Wishlist Love Button -->
            @if(!empty($product->id))
                <button type="button" 
                        class="btn-wishlist-toggle {{ $isWishlisted ? 'active' : '' }}" 
                        data-id="{{ $product->id }}" 
                        onclick="event.preventDefault(); event.stopPropagation(); toggleWishlistCard(this, {{ $product->id }})" 
                        title="{{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}" 
                        style="width: 26px; height: 26px; border-radius: 50%; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,0.06); display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; transition: all 0.2s ease;">
                    <i class="bi {{ $isWishlisted ? 'bi-heart-fill text-danger' : 'bi-heart' }}" 
                       style="font-size: 12px; color: {{ $isWishlisted ? '#ef4444' : '#64748b' }};"></i>
                </button>
            @else
                <span class="d-inline-flex align-items-center gap-1 text-muted" style="font-size: 11px; font-weight: 600;">
                    <i class="bi bi-star-fill text-warning" style="font-size: 10px;"></i>
                    <span>{{ number_format($product->rating ?: 4.8, 1) }}</span>
                </span>
            @endif
        </div>

        <!-- Product Image Showcase Canvas -->
        <a href="{{ $product->slug ? route('products.show', $product->slug) : '#' }}" class="d-block text-decoration-none">
            <div class="d-flex align-items-center justify-content-center rounded-3 mb-2 trending-img-canvas" 
                 style="height: 115px; background: radial-gradient(circle at center, #ffffff 30%, #f8fafc 100%); border: 1px solid #f1f5f9; overflow: hidden; padding: 6px; position: relative;">
                <img src="{{ $product->primary_image_url }}" 
                     onerror="this.onerror=null; this.src='{{ asset('images/product-placeholder.png') }}';" 
                     class="img-fluid trending-card-img" 
                     style="max-height: 105px; max-width: 92%; object-fit: contain; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.06)); transition: transform 0.35s ease;" 
                     alt="{{ $product->name }}"
                     loading="lazy">
            </div>
        </a>

            <!-- Card Body Content -->
            <div class="d-flex flex-column flex-grow-1 justify-content-between">
                <div>
                    <!-- Category Micro-tag -->
                    <div class="text-uppercase fw-bold text-truncate mb-0.5" style="font-size: 9.5px; letter-spacing: 0.5px; color: #64748b;">
                        {{ $product->category->name ?? 'Wholesale Supply' }}
                    </div>

                    <!-- Product Title -->
                    <div class="fw-bold mb-1.5 trending-card-title" 
                         style="font-size: 12.5px; color: #0f172a; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 33px;" 
                         title="{{ $product->name }}">
                        {{ $product->name }}
                    </div>
                </div>

                <!-- Price & Unit -->
                <div class="pt-1 border-top border-light">
                    <div class="d-flex align-items-baseline gap-1">
                        @if($product->mrp > 0 && $product->mrp > $product->selling_price)
                            <del class="text-muted text-decoration-line-through me-0.5" style="font-size: 11px;">₹{{ number_format($product->mrp) }}</del>
                        @endif
                        <span class="fw-bold" style="font-size: 15px; font-weight: 800; color: #0f172a; letter-spacing: -0.2px;">
                            ₹{{ number_format($product->selling_price ?: ($product->mrp ?: 0)) }}
                        </span>
                        @if(!empty($product->unit))
                            <span class="text-muted" style="font-size: 11px; font-weight: 500;">
                                /{{ str_starts_with(strtolower($product->unit), '/') ? ltrim($product->unit, '/') : $product->unit }}
                            </span>
                        @endif
                    </div>

                    <!-- Subtle Action Button -->
                    <div class="mt-2">
                        <a href="{{ $product->slug ? route('products.show', $product->slug) : '#' }}" 
                           class="btn btn-sm w-100 rounded-pill py-1 fw-bold d-flex align-items-center justify-content-center gap-1 trending-card-cta text-decoration-none" 
                           style="font-size: 11px; background: #0f172a; color: #ffffff; border: none; transition: all 0.25s ease;">
                            <span>View Details</span>
                            <i class="bi bi-arrow-right" style="font-size: 11px;"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
</div>
