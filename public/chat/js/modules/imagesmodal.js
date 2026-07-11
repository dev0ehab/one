window.onload = function () {
    var modal = document.getElementById("myModal");
    var imgs = document.querySelectorAll(".c-room-container img");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    function createPreview(img) {
        img.onclick = function () {
            modal.style.display = "block";
            modalImg.src = this.src;
            captionText.innerHTML = this.alt;
        }
    }

    imgs.forEach(img => {
        createPreview(img)
    });

    var span = document.getElementsByClassName("close")[0];
    span.onclick = function () {
        modal.style.display = "none";
    }

}

