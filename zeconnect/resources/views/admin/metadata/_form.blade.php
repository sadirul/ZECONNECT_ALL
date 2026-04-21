@csrf
@php($metadata = $metadata ?? null)
@php($selectedInputType = old('input_type', $metadata->input_type ?? 'text'))
@php($valueInput = old('value', $metadata->value ?? ''))

<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Key</label>
        <input
            type="text"
            name="key"
            value="{{ old('key', $metadata->key ?? '') }}"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none"
        >
        @error('key')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Input Type</label>
        <select
            id="metadata-input-type"
            name="input_type"
            required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none"
        >
            @foreach ($inputTypes as $type => $label)
                <option value="{{ $type }}" {{ $selectedInputType === $type ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('input_type')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-slate-700">Value</label>
        <div id="metadata-value-field"></div>
        @error('value')
            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mt-6 flex items-center gap-2">
    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
        {{ $submitLabel ?? 'Save Metadata' }}
    </button>
    <a href="{{ route('admin.metadata.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
        Cancel
    </a>
</div>

<script>
    const metadataInputType = document.getElementById('metadata-input-type');
    const metadataValueField = document.getElementById('metadata-value-field');
    const initialValue = @json((string) $valueInput);

    const fieldClasses = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-400 focus:outline-none';

    const escapeHtml = (unsafe) => unsafe
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const renderValueInput = (type, value = '') => {
        const safeValue = String(value ?? '');
        const escapedValue = escapeHtml(safeValue);

        if (type === 'textarea') {
            metadataValueField.innerHTML = `<textarea name="value" rows="5" class="${fieldClasses}">${escapedValue}</textarea>`;
            return;
        }

        if (type === 'boolean') {
            const normalized = ['1', 'true', 'yes', 'on'].includes(safeValue.toLowerCase()) ? '1' : '0';
            metadataValueField.innerHTML = `
                <select name="value" class="${fieldClasses}">
                    <option value="1" ${normalized === '1' ? 'selected' : ''}>Yes</option>
                    <option value="0" ${normalized === '0' ? 'selected' : ''}>No</option>
                </select>
            `;
            return;
        }

        const htmlInputTypeMap = {
            text: 'text',
            number: 'number',
            email: 'email',
            url: 'url',
            date: 'date',
            time: 'time',
            datetime: 'datetime-local',
            color: 'color',
            tel: 'tel',
            search: 'search',
        };

        const htmlType = htmlInputTypeMap[type] ?? 'text';
        const extraAttrs = htmlType === 'number' ? 'step="any"' : '';

        metadataValueField.innerHTML = `<input type="${htmlType}" name="value" value="${escapedValue}" ${extraAttrs} class="${fieldClasses}">`;
    };

    renderValueInput(metadataInputType.value, initialValue);

    metadataInputType.addEventListener('change', () => {
        const currentValueInput = metadataValueField.querySelector('[name="value"]');
        const currentValue = currentValueInput ? currentValueInput.value : '';
        renderValueInput(metadataInputType.value, currentValue);
    });
</script>
