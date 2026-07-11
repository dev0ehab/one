@props(['name', 'multi' => false, 'model' => null, 'items' => [], 'label' => null , 'attr' => '' , 'value' => null])

@php
    $value = $model?->$name ?? null;
@endphp

<div class="form-group col-md-6">
    <div class="controls">
        <label>{{ $label ?? __("admin.$name")}}</label>
        <select {!! $attr !!} class="form-control mb-3 select2" name="{{ $name }}" @mutiple($multi)>
            <option value>{{__('admin.select_one')}}</option>
            @foreach ($items as $id => $name)
                <option value="{{ $id }}" @selected($id == $value) >{{ $name }}</option>
            @endforeach

        </select>
    </div>
</div>