@php
$colors = [
    'admin' => 'badge-admin',
    'client' => 'badge-client'
];
@endphp

<span class="role-badge {{ $colors[$role] ?? 'badge-client' }}">
    {{ ucfirst($role) }}
</span>