@props(['models' => [], 'label' => null])

@php
    $unique_id = uniqid();
@endphp


<div >
    @if ($label)
        <label>{{ __("admin.$label") }}</label>
    @endif

    <div class="row py-3">
        @foreach ($models ?? [] as $image)
            <div class="col">
                <img src="{{ asset($image) }}" alt="Order Image"
                    class="img-thumbnail img-thumbnail-{{ $unique_id }} preview-image-{{ $unique_id }}"
                    style="width: 200px; height: 200px; object-fit: cover; cursor: pointer;">
            </div>
        @endforeach

    </div>

</div>
<!-- Modal for Image Preview -->
<div id="imageModal-{{ $unique_id }}" class="modal">
    <div class="modal-content">
        <span class="close close-{{ $unique_id }}">&times;</span>
        <img id="modalImg-{{ $unique_id }}">
    </div>
</div>

<!-- Styles -->
<style>
    /* Small image styling */
    .img-thumbnail {
        transition: transform 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.1);
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent background */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Centered Modal Box */
    .modal-content {
        background: white;
        padding: 15px;
        border-radius: 8px;
        max-width: 500px;
        text-align: center;
        position: relative;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Image inside modal */
    .modal-content img {
        max-width: 100%;
        max-height: 400px;
        border-radius: 5px;
    }

    /* Close button */
    .close {
        position: absolute;
        top: 5px;
        right: 10px;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        background: red;
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Fade-in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>


@push('js')
    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            unique = @json($unique_id)

            let modal = document.getElementById("imageModal-" + unique);
            let modalImg = document.getElementById("modalImg-" + unique);
            let closeBtn = document.querySelector(".close-" + unique);

            console.log(modal, modalImg, closeBtn);

            // Ensure modal is hidden on page load
            modal.style.display = "none";

            document.querySelectorAll(".preview-image-" + unique).forEach(img => {
                img.addEventListener("click", function() {
                    modal.style.display = "flex"; // Show the modal when an image is clicked
                    modalImg.src = this.src;
                });
            });

            closeBtn.addEventListener("click", function(e) {
                console.log(e.target);

                modal.style.display = "none";
            });

            modal.addEventListener("click", function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        });
    </script>
@endpush
