@props([
    'bg' => 'primary',
    'class' => '',
])

{{--
  theme-container handles:
  - margin-left: 16rem  (pushes content past the fixed sidebar)
  - padding-top: 4rem   (pushes content below the fixed navbar)
  - min-height: 100vh   (fills full viewport height — no white gap)
  - background: var(--theme-bg-primary) — matches theme
--}}
<div class="theme-container {{ $class }}">
    {{ $slot }}
</div>