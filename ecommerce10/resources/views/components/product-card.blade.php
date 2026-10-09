<div class="card product-card h-100">
  <a href="{{ $link }}" class="product-card-media">
    <img src="{{ $image }}" class="product-card-image" alt="{{ $name }}">
  </a>
  <div class="card-body d-flex flex-column">
    @if ($category)
      <span class="badge product-card-category align-self-start mb-2">{{ $category }}</span>
    @endif

    <h5 class="card-title product-card-title">{{ $name }}</h5>

    <p class="card-text product-card-description flex-grow-1">{{ $description }}</p>

    @if (! is_null($price))
      <div class="product-card-price">Rp {{ number_format($price, 0, ',', '.') }}</div>
    @endif

    <a href="{{ $link }}" class="btn product-card-button w-100 mt-3">{{ $buttonLabel }}</a>
  </div>
</div>