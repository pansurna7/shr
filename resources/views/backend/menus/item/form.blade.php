<div class="row">
    <div class="col-9">
        <div class="row mb-3">
            <label for="type" class="col-sm-3 col-form-label">Type</label>
            <div class="col-sm-9">
                <select name="type" id="type" class="select" onchange="setItemType()">
                    <option value="item" {{ $menuItem->type == 'item' ? 'selected' : '' }}>Menu Item</option>
                    <option value="divider" {{ $menuItem->type == 'divider' ? 'selected' : '' }}>Divider</option>
                </select>
                @error('type')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div id="divider_fields">
            <div class="row mb-3">
                <label for="divider_title" class="col-sm-3 col-form-label">Title Of Divider</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="divider_title" name="divider_title"  value="{{ old('divider_title',$menuItem->divider_title ?? "") }}">
                    @error('divider_title')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div id="item_fields">
            <div class="row mb-3">
                <label for="title" class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="title" name="title"  value="{{ old('title',$menuItem->title ?? "") }}">
                    @error('title')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="url" class="col-sm-3 col-form-label">URL</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="url" name="url"  value="{{ old('url',$menuItem->url ?? "") }}">
                    @error('url')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="target" class="col-sm-3 col-form-label">Open In</label>
                <div class="col-sm-9">
                    <select name="target" id="target" class="select">
                        <option value="_self"  {{ $menuItem->target == '_self' ? 'selected' : '' }}>Same Tab/Window</option>
                        <option value="_blank"  {{ $menuItem->target == '_blank' ? 'selected' : '' }}>New Tabe/Window</option>
                    </select>
                    @error('target')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="icon_class" class="col-sm-3 col-form-label">Font Icon_class</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="icon_class" name="icon_class"  value="{{ old('icon_class',$menuItem->icon_class ?? "") }}">
                    @error('icon_class')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>


