@if ($paginator->hasPages())
<nav class="hf-pagination" aria-label="Pagination">

    {{-- Résumé --}}
    <span class="hf-pagination__summary">
        {{ $paginator->firstItem() }} – {{ $paginator->lastItem() }}
        <span class="hf-pagination__summary-total">sur {{ $paginator->total() }} hébergements</span>
    </span>

    <div class="hf-pagination__controls">

        {{-- ← Précédent --}}
        @if ($paginator->onFirstPage())
            <span class="hf-pagination__btn hf-pagination__btn--disabled" aria-disabled="true">
                &#8592; Précédent
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="hf-pagination__btn hf-pagination__btn--nav" rel="prev">
                &#8592; Précédent
            </a>
        @endif

        {{-- Pages --}}
        <div class="hf-pagination__pages">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="hf-pagination__ellipsis">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="hf-pagination__page hf-pagination__page--active" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="hf-pagination__page">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Suivant → --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="hf-pagination__btn hf-pagination__btn--nav" rel="next">
                Suivant &#8594;
            </a>
        @else
            <span class="hf-pagination__btn hf-pagination__btn--disabled" aria-disabled="true">
                Suivant &#8594;
            </span>
        @endif

    </div>
</nav>

<style>
.hf-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 2.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border, #DDD6CA);
}

.hf-pagination__summary {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.875rem;
    color: var(--gray, #6B6B7A);
    letter-spacing: 0.01em;
}

.hf-pagination__summary-total {
    color: var(--lgray, #A0A0B0);
}

.hf-pagination__controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Pages numérotées */
.hf-pagination__pages {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    margin: 0 0.5rem;
}

.hf-pagination__page {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    height: 2.25rem;
    padding: 0 0.5rem;
    border-radius: 8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--ink, #2E2E3A);
    background: transparent;
    border: 1.5px solid var(--border, #DDD6CA);
    text-decoration: none;
    transition: all 0.18s ease;
    cursor: pointer;
}

.hf-pagination__page:hover {
    background: var(--terra-soft, #FEF3E2);
    border-color: var(--terra, #C8602A);
    color: var(--terra, #C8602A);
}

.hf-pagination__page--active {
    background: var(--terra, #C8602A);
    border-color: var(--terra, #C8602A);
    color: #fff;
    font-weight: 600;
    cursor: default;
    box-shadow: 0 2px 8px rgba(200, 96, 42, 0.30);
}

.hf-pagination__ellipsis {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 2.25rem;
    height: 2.25rem;
    color: var(--lgray, #A0A0B0);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem;
}

/* Boutons Précédent / Suivant */
.hf-pagination__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 1.1rem;
    border-radius: 8px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.18s ease;
}

.hf-pagination__btn--nav {
    color: var(--terra, #C8602A);
    background: var(--terra-soft, #FEF3E2);
    border: 1.5px solid rgba(200, 96, 42, 0.25);
}

.hf-pagination__btn--nav:hover {
    background: var(--terra, #C8602A);
    color: #fff;
    border-color: var(--terra, #C8602A);
    box-shadow: 0 2px 8px rgba(200, 96, 42, 0.28);
}

.hf-pagination__btn--disabled {
    color: var(--lgray, #A0A0B0);
    background: var(--sand, #F5EFE6);
    border: 1.5px solid var(--border, #DDD6CA);
    cursor: not-allowed;
    opacity: 0.6;
}

/* Mobile */
@media (max-width: 640px) {
    .hf-pagination {
        flex-direction: column;
        align-items: center;
    }
    .hf-pagination__controls {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>
@endif