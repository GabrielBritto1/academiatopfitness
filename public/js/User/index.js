function confirmarExclusao(event, formulario) {
    event.preventDefault();

    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não poderá ser revertida!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processando...',
                text: 'Aguarde enquanto o usuário é excluído.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            formulario.submit();
        }
    });
}

function initStudentPhotoCapture(config) {
    const fileInput = document.getElementById(config.fileInputId);
    const openCameraButton = document.getElementById(config.openCameraButtonId);
    const captureCameraButton = document.getElementById(config.captureCameraButtonId);
    const closeCameraButton = document.getElementById(config.closeCameraButtonId);
    const cameraWrapper = document.getElementById(config.cameraWrapperId);
    const video = document.getElementById(config.videoId);
    const canvas = document.getElementById(config.canvasId);
    const previewWrapper = document.getElementById(config.previewWrapperId);
    const previewImage = document.getElementById(config.previewImageId);
    const modal = config.modalId ? document.getElementById(config.modalId) : null;

    if (!fileInput || !openCameraButton || !captureCameraButton || !closeCameraButton || !cameraWrapper || !video || !canvas || !previewWrapper || !previewImage) {
        return;
    }

    let mediaStream = null;
    let previewUrl = null;

    const clearPreviewUrl = () => {
        if (!previewUrl) {
            return;
        }

        URL.revokeObjectURL(previewUrl);
        previewUrl = null;
    };

    const setPreview = (src, options = {}) => {
        clearPreviewUrl();

        if (!src) {
            previewImage.src = '';
            previewWrapper.classList.add('d-none');
            return;
        }

        if (options.objectUrl) {
            previewUrl = src;
        }

        previewImage.src = src;
        previewWrapper.classList.remove('d-none');
    };

    const stopCamera = () => {
        if (mediaStream) {
            mediaStream.getTracks().forEach((track) => track.stop());
            mediaStream = null;
        }

        video.srcObject = null;
        cameraWrapper.classList.add('d-none');
        captureCameraButton.classList.add('d-none');
        closeCameraButton.classList.add('d-none');
    };

    const syncPreviewWithFileInput = () => {
        const [file] = fileInput.files || [];

        if (!file) {
            setPreview(null);
            return;
        }

        const filePreviewUrl = URL.createObjectURL(file);
        setPreview(filePreviewUrl, { objectUrl: true });
    };

    const showCameraError = (message) => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Não foi possível abrir a câmera',
                text: message,
            });

            return;
        }

        window.alert(message);
    };

    openCameraButton.addEventListener('click', async () => {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showCameraError('Este navegador não suporta acesso à câmera.');
            return;
        }

        stopCamera();

        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                },
                audio: false,
            });

            video.srcObject = mediaStream;
            cameraWrapper.classList.remove('d-none');
            captureCameraButton.classList.remove('d-none');
            closeCameraButton.classList.remove('d-none');
        } catch (error) {
            const secureContextHint = window.isSecureContext
                ? 'Verifique a permissão de câmera do navegador.'
                : 'A câmera no navegador exige HTTPS ou localhost.';

            showCameraError(`${secureContextHint}`);
        }
    });

    captureCameraButton.addEventListener('click', () => {
        if (!mediaStream) {
            return;
        }

        const width = video.videoWidth || 640;
        const height = video.videoHeight || 480;
        const context = canvas.getContext('2d');

        if (!context) {
            showCameraError('Não foi possível preparar a captura da foto.');
            return;
        }

        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);

        canvas.toBlob((blob) => {
            if (!blob) {
                showCameraError('Não foi possível capturar a foto.');
                return;
            }

            const capturedFile = new File([blob], `aluno-${Date.now()}.jpg`, {
                type: 'image/jpeg',
            });

            if (typeof DataTransfer !== 'undefined') {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(capturedFile);
                fileInput.files = dataTransfer.files;
            } else {
                showCameraError('Seu navegador não permite anexar a foto capturada automaticamente.');
                return;
            }

            syncPreviewWithFileInput();
            stopCamera();
        }, 'image/jpeg', 0.92);
    });

    closeCameraButton.addEventListener('click', () => {
        stopCamera();
    });

    fileInput.addEventListener('change', () => {
        syncPreviewWithFileInput();
    });

    if (modal && window.jQuery) {
        window.jQuery(modal).on('hidden.bs.modal', () => {
            stopCamera();
        });
    }
}

window.initStudentPhotoCapture = initStudentPhotoCapture;

document.addEventListener('DOMContentLoaded', () => {
    initStudentPhotoCapture({
        fileInputId: 'foto',
        openCameraButtonId: 'open-student-camera',
        captureCameraButtonId: 'capture-student-camera',
        closeCameraButtonId: 'close-student-camera',
        cameraWrapperId: 'student-camera-wrapper',
        videoId: 'student-camera-video',
        canvasId: 'student-camera-canvas',
        previewWrapperId: 'student-photo-preview-wrapper',
        previewImageId: 'student-photo-preview',
        modalId: 'modalDefault',
    });
});
