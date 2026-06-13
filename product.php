<?php 
require_once 'data.php';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProductById($productId);

if (!$product) {
    header('Location: index.php');
    exit;
}

$category = getCategoryById($product['category']);
$relatedProducts = array_filter($products, function($p) use ($product) {
    return $p['category'] === $product['category'] && $p['id'] !== $product['id'];
});

$pageTitle = $product['name'] . ' - CarrefourSA';
$currentPage = '';
?>
<?php include 'includes/header.php'; ?>

    <div id="flying-item" class="fixed pointer-events-none z-[9999] opacity-0">
        <div class="w-12 h-12 bg-brand-primary rounded-full flex items-center justify-center text-white shadow-lg">
            <i data-lucide="shopping-basket" class="w-5 h-5"></i>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 lg:px-6 py-6 lg:py-10">
        
        <nav class="hidden lg:flex items-center gap-2 text-sm text-slate-500 mb-8">
            <a href="index.php" class="hover:text-brand-primary transition-colors">Anasayfa</a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
            <a href="#" class="hover:text-brand-primary transition-colors"><?= $category['name'] ?></a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-slate-300"></i>
            <span class="text-slate-800 font-medium"><?= $product['name'] ?></span>
        </nav>

        <div class="bg-white rounded-2xl overflow-hidden shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                
                <div class="p-8 lg:p-16 flex flex-col items-center justify-center border-b lg:border-b-0 lg:border-r border-slate-100">
                    <div class="relative w-full max-w-sm">
                        <?php if($product['badge']): ?>
                            <div class="absolute top-0 left-0 z-10">
                                <span class="inline-block bg-brand-accent text-white text-xs font-semibold px-3 py-1.5 rounded">
                                    <?= $product['badge'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <button class="absolute top-0 right-0 z-10 w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 hover:text-brand-accent hover:bg-slate-200 transition-colors">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                        
                        <div class="aspect-square flex items-center justify-center p-4">
                            <img id="product-image" src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="max-w-full max-h-full object-contain transition-all duration-300">
                        </div>
                    </div>
                    
                    <?php 
                    $allImages = [];
                    if ($product['image']) $allImages[] = $product['image'];
                    if (!empty($product['images'])) {
                        foreach ($product['images'] as $img) {
                            $allImages[] = $img['image_url'];
                        }
                    }
                    if (count($allImages) > 1): 
                    ?>
                    <div class="flex gap-2 mt-4 overflow-x-auto pb-2">
                        <?php foreach ($allImages as $index => $imgUrl): ?>
                        <button onclick="changeMainImage('<?= htmlspecialchars($imgUrl) ?>', this)" class="w-16 h-16 rounded-lg border-2 <?= $index === 0 ? 'border-brand-primary' : 'border-slate-200' ?> overflow-hidden flex-shrink-0 hover:border-brand-primary transition-colors thumbnail-btn">
                            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="Ürün görseli" class="w-full h-full object-contain p-1">
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="p-8 lg:p-16 flex flex-col justify-center">
                    <div class="max-w-sm mx-auto lg:mx-0 w-full">
                        
                        <p class="text-xs font-semibold text-brand-primary tracking-wider mb-2">CARREFOURSA</p>
                        
                        <h1 class="text-2xl lg:text-3xl font-bold text-slate-800 mb-4"><?= $product['name'] ?></h1>
                        
                        <div class="text-slate-500 text-sm leading-relaxed mb-6 product-description prose prose-sm prose-slate max-w-none"><?= parseMarkdown($product['description']) ?></div>

                        <div class="flex items-center gap-2 mb-4">
                            <?php if($product['stock'] > 0): ?>
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                <span class="text-sm text-slate-600">Stokta</span>
                            <?php else: ?>
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                <span class="text-sm text-slate-600">Tükendi</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($product['variants'])): ?>
                        <?php 
                        $colorVariants = array_filter($product['variants'], fn($v) => $v['variant_type'] === 'color');
                        $modelVariants = array_filter($product['variants'], fn($v) => $v['variant_type'] === 'model');
                        ?>
                        
                        <?php if (!empty($colorVariants)): ?>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-slate-700 mb-2">Renk Seçimi</p>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($colorVariants as $variant): ?>
                                <button onclick="selectVariant(<?= $variant['id'] ?>, '<?= htmlspecialchars($variant['image_url'] ?: $product['image']) ?>', '<?= htmlspecialchars($variant['variant_name']) ?>')" 
                                    class="variant-btn px-4 py-2 rounded-lg border-2 border-slate-200 text-sm font-medium text-slate-700 hover:border-brand-primary transition-colors"
                                    data-variant-id="<?= $variant['id'] ?>">
                                    <?= htmlspecialchars($variant['variant_name']) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($modelVariants)): ?>
                        <div class="mb-4">
                            <p class="text-sm font-medium text-slate-700 mb-2">Model Seçimi</p>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($modelVariants as $variant): ?>
                                <button onclick="selectVariant(<?= $variant['id'] ?>, '<?= htmlspecialchars($variant['image_url'] ?: $product['image']) ?>', '<?= htmlspecialchars($variant['variant_name']) ?>')" 
                                    class="variant-btn px-4 py-2 rounded-lg border-2 border-slate-200 text-sm font-medium text-slate-700 hover:border-brand-primary transition-colors"
                                    data-variant-id="<?= $variant['id'] ?>">
                                    <?= htmlspecialchars($variant['variant_name']) ?>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>

                        <div class="mb-8">
                            <div class="flex items-baseline gap-2">
                                <?php if($product['old_price']): ?>
                                    <span class="text-lg text-slate-400 line-through"><?= number_format($product['old_price'], 2, ',', '.') ?></span>
                                <?php endif; ?>
                                <span class="text-3xl font-bold text-slate-800"><?= number_format($product['price'], 2, ',', '.') ?></span>
                                <span class="text-base text-slate-500">TL</span>
                            </div>
                            <?php if($product['old_price']): ?>
                                <?php $discount = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>
                                <span class="inline-block mt-2 bg-green-50 text-green-600 text-xs font-medium px-2 py-1 rounded">%<?= $discount ?> indirim</span>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center gap-3 mb-8">
                            <div class="flex items-center border border-slate-200 rounded-xl">
                                <button class="w-11 h-11 flex items-center justify-center text-slate-500 hover:text-brand-primary transition-colors" onclick="decreaseQty()">
                                    <i data-lucide="minus" class="w-4 h-4"></i>
                                </button>
                                <input type="text" id="quantity" value="1" class="w-12 h-11 text-center font-semibold text-lg bg-transparent focus:outline-none" readonly>
                                <button class="w-11 h-11 flex items-center justify-center text-slate-500 hover:text-brand-primary transition-colors" onclick="increaseQty()">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                            
                            <button id="add-to-cart-btn" onclick="addToCart(event)" class="flex-1 bg-brand-primary hover:bg-brand-secondary text-white font-semibold h-11 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
                                <i data-lucide="shopping-basket" class="w-5 h-5"></i>
                                <span>Sepete Ekle</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-100">
                            <div class="text-center">
                                <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="truck" class="w-4 h-4 text-brand-primary"></i>
                                </div>
                                <span class="text-xs text-slate-500">Hızlı Teslimat</span>
                            </div>
                            <div class="text-center">
                                <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="package" class="w-4 h-4 text-brand-primary"></i>
                                </div>
                                <span class="text-xs text-slate-500">Kolay İade</span>
                            </div>
                            <div class="text-center">
                                <div class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i data-lucide="credit-card" class="w-4 h-4 text-brand-primary"></i>
                                </div>
                                <span class="text-xs text-slate-500">Güvenli Ödeme</span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <?php if(count($relatedProducts) > 0): ?>
        <section class="mt-12">
            <h2 class="text-xl font-bold text-slate-800 mb-6">Benzer Ürünler</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach(array_slice($relatedProducts, 0, 4) as $relProduct): ?>
                    <a href="product.php?id=<?= $relProduct['id'] ?>" class="product-card bg-white rounded-xl overflow-hidden relative group block">
                        <?php if($relProduct['badge']): ?>
                            <div class="absolute top-3 left-3 z-10">
                                <span class="inline-block bg-brand-accent text-white text-[11px] font-semibold px-2.5 py-1 rounded">
                                    <?= $relProduct['badge'] ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="aspect-square p-6 bg-white">
                            <img src="<?= $relProduct['image'] ?>" alt="<?= $relProduct['name'] ?>" class="w-full h-full object-contain">
                        </div>

                        <div class="p-4 border-t border-slate-50">
                            <div class="text-[11px] font-semibold text-brand-primary mb-1 tracking-wide">CARREFOURSA</div>
                            
                            <h3 class="text-sm font-medium text-slate-700 leading-snug mb-3 line-clamp-2 h-10">
                                <?= $relProduct['name'] ?>
                            </h3>

                            <div class="flex items-end justify-between gap-2">
                                <div>
                                    <?php if($relProduct['old_price']): ?>
                                        <div class="text-xs text-slate-400 line-through mb-0.5">
                                            <?= number_format($relProduct['old_price'], 2, ',', '.') ?> TL
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-xl font-bold text-brand-accent">
                                        <?= number_format($relProduct['price'], 2, ',', '.') ?> <span class="text-xs font-semibold text-slate-500">TL</span>
                                    </div>
                                </div>
                                
                                <button class="w-11 h-11 bg-brand-primary rounded-lg flex items-center justify-center text-white hover:bg-brand-secondary transition-colors" onclick="event.preventDefault();">
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/mobile-nav.php'; ?>
<?php include 'includes/tracker.php'; ?>

    <script>
        const currentProduct = <?= json_encode($product) ?>;
        let selectedVariant = null;
        
        function changeMainImage(imageUrl, btn) {
            document.getElementById('product-image').src = imageUrl;
            document.querySelectorAll('.thumbnail-btn').forEach(b => b.classList.remove('border-brand-primary'));
            if (btn) btn.classList.add('border-brand-primary');
        }
        
        function selectVariant(variantId, imageUrl, variantName) {
            selectedVariant = { id: variantId, name: variantName };
            if (imageUrl) {
                document.getElementById('product-image').src = imageUrl;
            }
            document.querySelectorAll('.variant-btn').forEach(b => {
                b.classList.remove('border-brand-primary', 'bg-brand-primary/10');
                b.classList.add('border-slate-200');
            });
            const btn = document.querySelector(`[data-variant-id="${variantId}"]`);
            if (btn) {
                btn.classList.remove('border-slate-200');
                btn.classList.add('border-brand-primary', 'bg-brand-primary/10');
            }
        }
        
        function increaseQty() {
            const input = document.getElementById('quantity');
            input.value = parseInt(input.value) + 1;
        }
        
        function decreaseQty() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
        
        function addProductToCart(productId, quantity) {
            let cart = JSON.parse(localStorage.getItem('cart') || '{"items":[]}');
            
            const variantId = selectedVariant ? selectedVariant.id : null;
            const variantName = selectedVariant ? selectedVariant.name : null;
            const currentImage = document.getElementById('product-image').src;
            
            const cartKey = variantId ? `${productId}_${variantId}` : productId;
            const existingIndex = cart.items.findIndex(item => {
                if (variantId) {
                    return item.product_id === productId && item.variant_id === variantId;
                }
                return item.product_id === productId && !item.variant_id;
            });
            
            if (existingIndex > -1) {
                cart.items[existingIndex].quantity += quantity;
            } else {
                const itemName = variantName 
                    ? `${currentProduct.name} - ${variantName}` 
                    : currentProduct.name;
                    
                cart.items.push({
                    product_id: productId,
                    variant_id: variantId,
                    variant_name: variantName,
                    name: itemName,
                    price: currentProduct.price,
                    image: currentImage,
                    quantity: quantity
                });
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            
            if (window.updateCartBadge) window.updateCartBadge();
            if (window.sendHeartbeat) window.sendHeartbeat();
        }

        function addToCart(e) {
            const btn = document.getElementById('add-to-cart-btn');
            const flyingItem = document.getElementById('flying-item');
            const cartIcon = document.getElementById('header-cart');
            const cartBadge = document.getElementById('cart-badge');
            const qty = parseInt(document.getElementById('quantity').value);
            
            addProductToCart(currentProduct.id, qty);
            
            const btnRect = btn.getBoundingClientRect();
            const cartRect = cartIcon.getBoundingClientRect();
            
            flyingItem.style.left = (btnRect.left + btnRect.width / 2 - 24) + 'px';
            flyingItem.style.top = (btnRect.top + btnRect.height / 2 - 24) + 'px';
            flyingItem.style.opacity = '1';
            flyingItem.style.transform = 'scale(1)';
            flyingItem.style.transition = 'none';
            
            btn.disabled = true;
            btn.innerHTML = '<svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    const deltaX = cartRect.left + cartRect.width / 2 - (btnRect.left + btnRect.width / 2);
                    const deltaY = cartRect.top + cartRect.height / 2 - (btnRect.top + btnRect.height / 2);
                    
                    flyingItem.style.transition = 'all 0.6s cubic-bezier(0.2, 0.8, 0.2, 1)';
                    flyingItem.style.transform = `translate(${deltaX}px, ${deltaY}px) scale(0.3)`;
                    flyingItem.style.opacity = '0.8';
                });
            });
            
            setTimeout(() => {
                flyingItem.style.opacity = '0';
                
                cartIcon.style.transform = 'scale(1.15)';
                setTimeout(() => {
                    cartIcon.style.transform = 'scale(1)';
                }, 150);
                
                cartBadge.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    cartBadge.style.transform = 'scale(1)';
                }, 200);
                
                if (window.animateMobileCart) window.animateMobileCart();
                
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Eklendi</span>';
                btn.classList.remove('bg-brand-primary', 'hover:bg-brand-secondary');
                btn.classList.add('bg-green-500');
            }, 500);
            
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 11 4-7"/><path d="m19 11-4-7"/><path d="M2 11h20"/><path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8c.9 0 1.8-.7 2-1.6l1.7-7.4"/><path d="m9 11 1 9"/><path d="M4.5 15.5h15"/><path d="m15 11-1 9"/></svg><span>Sepete Ekle</span>';
                btn.classList.remove('bg-green-500');
                btn.classList.add('bg-brand-primary', 'hover:bg-brand-secondary');
                
                flyingItem.style.transition = 'none';
                flyingItem.style.transform = 'scale(1)';
            }, 2000);
        }
    </script>
