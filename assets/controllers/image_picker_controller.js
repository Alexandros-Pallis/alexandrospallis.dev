import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['input', 'preview'];
    static values = {
        pickerUrl: String,
        uploadUrl: String,
    };

    connect() {
        this.dialog = null;
        this.searchTimeout = null;
    }

    open(event) {
        event.preventDefault();

        if (!this.dialog) {
            this.dialog = document.createElement('dialog');
            this.dialog.classList.add('image-picker-dialog');
            this.dialog.addEventListener('click', this.onDialogClick.bind(this));
            this.dialog.addEventListener('input', this.onSearchInput.bind(this));
            this.dialog.addEventListener('submit', this.onUploadSubmit.bind(this));
            document.body.appendChild(this.dialog);
        }

        this.dialog.innerHTML = '<p class="p-3">Loading…</p>';
        this.dialog.showModal();
        this.loadGrid(this.pickerUrlValue);
    }

    clear(event) {
        event.preventDefault();
        this.inputTarget.value = '';
        this.renderPreview(null, null);
    }

    onDialogClick(event) {
        if (event.target.closest('[data-image-picker-close]')) {
            this.dialog.close();

            return;
        }

        const item = event.target.closest('[data-image-picker-item]');
        if (item) {
            this.select(item.dataset.imagePickerId, item.dataset.imagePickerUrl, item.dataset.imagePickerLabel);
            this.dialog.close();

            return;
        }

        const pageLink = event.target.closest('[data-image-picker-page]');
        if (pageLink) {
            event.preventDefault();
            this.loadGrid(pageLink.href);
        }
    }

    onSearchInput(event) {
        if (!event.target.matches('[data-image-picker-search]')) {
            return;
        }

        const url = this.pickerUrlValue + '?q=' + encodeURIComponent(event.target.value);

        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => this.loadGrid(url), 300);
    }

    onUploadSubmit(event) {
        if (!event.target.matches('[data-image-picker-upload-form]')) {
            return;
        }

        event.preventDefault();

        fetch(this.uploadUrlValue, {
            method: 'POST',
            body: new FormData(event.target),
        })
            .then((response) => response.json())
            .then((image) => {
                this.select(image.id, image.url, image.label);
                this.dialog.close();
            });
    }

    loadGrid(url) {
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((response) => response.text())
            .then((html) => {
                this.dialog.innerHTML = html;
            });
    }

    select(id, url, label) {
        this.inputTarget.value = id;
        this.renderPreview(url, label);
    }

    renderPreview(url, label) {
        this.previewTarget.replaceChildren();

        if (url) {
            const img = document.createElement('img');
            img.src = url;
            img.alt = label ?? '';
            img.classList.add('image-picker-thumb');
            this.previewTarget.appendChild(img);
        } else {
            const span = document.createElement('span');
            span.classList.add('text-muted');
            span.textContent = 'No image selected';
            this.previewTarget.appendChild(span);
        }
    }
}
