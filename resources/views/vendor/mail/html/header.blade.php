<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === env('APP_NAME'))
        <a href="/"><img src="{{ env('APP_URL') . App\Models\Options::get_option('site_logo') }}"
            class="top-logo" alt="Squad Domains" height="40" /></a>
      @else
        {{ $slot }}
      @endif
    </a>
  </td>
</tr>
