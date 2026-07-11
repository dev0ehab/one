@props(['label' => null, 'name' => 'image', 'model' => null, 'attr' => ''])

<div class="col">
    <div class="imgMontg text-center">
        <div class="dropBox">
            <div class="textCenter">
                <div class="imagesUploadBlock">


                    @if ($model)
                        <label class="uploadImg">
                            <span><i class="feather icon-image"></i></span>
                            <input type="file" accept="image/*" name="{{ $name }}" class="imageUploader" {!! $attr !!}>
                        </label>
                        <div class="uploadedBlock">
                            <img src="{{ $model?->$name }}">
                            <button class="close"><i class="feather icon-trash-2"></i></button>
                        </div>

                    @else
                        <label class="uploadImg">
                            <span><i class="feather icon-image"></i></span>
                            <input type="file" accept="image/*" name="{{ $name }}" class="imageUploader" {!! $attr !!}>
                        </label>
                    @endif


                </div>
                <p>{{ $label ?? __("admin.$name") }}</p>
            </div>
        </div>
    </div>
</div>