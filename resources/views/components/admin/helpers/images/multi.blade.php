@props(['name' => 'image', 'model' => null , 'attr' => ''  , 'label' => null])

<div class="row">
    <div class="col-12">
        <div class="imgMontg col-12 text-center">
            <div class="dropBox">
                <div class="textCenter">
                    <div class="imagesUploadBlock">
                        <label class="uploadImg">
                            <span><i class="feather icon-image"></i></span>
                            <input type="file" accept="image/*" name="{{ $name }}[]" class="imageUploader" multiple {!! $attr !!}
                                required>
                        </label>
                    </div>
                    <p>{{ $label ?? __("admin.$name") }}</p>
                </div>
            </div>
        </div>

        <div id="previewContainer" class="d-flex flex-wrap">
            @if($model && $model->$name)
                @foreach(json_decode($model->$name, true) as $image)
                    <div class="uploadedBlock existing-image" data-src="{{ $image }}">
                        <img src="{{ $image }}">
                        <button type="button" class="close removeImage"><i class="feather icon-trash-2"></i></button>
                    </div>
                @endforeach
            @endif
        </div>

        <input type="hidden" name="deleted_images" id="deletedImages">
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    let imageUploader = document.querySelector(".imageUploader");
    let previewContainer = document.getElementById("previewContainer");
    let deletedImagesInput = document.getElementById("deletedImages");

    let selectedFiles = [];

    imageUploader.addEventListener("change", function (event) {
        let newFiles = Array.from(event.target.files); // Get newly selected files
        selectedFiles = selectedFiles.concat(newFiles); // Add new files to the existing list

        newFiles.forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function (e) {
                let div = document.createElement("div");
                div.classList.add("uploadedBlock");

                let img = document.createElement("img");
                img.src = e.target.result;

                let button = document.createElement("button");
                button.classList.add("close", "removeImage");
                button.innerHTML = '<i class="feather icon-trash-2"></i>';
                button.dataset.index = selectedFiles.indexOf(file); // Store file index for removal

                button.onclick = function () {
                    let fileIndex = parseInt(this.dataset.index);
                    selectedFiles[fileIndex] = null; // Mark file as removed
                    div.remove();
                };

                div.appendChild(img);
                div.appendChild(button);
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });

    document.addEventListener("click", function (event) {
        if (event.target.closest(".removeImage")) {
            let imageBlock = event.target.closest(".uploadedBlock");

            if (imageBlock.classList.contains("existing-image")) {
                let imageSrc = imageBlock.dataset.src;
                let deletedImages = deletedImagesInput.value ? JSON.parse(deletedImagesInput.value) : [];
                deletedImages.push(imageSrc);
                deletedImagesInput.value = JSON.stringify(deletedImages);
            }

            imageBlock.remove();
        }
    });

    document.querySelector("form").addEventListener("submit", function (event) {
        let newFileList = selectedFiles.filter(file => file !== null); // Remove null entries

        let dataTransfer = new DataTransfer();
        newFileList.forEach(file => dataTransfer.items.add(file));

        imageUploader.files = dataTransfer.files; // Update input with the filtered file list
    });
});
</script>

<style>
    #previewContainer {
        display: block;
        width: 100%;
        height: 200px;
    }

    .uploadedBlock {
        position: relative;
        display: inline-block;
        margin: 5px;
    }

    .uploadedBlock img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .uploadedBlock .close {
        position: absolute;
        top: 5px;
        right: 5px;
        background: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
</style>