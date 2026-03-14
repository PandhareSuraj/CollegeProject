@props([
    'view' => null,
    'edit' => null,
    'delete' => null,
    'deleteMessage' => 'Are you sure?',
    'deleteRoute' => null,
])

<td class="px-6 py-4 flex gap-2 flex-wrap items-center">
    @if($view)
        <a href="{{ $view }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition" title="View">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                <path fill-rule="evenodd" d="M0 10s3-5.5 10-5.5S20 10 20 10s-3 5.5-10 5.5S0 10 0 10zm10-8c-4.41 0-8 2.908-8 6.5S5.59 18 10 18s8-2.908 8-6.5S14.41 2 10 2z" clip-rule="evenodd" />
            </svg>
        </a>
    @endif

    @if($edit)
        <a href="{{ $edit }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition" title="Edit">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
        </a>
    @endif

    @if($deleteRoute)
        <form action="{{ $deleteRoute }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition" title="Delete" onclick="return confirm('{{ $deleteMessage }}')">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>
    @elseif($delete)
        <form action="{{ $delete }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition" title="Delete" onclick="return confirm('{{ $deleteMessage }}')">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </form>
    @endif
</td>
