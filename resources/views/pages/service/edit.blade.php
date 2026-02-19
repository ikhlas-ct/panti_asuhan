{{-- resources/views/pages/service/edit.blade.php --}}
@extends('layouts.user.user')

@section('title', 'Edit ' . ucfirst($type))

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Edit {{ ucfirst($type) }}</div>
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

                    <form action="{{ route('service.update', [$type, $service->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ old('title', $service->title) }}" required>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @if (in_array($type, ['tema', 'layanan', 'etika', 'keunggulan', 'transportasi']))
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $service->description) }}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif

                        @if ($type === 'transportasi')
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" name="price" id="price" class="form-control"
                                    value="{{ old('price', $service->price) }}" required>
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Bagian Gambar -->
                            <div class="mb-4">
                                <label class="form-label">Gambar Saat Ini</label>
                                @if ($service->gambar)
                                    <div class="mb-3">
                                        <img src="{{ asset( $service->gambar) }}" alt="Current Image"
                                            class="img-thumbnail" style="max-height: 200px; object-fit: cover;">
                                        <small class="d-block text-muted mt-1">Gambar lama. Biarkan kosong jika tidak ingin mengganti.</small>
                                    </div>
                                @else
                                    <p class="text-muted">Belum ada gambar.</p>
                                @endif

                                <label for="gambar" class="form-label mt-2">Ganti Gambar (Opsional)</label>
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
                                            {{ old('icon', $service->icon) == $kategori->id_kategori ? 'selected' : '' }}>
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
                                <i id="icon-preview" class="{{ old('icon') ? ($kategoris->firstWhere('id_kategori', old('icon', $service->icon))->icon ?? '') : (optional($service->kategori)->icon ?? '') }}"></i>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="order" class="form-label">Order / Urutan</label>
                            <input type="number" name="order" id="order" class="form-control"
                                value="{{ old('order', $service->order) }}" min="0">
                            @error('order')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @if (in_array($type, ['tema', 'layanan', 'transportasi']))
                            <h4 class="mb-3 mt-5">Steps (Opsional)</h4>
                            <div id="steps-container">
                                @php
                                    if (old('steps')) {
                                        $steps = collect(old('steps'))->sortBy('step_number')->values();
                                    } else {
                                        $steps = $service->steps->sortBy('step_number');
                                    }
                                    $stepCount = $steps->count();
                                @endphp
                                @foreach ($steps as $loopKey => $step)
                                    @php
                                        $index = $loopKey + 1;
                                        $isOld = !is_object($step);
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
                                                value="{{ $isOld ? ($step['title'] ?? '') : old("steps.$index.title", $step->title) }}">
                                        </div>

                                        <div class="mb-2">
                                            <label>Icon</label>
                                            <select name="steps[{{ $index }}][icon]" class="form-control select-icon"
                                                data-step-id="{{ $index }}">
                                                <option value="">Pilih Icon</option>
                                                @foreach ($kategoris as $kategori)
                                                    <option value="{{ $kategori->id_kategori }}"
                                                        data-icon="{{ $kategori->icon }}"
                                                        {{ $isOld ? (($step['icon'] ?? '') == $kategori->id_kategori ? 'selected' : '') : (old("steps.$index.icon", $step->icon) == $kategori->id_kategori ? 'selected' : '') }}>
                                                        {{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mt-2">
                                            <span>Preview Icon: </span>
                                            <i id="step-icon-preview-{{ $index }}"
                                                class="{{ $isOld ? ($kategoris->firstWhere('id_kategori', $step['icon'] ?? '')?->icon ?? '') : (optional($step->kategori)->icon ?? '') }}"></i>
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
                            <button type="submit" class="btn btn-success">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatIcon(option) {
            if (!option.id) return option.text;
            const icon = $(option.element).data('icon');
            return `<span><i class="${icon} me-2"></i> ${option.text}</span>`;
        }

        function initSelect2(selector) {
            selector.select2({
                width: '100%',
                templateResult: formatIcon,
                templateSelection: formatIcon,
                escapeMarkup: m => m
            });
        }

        $(document).ready(function() {
            initSelect2($('#icon'));
            initSelect2($('.select-icon'));

            $('#icon').on('change select2:select', function() {
                const icon = $(this).find(':selected').data('icon');
                $('#icon-preview').attr('class', icon ?? '');
            });

            // Set initial preview on load for main icon
            const initialIcon = $('#icon').find(':selected').data('icon');
            $('#icon-preview').attr('class', initialIcon || '');

            // Handle step icon changes and initial previews
            $('.select-icon').each(function() {
                const stepId = $(this).data('step-id');
                if (stepId) {
                    // Set initial preview for existing steps
                    const initialStepIcon = $(this).find(':selected').data('icon');
                    $('#step-icon-preview-' + stepId).attr('class', initialStepIcon || '');
                }
            });

            $(document).on('change select2:select', '.select-icon', function() {
                const stepId = $(this).data('step-id');
                if (stepId) {
                    const icon = $(this).find(':selected').data('icon');
                    $('#step-icon-preview-' + stepId).attr('class', icon ?? '');
                }
            });
        });
    </script>


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

    // Inisialisasi semua select icon
    function initAllSelect2() {
        initSelect2($('#icon'));
        initSelect2($('.select-icon'));
    }

    $(document).ready(function() {

        // Inisialisasi Select2 saat pertama load
        initAllSelect2();

        // Preview Icon Utama
        $('#icon').on('change', function() {
            const iconClass = $(this).find(':selected').data('icon');
            $('#icon-preview').attr('class', iconClass || '');
        });

        // Set preview awal icon utama
        const initialMainIcon = $('#icon').find(':selected').data('icon');
        $('#icon-preview').attr('class', initialMainIcon || '');

        // Preview Icon untuk Step (delegation - penting untuk step dinamis)
        $(document).on('change', '.select-icon', function() {
            const stepId = $(this).data('step-id');
            if (stepId) {
                const iconClass = $(this).find(':selected').data('icon');
                $(`#step-icon-preview-${stepId}`).attr('class', iconClass || '');
            }
        });

        // Set preview awal untuk semua step yang sudah ada
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

        // Inisialisasi Select2 pada select baru
        initSelect2($(`#step-${stepCount} .select-icon`));
    }

    function removeStep(id) {
        $(`#step-${id}`).remove();
    }
</script>
@endsection
