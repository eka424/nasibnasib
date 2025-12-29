@csrf
@if(isset($artikel) && $artikel->exists)
    @method('PUT')
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Judul</label>
        <input type="text" name="title" value="{{ old('title', $artikel->title ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $artikel->slug ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Konten</label>
        <textarea name="content" rows="6" class="mt-1 w-full rounded border-gray-300" required>{{ old('content', $artikel->content ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Thumbnail</label>
        <input type="file" name="thumbnail" class="mt-1 w-full rounded border-gray-300">
        @if(isset($artikel) && $artikel->thumbnail)
            <p class="text-sm text-gray-500 mt-1">Saat ini: {{ $artikel->thumbnail }}</p>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1 w-full rounded border-gray-300" required>
            @foreach (['draft' => 'Draft', 'published' => 'Published'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $artikel->status ?? 'draft') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>
