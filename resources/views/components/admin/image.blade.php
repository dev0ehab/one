@props(['name' => 'image', 'multi' => false, 'model' => null, 'show' => false, 'label' => null, 'attr' => ''])

@if ($show)

    <x-admin.helpers.images.show :models="is_array($model) ? $model : [$model]" :label="$label" :attr="$attr" />

@else

    @if($multi)
        <x-admin.helpers.images.multi :name="$name" :model="$model" :label="$label" :attr="$attr" />

    @else

        <x-admin.helpers.images.single :name="$name" :model="$model" :label="$label" :attr="$attr" />

    @endif

@endif
