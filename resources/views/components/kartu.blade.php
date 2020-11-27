<div class="card">
    <!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
    <header class="card-header">
      <p class="card-header-title">
        {!! $title !!}
      </p>
      <a href="#" class="card-header-icon" aria-label="more options">
        <span class="icon">
          <i class="fas fa-angle-down" aria-hidden="true"></i>
        </span>
      </a>
    </header>

    <div class="card-content">
	    {{ $slot }}
	  </div>
</div>