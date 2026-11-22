@if ($paginator->hasPages())
    <div class="pagination">
        <div class="muted">
            Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} registros
        </div>
        <div class="links">
            @if ($paginator->onFirstPage())
                <span class="current" style="background:#e5e7eb;color:#6b7280;border-color:#e5e7eb;">Anterior</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}">Anterior</a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page === $paginator->currentPage())
                    <span class="current">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}">Siguiente</a>
            @else
                <span class="current" style="background:#e5e7eb;color:#6b7280;border-color:#e5e7eb;">Siguiente</span>
            @endif
        </div>
    </div>
@endif
