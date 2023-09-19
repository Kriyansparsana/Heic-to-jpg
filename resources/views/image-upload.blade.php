<!DOCTYPE html>
<html>

<head>
    <title>Image Upload</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <h1>Image Upload</h1>

    <form action="{{ route('convert.heic.to.jpeg') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" id="imageInput" accept=".heic" name="heicImage">
        <div id="cropperContainer">
            <img id="cropperImage" src="#" alt="Selected Image">
        </div>
        <button type="submit" id="cropButton">Crop</button>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.11/cropper.min.js"></script>
    <script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            reader.onload = function() {
                const image = new Image();
                image.src = reader.result;

                image.onload = function() {
                    const cropperContainer = document.getElementById('cropperContainer');
                    cropperContainer.innerHTML = '';

                    const cropperImage = document.getElementById('cropperImage');
                    cropperImage.src = image.src;

                    const cropper = new Cropper(cropperImage, {
                        aspectRatio: 1, // Set the aspect ratio as needed
                        viewMode: 1, // Set the view mode as needed
                    });

                    document.getElementById('cropButton').addEventListener('click', function() {
                        const croppedCanvas = cropper.getCroppedCanvas();

                        const croppedImageDataURL = croppedCanvas.toDataURL('image/jpeg');

                        const formData = new FormData();
                        formData.append('heicImage',
                            file);
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');

                        console.log(formData);

                        // Send the form data to the server for HEIC to JPEG conversion
                        fetch("{{ route('convert.heic.to.jpeg') }}", {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const convertedJpegPath = data.convertedJpegPath;
                                // Use the converted JPEG image path for further processing or display
                                // You can update your UI or perform additional actions with the converted image
                                console.log('Converted JPEG Path:', convertedJpegPath);
                            })
                            .catch(error => {
                                console.error('Error converting HEIC to JPEG:', error);
                            });
                    });
                };
            };

            reader.readAsDataURL(file);
        });
    </script>
</body>

</html>
