document.addEventListener("DOMContentLoaded", function () {

    const image = document.getElementById("image");

    if (!image) return;

    image.addEventListener("change", function () {

        const file = this.files[0];
        const preview = document.getElementById("preview");
        const noImage = document.getElementById("noImage");

        if (file) {

            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";
                noImage.style.display = "none";
            };

            reader.readAsDataURL(file);

        } else {

            preview.src = "";
            preview.style.display = "none";
            noImage.style.display = "block";

        }

    });

});
