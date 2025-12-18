@php
    $alertShown = false;
@endphp

@if ($errors->any())
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>There were some problems with your input:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @php $alertShown = true; @endphp
@endif

@if (! $alertShown)
    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @php $alertShown = true; @endphp
    @endif
@endif

@if (! $alertShown)
    @if(session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @php $alertShown = true; @endphp
    @endif
@endif

@if (! $alertShown)
    @if(session('status'))
        <div class="container mt-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @php $alertShown = true; @endphp
    @endif
@endif

@if (! $alertShown)
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @php $alertShown = true; @endphp
    @endif
@endif
