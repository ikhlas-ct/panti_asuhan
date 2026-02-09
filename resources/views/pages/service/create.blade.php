@extends('layouts.user.user')

@section('title', 'Tambah ' . ucfirst($type))

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Tambah {{ ucfirst($type) }}</div>
                </div>
                <div class="card-body">
                    @include('partials.alert.alert')

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('service.store', $type) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @if (in_array($type, ['tema', 'layanan', 'etika', 'keunggulan', 'transportasi']))
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif

                        @if ($type === 'transportasi')
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" name="price" id="price" class="form-control"
                                    value="{{ old('price') }}" required>
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="gambar" class="form-label">Gambar</label>
                                <input type="file" name="gambar" id="gambar" class="form-control"
                                    accept="image/jpeg, image/png, image/gif">
                                <small class="text-muted">Format yang didukung: JPG, PNG, GIF. Ukuran maksimal disarankan 2MB.</small>
                                @error('gambar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif

                        @if (in_array($type, ['tema', 'layanan', 'etika', 'keunggulan', 'informasi']))
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon Kategori</label>
                                <select name="icon" id="icon" class="form-control">
                                    <option value="">Pilih Icon</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id_kategori }}" data-icon="{{ $kategori->icon }}"
                                            {{ old('icon') == $kategori->id_kategori ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('icon')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mt-2 mb-4">
                                <span>Preview Icon: </span>
                                <i id="icon-preview"></i>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="order" class="form-label">Order / Urutan</label>
                            <input type="number" name="order" id="order" class="form-control"
                                value="{{ old('order', 0) }}" min="0">
                            @error('order')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @if (in_array($type, ['tema', 'layanan', 'transportasi']))
                            <h4 class="mb-3 mt-5">Steps (Opsional)</h4>
                            <div id="steps-container">
                                @php
                                    $oldSteps = collect(old('steps'))->sortBy('step_number')->values();
                                    $stepCount = $oldSteps->count();
                                @endphp
                                @foreach ($oldSteps as $loopKey => $oldStep)
                                    @php
                                        $index = $loopKey + 1;
                                    @endphp

                                    <div class="bg-light mb-3 rounded border p-3" id="step-{{ $index }}">
                                        <div class="d-flex justify-content-between mb-2">
                                            <strong>Step {{ $index }}</strong>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="removeStep({{ $index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <input type="hidden" name="steps[{{ $index }}][step_number]"
                                            value="{{ $index }}">

                                        <div class="mb-2">
                                            <label>Title</label>
                                            <input type="text" name="steps[{{ $index }}][title]" class="form-control"
                                                value="{{ $oldStep['title'] ?? '' }}">
                                        </div>

                                        @if ($type !== 'tema')
                                            <div class="mb-2">
                                                <label>Description</label>
                                                <textarea name="steps[{{ $index }}][description]" class="form-control" rows="3">{{ $oldStep['description'] ?? '' }}</textarea>
                                            </div>
                                        @endif

                                        <div class="mb-2">
                                            <label>Icon</label>
                                            <select name="steps[{{ $index }}][icon]" class="form-control select-icon"
                                                data-step-id="{{ $index }}">
                                                <option value="">Pilih Icon</option>
                                                @foreach ($kategoris as $kategori)
                                                    <option value="{{ $kategori->id_kategori }}"
                                                        data-icon="{{ $kategori->icon }}"
                                                        {{ ($oldStep['icon'] ?? '') == $kategori->id_kategori ? 'selected' : '' }}>
                                                        {{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-2">
                                            <span>Preview Icon: </span>
                                            <i id="step-icon-preview-{{ $index }}" class=""></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-secondary mb-4" onclick="addStep()">
                                <i class="fas fa-plus"></i> Tambah Step
                            </button>
                        @endif

                        <div class="text-end">
                            <a href="{{ route('service.index', $type) }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    // Format tampilan option dengan icon
    function formatIcon(option) {
        if (!option.id) return option.text;
        const icon = $(option.element).data('icon');
        return icon
            ? `<span><i class="${icon} me-2"></i> ${option.text}</span>`
            : option.text;
    }

    // Inisialisasi Select2
    function initSelect2(selector) {
        selector.select2({
            width: '100%',
            templateResult: formatIcon,
            templateSelection: formatIcon,
            escapeMarkup: function(m) { return m; }
        });
    }

    // Inisialisasi semua select icon (main + steps)
    function initAllSelect2() {
        initSelect2($('#icon'));
        initSelect2($('.select-icon'));
    }

    $(document).ready(function() {

        // Inisialisasi pertama kali
        initAllSelect2();

        // Preview untuk icon utama
        $('#icon').on('change', function() {
            const iconClass = $(this).find(':selected').data('icon');
            $('#icon-preview').attr('class', iconClass || '');
        });

        // Set preview awal untuk icon utama
        const initialMainIcon = $('#icon').find(':selected').data('icon');
        $('#icon-preview').attr('class', initialMainIcon || '');

        // Preview untuk semua step icon (delegation)
        $(document).on('change', '.select-icon', function() {
            const stepId = $(this).data('step-id');
            if (stepId) {
                const iconClass = $(this).find(':selected').data('icon');
                $(`#step-icon-preview-${stepId}`).attr('class', iconClass || '');
            }
        });

        // Set preview awal untuk step yang sudah ada (dari old input)
        $('.select-icon').each(function() {
            const stepId = $(this).data('step-id');
            if (stepId) {
                const iconClass = $(this).find(':selected').data('icon');
                $(`#step-icon-preview-${stepId}`).attr('class', iconClass || '');
            }
        });
    });

    // ====================== DYNAMIC STEPS ======================
    const pageType = @json($type);
    let stepCount = {{ $stepCount ?? 0 }};

    function getDescriptionHtml(index) {
        if (pageType !== 'tema') {
            return `
                <div class="mb-2">
                    <label>Description</label>
                    <textarea name="steps[${index}][description]" class="form-control" rows="3"></textarea>
                </div>`;
        }
        return '';
    }

    function addStep() {
        stepCount++;

        const html = `
        <div class="border rounded p-3 mb-3 bg-light" id="step-${stepCount}">
            <div class="d-flex justify-content-between mb-2">
                <strong>Step ${stepCount}</strong>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeStep(${stepCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <input type="hidden" name="steps[${stepCount}][step_number]" value="${stepCount}">

            <div class="mb-2">
                <label>Title</label>
                <input type="text" name="steps[${stepCount}][title]" class="form-control">
            </div>

            ${getDescriptionHtml(stepCount)}

            <div class="mb-2">
                <label>Icon</label>
                <select name="steps[${stepCount}][icon]"
                        class="form-control select-icon"
                        data-step-id="${stepCount}">
                    <option value="">Pilih Icon</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id_kategori }}"
                                data-icon="{{ $kategori->icon }}">
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-2">
                <span>Preview Icon: </span>
                <i id="step-icon-preview-${stepCount}" class=""></i>
            </div>
        </div>`;

        $('#steps-container').append(html);

        // Inisialisasi Select2 pada select yang baru ditambahkan
        initSelect2($(`#step-${stepCount} .select-icon`));
    }

    function removeStep(id) {
        $(`#step-${id}`).remove();
    }
</script>
@endsection
