
@php
    $statuses = [
        'pending' => [
            'label' => 'Chờ duyệt',
            'class' => 'bg-warning text-dark'
        ],
        'approved' => [
            'label' => 'Đã duyệt',
            'class' => 'bg-success'
        ],
        'rejected' => [
            'label' => 'Bị từ chối',
            'class' => 'bg-danger'
        ],
        'expired' => [
            'label' => 'Hết hạn',
            'class' => 'bg-secondary'
        ],
    ];

    $badge = $statuses[$status] ?? [
        'label' => $status,
        'class' => 'bg-secondary'
    ];
@endphp

<span class="badge {{ $badge['class'] }}">
    {{ $badge['label'] }}
</span>
