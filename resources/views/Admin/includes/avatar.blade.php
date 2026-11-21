@php($avatarUrl = $avatarUser->adminAvatarUrl())
<span data-admin-avatar="{{ $variant }}"
      class="rounded-circle d-inline-flex align-items-center justify-content-center overflow-hidden bg-secondary-subtle {{ $variant === 'preview' ? 'mb-3' : 'ms-1' }}"
      style="width:{{ $size }}px;height:{{ $size }}px" role="img" aria-label="{{ $avatarUser->name ?? 'Administrator' }}">
  <span @class(['d-none' => $avatarUrl])>{{ mb_strtoupper(mb_substr($avatarUser->name ?: 'Admin', 0, 1)) }}</span>
  <img @if($avatarUrl) src="{{ $avatarUrl }}" @endif @class(['w-100 h-100 object-fit-cover', 'd-none' => !$avatarUrl]) alt="">
</span>
