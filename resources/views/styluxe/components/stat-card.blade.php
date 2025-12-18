<div class="stat-card {{ $variant ?? '' }}">
    <span class="stat-emoji">{{ $emoji }}</span>
    <h3 class="stat-title">{{ $title }}</h3>
    <p class="stat-value">{{ $value }}</p>
    @if(isset($change))
    <span class="stat-change {{ $change >= 0 ? 'positive' : 'negative' }}">
        {{ $change >= 0 ? '↑' : '↓' }} {{ abs($change) }}%
    </span>
    @endif
</div>