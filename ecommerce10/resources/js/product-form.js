import Croppie from 'croppie';
import 'croppie/croppie.css';

const MAX_FILE_SIZE = 2 * 1024 * 1024;
const MIN_IMAGE_DIMENSION = 300;
const CROPPED_IMAGE_SIZE = 600;
const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
const MAX_INTEGER = 2147483647;

const form = document.getElementById('product-form');

if (form) {
    initProductForm(form);
}

function initProductForm(form) {
    const imageInput = form.querySelector('#image');
    const cropSection = document.getElementById('crop-section');
    const cropperElement = document.getElementById('image-cropper');
    const cropButton = document.getElementById('crop-image-button');
    const cancelButton = document.getElementById('cancel-crop-button');
    const changeButton = document.getElementById('change-image-button');
    const previewWrapper = document.getElementById('image-preview-wrapper');
    const previewImage = document.getElementById('image-preview');
    const submitButton = form.querySelector('[type="submit"]');
    const imageRequired = imageInput.required;
    const initialPreviewSrc = previewImage.getAttribute('src') || null;

    let croppie = null;
    let croppieReady = null;
    let cropperFailed = false;
    let pendingCrop = false;
    let originalFile = null;
    let previewUrl = null;
    let isSubmitting = false;
    let operationId = 0;

    const rules = {
        name: [
            [(value) => value.trim().length > 0, 'Name is required.'],
            [(value) => value.trim().length >= 3, 'Name must be at least 3 characters.'],
            [(value) => value.trim().length <= 255, 'Name must not be greater than 255 characters.'],
        ],
        description: [
            [(value) => value.trim().length > 0, 'Description is required.'],
            [(value) => value.trim().length >= 3, 'Description must be at least 3 characters.'],
            [(value) => value.trim().length <= 1000, 'Description must not be greater than 1000 characters.'],
        ],
        price: [
            [(value) => value.trim().length > 0, 'Price is required.'],
            [(value) => Number.isFinite(Number(value)) && Number(value) >= 0, 'Price must be 0 or greater.'],
            [(value) => /^\d+$/.test(value.trim()), 'Price must be a whole number.'],
            [(value) => Number(value) <= MAX_INTEGER, 'Price is too large.'],
        ],
        stock: [
            [(value) => value.trim().length > 0, 'Stock is required.'],
            [(value) => Number.isFinite(Number(value)) && Number(value) >= 0, 'Stock must be 0 or greater.'],
            [(value) => /^\d+$/.test(value.trim()), 'Stock must be a whole number.'],
            [(value) => Number(value) <= MAX_INTEGER, 'Stock is too large.'],
        ],
        product_category_id: [
            [(value) => value !== '', 'Category is required.'],
        ],
    };

    Object.keys(rules).forEach((name) => {
        const field = form.elements.namedItem(name);

        if (!field) {
            return;
        }

        ['blur', 'change', 'input'].forEach((eventName) => {
            field.addEventListener(eventName, () => validateField(name));
        });
    });

    imageInput.addEventListener('change', async () => {
        resetCropper();
        hidePreview();

        const file = imageInput.files && imageInput.files[0];

        if (!file) {
            setError('image', imageRequired ? 'Image is required.' : '');
            return;
        }

        const currentOperation = ++operationId;
        const fileError = validateImageFile(file);

        if (fileError) {
            imageInput.value = '';
            setError('image', fileError);
            return;
        }

        try {
            const dataUrl = await readAsDataUrl(file);
            const { width, height } = await getImageDimensions(dataUrl);

            if (currentOperation !== operationId) {
                return;
            }

            if (width < MIN_IMAGE_DIMENSION || height < MIN_IMAGE_DIMENSION) {
                imageInput.value = '';
                setError('image', `Image must be at least ${MIN_IMAGE_DIMENSION} × ${MIN_IMAGE_DIMENSION} pixels.`);
                return;
            }

            originalFile = file;
            setError('image', '');
            showCropper(dataUrl, currentOperation);
        } catch {
            if (currentOperation !== operationId) {
                return;
            }

            imageInput.value = '';
            setError('image', 'The selected image could not be read. Please choose another file.');
        }
    });

    cropButton.addEventListener('click', async () => {
        await applyCrop();
    });

    cancelButton.addEventListener('click', () => {
        imageInput.value = '';
        resetCropper();
        hidePreview();
        setError('image', imageRequired ? 'Image is required.' : '');
    });

    changeButton.addEventListener('click', () => {
        imageInput.value = '';
        resetCropper();
        hidePreview();
        imageInput.click();
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (isSubmitting) {
            return;
        }

        let firstInvalidField = null;

        Object.keys(rules).forEach((name) => {
            if (!validateField(name) && !firstInvalidField) {
                firstInvalidField = form.elements.namedItem(name);
            }
        });

        if (!validateImage() && !firstInvalidField) {
            firstInvalidField = imageInput;
        }

        if (firstInvalidField) {
            firstInvalidField.focus();
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (!(await applyCrop())) {
            return;
        }

        if (!validateImage()) {
            imageInput.focus();
            return;
        }

        isSubmitting = true;

        if (submitButton) {
            submitButton.disabled = true;
        }

        form.submit();
    });

    function validateField(name) {
        const fieldRules = rules[name];

        if (!fieldRules) {
            return true;
        }

        const field = form.elements.namedItem(name);
        const value = field ? String(field.value) : '';

        for (const [isValid, message] of fieldRules) {
            if (!isValid(value)) {
                setError(name, message);
                return false;
            }
        }

        setError(name, '');
        return true;
    }

    function validateImage() {
        if (!imageRequired || (imageInput.files && imageInput.files.length > 0)) {
            setError('image', '');
            return true;
        }

        setError('image', 'Image is required.');
        return false;
    }

    function validateImageFile(file) {
        if (!ALLOWED_IMAGE_TYPES.includes(file.type)) {
            return 'Image must be a JPG, PNG, or WEBP file.';
        }

        if (file.size > MAX_FILE_SIZE) {
            return 'Image must not be larger than 2 MB.';
        }

        return '';
    }

    function setError(name, message) {
        const errorElement = form.querySelector(`[data-error-for="${name}"]`);

        if (!errorElement) {
            return;
        }

        errorElement.textContent = message;
        errorElement.classList.toggle('hidden', message === '');

        const field = form.elements.namedItem(name);

        if (field) {
            field.classList.toggle('border-red-500', message !== '');
            field.classList.toggle('border-gray-300', message === '');
        }
    }

    function showCropper(dataUrl, currentOperation) {
        cropSection.classList.remove('hidden');
        pendingCrop = true;
        cropperFailed = false;

        croppie = new Croppie(cropperElement, {
            viewport: { width: 300, height: 300, type: 'square' },
            boundary: { width: 400, height: 400 },
            enforceBoundary: true,
            enableOrientation: true,
            showZoomer: true,
        });

        croppieReady = croppie.bind({ url: dataUrl }).catch(() => {
            if (currentOperation !== operationId) {
                return;
            }

            cropperFailed = true;
            resetCropper();
            imageInput.value = '';
            setError('image', 'The selected image could not be loaded. Please choose another file.');
        });
    }

    async function applyCrop() {
        if (!pendingCrop) {
            return true;
        }

        if (croppieReady) {
            await croppieReady;
        }

        if (!croppie || cropperFailed) {
            return false;
        }

        try {
            const blob = await croppie.result({
                type: 'blob',
                size: { width: CROPPED_IMAGE_SIZE, height: CROPPED_IMAGE_SIZE },
                format: 'webp',
                quality: 0.9,
                circle: false,
            });

            const croppedFile = new File([blob], croppedFileName(originalFile), {
                type: 'image/webp',
                lastModified: Date.now(),
            });

            replaceSelectedFile(croppedFile);
            showPreview(URL.createObjectURL(blob));
            resetCropper();
            setError('image', '');

            return true;
        } catch {
            setError('image', 'The image could not be cropped. Please try again.');
            return false;
        }
    }

    function resetCropper() {
        operationId++;
        pendingCrop = false;
        cropperFailed = false;
        croppieReady = null;

        if (croppie) {
            croppie.destroy();
            croppie = null;
        }

        cropperElement.innerHTML = '';
        cropSection.classList.add('hidden');
    }

    function replaceSelectedFile(file) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        imageInput.files = dataTransfer.files;
    }

    function showPreview(url) {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }

        previewUrl = url;
        previewImage.src = url;
        previewWrapper.classList.remove('hidden');
    }

    function hidePreview() {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        if (initialPreviewSrc) {
            previewImage.src = initialPreviewSrc;
            previewWrapper.classList.remove('hidden');
            return;
        }

        previewImage.removeAttribute('src');
        previewWrapper.classList.add('hidden');
    }
}

function croppedFileName(file) {
    const baseName = (file && file.name ? file.name : 'image')
        .replace(/\.[^.]+$/, '')
        .replace(/[^\w-]+/g, '-');

    return `${baseName || 'image'}-cropped.webp`;
}

function readAsDataUrl(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.onload = () => resolve(reader.result);
        reader.onerror = () => reject(reader.error);
        reader.readAsDataURL(file);
    });
}

function getImageDimensions(source) {
    return new Promise((resolve, reject) => {
        const image = new Image();

        image.onload = () => resolve({ width: image.naturalWidth, height: image.naturalHeight });
        image.onerror = () => reject(new Error('Unable to load the selected image.'));
        image.src = source;
    });
}
