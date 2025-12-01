<div class="row p-3">
    <div class="col d-flex flex-column flex-md-row justify-content-between gap-3">

        {{-- FILTER SECTION --}}
        <div class="d-flex mb-3 gap-2">
            @foreach ($filters as $key => $values)
                <div class="d-flex flex-column">
                    <strong>{{ ucfirst($key) }}:</strong>
                    <select class="form-select select-filter" name="{{ str_replace(' ', '_', $key) }}">
                        <option value="">Semua</option>
                        @foreach ($values as $value)
                            <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>

        {{-- SEARCH SECTION --}}
        <form action="{{ $route }}" method="GET" id="search-form">

            {{-- Preserve all query params except "search" --}}
            @foreach (request()->except('search') as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach

            <div class="d-flex flex-md-column align-items-center align-items-md-start flex-row gap-2">
                <strong>Cari:</strong>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Cari produk..." name="search"
                        value="{{ request('search') }}" width="300">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    const selectFilters = document.querySelectorAll('.select-filter');

    // Set select defaults based on URL
    (function applySelectDefaults() {
        const url = new URL(window.location.href);
        selectFilters.forEach(select => {
            const paramValue = url.searchParams.get(select.name);
            if (paramValue !== null) {
                const optionExists = Array.from(select.options).some(opt => opt.value === paramValue);
                if (optionExists) select.value = paramValue;
            }
        });
    })();

    // When a filter is changed → update URL + reset page
    selectFilters.forEach(selectFilter => {
        selectFilter.addEventListener('change', function() {
            const url = new URL(window.location.href);

            // Update specific filter
            url.searchParams.set(this.name, this.value);

            // Keep search text
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput && searchInput.value) {
                url.searchParams.set('search', searchInput.value);
            }

            // Reset page number
            url.searchParams.delete('page');

            // Redirect
            window.location.href = url.toString();
        });
    });

    // When search is submitted → remove page=2
    document.getElementById('search-form')?.addEventListener('submit', function() {
        const url = new URL(window.location.href);
        url.searchParams.delete('page');

        // Put cleaned URL into form action
        this.action = url.pathname + "?" + url.searchParams.toString();
    });
</script>
