<div class="d-flex mb-3 gap-2">
    @foreach ($filters as $key => $values)
        <div class="d-flex flex-column">
            <strong>{{ ucfirst($key) }}:</strong>
            <select class="form-select select-filter" name="{{ $key }}">
                <option value="">Semua</option>
                @foreach ($values as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    @endforeach
</div>
