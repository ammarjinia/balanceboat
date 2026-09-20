{{--
    Bullet list used by most automation emails.

    A table rather than a <ul> because Outlook's list rendering ignores margins and produces
    inconsistent indentation across clients.

    @param array  $items  Plain strings.
    @param string $tone   'default' for a neutral dot, 'missing' for the red marker the
                          "details are missing" checklists use.
--}}
@php
    $tone   = $tone ?? 'default';
    $marker = $tone === 'missing' ? '&#9679;' : '&#9679;';
    $color  = $tone === 'missing' ? '#e11d48' : '#c026d3';
@endphp
<table cellspacing="0" cellpadding="0" border="0" width="100%" style="margin:0 0 16px;">
    @foreach ($items as $item)
        <tr>
            <td valign="top" width="16" style="padding:3px 0 3px 2px; color:{{ $color }}; font-size:12px; line-height:20px;">{!! $marker !!}</td>
            <td valign="top" style="padding:3px 0; font-size:14px; line-height:20px; color:#334155;">{{ $item }}</td>
        </tr>
    @endforeach
</table>
