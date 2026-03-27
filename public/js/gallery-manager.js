/*
|--------------------------------------------------------------------------
| Laravel Gallery - Admin Image Manager
|--------------------------------------------------------------------------
|
| Handles the gallery administration interface:
| - image uploads (Uppy)
| - drag & drop sorting
| - metadata editing
| - image deletion
| - toast notifications
|
| Package: taki47/laravel-gallery
| Author: Lajos Takács
| Website: https://takiwebneked.hu
| Repository: https://github.com/taki47/laravel-gallery
|
*/

(function () {
    /* ======================================
       Root element lookup
    ====================================== */
    const root = document.getElementById('laravel-gallery-manager');

    if (!root) {
        return;
    }

    /* ======================================
       Translations from global scope
    ====================================== */
    const translations = window.GalleryLang ?? {};

    /* ======================================
       Vue helpers
    ====================================== */
    const {
        createApp,
        nextTick
    } = Vue;

    createApp({
        data() {
            return {
                /* ======================================
                   Configuration from data attributes
                ====================================== */
                loadUrl: root.dataset.loadUrl,
                storeUrl: root.dataset.storeUrl,
                sortUrl: root.dataset.sortUrl,
                backUrl: root.dataset.backUrl,
                csrf: root.dataset.csrf,
                lang: translations,

                /* ======================================
                   Component state
                ====================================== */
                images: [],
                loading: false,
                savingOrder: false,
                saveTimers: {},
                uppy: null,
                
                /* ======================================
                   Toast notification state
                ====================================== */
                toast: {
                    visible: false,
                    message: '',
                    type: 'success',
                },
                toastTimeout: null,

                /* ======================================
                   Confirmation dialog state
                ====================================== */
                confirmDialog: {
                    visible: false,
                    message: "",
                    onConfirm: null
                }
            };
        },

        mounted() {
            /* ======================================
               Initial component setup
            ====================================== */
            this.loadImages();
            this.initUppy();
        },

        methods: {
            /* ======================================
               Back navigation
            ====================================== */
            goBack() {
                window.location.href = this.backUrl;
            },

            /* ======================================
               Confirmation dialog handlers
            ====================================== */
            confirmAction(message, callback) {
                this.confirmDialog.message = message;
                this.confirmDialog.onConfirm = callback;
                this.confirmDialog.visible = true;
            },

            confirmOk() {
                if ( this.confirmDialog.onConfirm )
                    this.confirmDialog.onConfirm();

                this.confirmDialog.visible = false;
            },

            confirmCancel() {
                this.confirmDialog.visible = false;
            },

            /* ======================================
               Toast notification handler
            ====================================== */
            showToast(message, type = 'success' ) {
                if ( this.toastTimeout )
                    clearTimeout(this.toastTimeout);

                this.toast.message = message;
                this.toast.type = type;
                this.toast.visible = true;

                this.toastTimeout = setTimeout(() => {
                    this.toast.visible = false;
                }, 3000);
            },

            /* ======================================
               Load gallery images from backend
            ====================================== */
            async loadImages() {
                this.loading = true;

                try {
                    const response = await fetch(this.loadUrl, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error(this.lang.messages.image.load_error);
                    }

                    const data = await response.json();

                    this.images = data.map(image => ({
                        ...image,
                        isSaving: false,
                        isDeleting: false,
                        error: null,
                    }));

                    await nextTick();
                    this.initSortable();
                } catch (error) {
                    console.error(error);
                    const errorTxt = error.message || this.lang.messages.error;
                    this.showToast(errorTxt, 'error');
                } finally {
                    this.loading = false;
                }
            },

            /* ======================================
               Initialize Uppy uploader
            ====================================== */
            initUppy() {
                this.uppy = new Uppy.Uppy({
                    autoProceed: true,
                    restrictions: {
                        allowedFileTypes: ['image/*']
                    }
                });

                this.uppy.use(Uppy.Dashboard, {
                    inline: true,
                    target: '#uppy-dashboard',
                    proudlyDisplayPoweredByUppy: false,
                    note: this.lang.messages.image.upload_hint,
                    height: 320,
                });

                this.uppy.use(Uppy.XHRUpload, {
                    endpoint: this.storeUrl,
                    fieldName: 'image',
                    formData: true,
                    headers: {
                        'X-CSRF-TOKEN': this.csrf,
                        'Accept': 'application/json'
                    }
                });

                this.uppy.on('upload-success', async () => {
                    await this.loadImages();
                });

                this.uppy.on('upload-error', (file, error) => {
                    console.error('Upload error:', file, error);
                });
            },

            /* ======================================
               Initialize drag and drop sorting
            ====================================== */
            initSortable() {
                const grid = this.$refs.imagesGrid;

                if (!grid || grid.dataset.sortableInitialized === '1') {
                    return;
                }

                Sortable.create(grid, {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: async () => {
                        const reorderedIds = Array.from(grid.querySelectorAll('[data-id]'))
                            .map(el => Number(el.dataset.id));

                        this.images.sort((a, b) => {
                            return reorderedIds.indexOf(a.id) - reorderedIds.indexOf(b.id);
                        });

                        await this.saveOrder();
                    }
                });

                grid.dataset.sortableInitialized = '1';
            },

            /* ======================================
               Persist new image order
            ====================================== */
            async saveOrder() {
                this.savingOrder = true;

                try {
                    const payload = {
                        items: this.images.map((image, index) => ({
                            id: image.id,
                            sort_order: index + 1
                        }))
                    };

                    const response = await fetch(this.sortUrl, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(this.lang.messages.sort.error);
                    }

                    this.showToast(result.message);
                } catch (error) {
                    console.error(error);
                    const errorTxt = error.message || this.lang.messages.sort.error;
                    this.showToast(errorTxt, 'error');
                } finally {
                    this.savingOrder = false;
                }
            },

            /* ======================================
               Queue metadata save with debounce
            ====================================== */
            queueSave(image) {
                if (this.saveTimers[image.id]) {
                    clearTimeout(this.saveTimers[image.id]);
                }

                this.saveTimers[image.id] = setTimeout(() => {
                    this.saveImage(image);
                }, 700);
            },

            /* ======================================
               Save image metadata
            ====================================== */
            async saveImage(image) {
                image.isSaving = true;
                image.error = null;
                
                try {
                    const response = await fetch(image.update_url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf
                        },
                        body: JSON.stringify({
                            caption: image.caption,
                            alt: image.alt
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(this.lang.messages.image.update_error);
                    }

                    this.showToast(result.message || this.lang.messages.image.update_error);
                } catch (error) {
                    console.error(error);
                    const errorTxt = error.message || this.lang.messages.image.update_error;
                    this.showToast(errorTxt, 'error');
                } finally {
                    image.isSaving = false;
                }
            },

            /* ======================================
               Start delete flow with confirmation
            ====================================== */
            deleteImage(image) {
                this.confirmAction(this.lang.admin.confirm.delete_image, () => {
                    this.performDelete(image);
                });
            },

            /* ======================================
               Delete image from backend
            ====================================== */
            async performDelete(image) {
                image.isDeleting = true;
                image.error = null;

                try {
                    const response = await fetch(image.delete_url, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf
                        }
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(this.lang.messages.image.delete_error);
                    }

                    this.images = this.images.filter(item => item.id !== image.id);
                    this.showToast(result.message || this.lang.messages.image.delete_error);
                } catch (error) {
                    console.error(error);
                    image.error = error.message || this.lang.messages.image.delete_error;
                    this.showToast(image.error, 'error');
                } finally {
                    image.isDeleting = false;
                }
            }
        },

        /* ======================================
           Inline Vue template
        ====================================== */
        template: `
            <div class="gallery-manager-wrapper">
                <transition name="modal-fade">
                    <div v-if="confirmDialog.visible" class="gallery-modal-overlay">

                        <div class="gallery-modal">

                            <div class="gallery-modal-message">
                                {{ confirmDialog.message }}
                            </div>

                            <div class="gallery-modal-actions">

                                <button class="modal-cancel"
                                        @click="confirmCancel">
                                    {{ lang.admin.buttons.cancel }}
                                </button>

                                <button class="modal-confirm"
                                        @click="confirmOk">
                                    {{ lang.admin.buttons.confirm }}
                                </button>

                            </div>

                        </div>

                    </div>
                </transition>

                <transition name="toast-fade">
                    <div v-if="toast.visible" class="gallery-toast" :class="'gallery-toast-' + toast.type">
                        {{ toast.message }}
                    </div>
                </transition>

                <div class="gallery-top-bar">
                    <button class="gallery-back-button"
                            @click="goBack">
                        ← {{ lang.admin.buttons.back }}
                    </button>
                </div>

                <div class="gallery-upload-section">
                    <h2 class="gallery-section-title">{{ lang.admin.titles.upload }}</h2>
                    <div id="uppy-dashboard"></div>
                </div>

                <div class="gallery-manager-toolbar">
                    <strong>{{ lang.admin.titles.manage_images }}</strong>
                    <span v-if="loading">{{ lang.admin.messages.loading }}</span>
                    <span v-if="savingOrder">{{ lang.admin.messages.save_order }}</span>
                </div>

                <div ref="imagesGrid" class="gallery-images-grid">
                    <div v-for="image in images"
                         :key="image.id"
                         class="gallery-image-card"
                         :data-id="image.id">

                        <div class="gallery-image-header">
                            <span class="drag-handle" :title="lang.admin.messages.drag_to_sort">☰</span>

                            <button type="button"
                                    class="delete-button"
                                    @click="deleteImage(image)"
                                    :disabled="image.isDeleting">
                                {{ image.isDeleting ? lang.admin.messages.deleting + '...' : lang.admin.buttons.delete }}
                            </button>
                        </div>

                        <div class="gallery-image-preview">
                            <img :src="image.preview_url" :alt="image.alt || ''">
                        </div>

                        <div class="gallery-image-fields">
                            <label>
                                {{ lang.admin.fields.caption }}
                                <input type="text"
                                       v-model="image.caption"
                                       @input="queueSave(image)">
                            </label>

                            <label>
                                {{ lang.admin.fields.alt }}
                                <input type="text"
                                       v-model="image.alt"
                                       @input="queueSave(image)">
                            </label>
                        </div>

                        <div class="gallery-image-footer">
                            <span v-if="image.isSaving">{{ lang.admin.buttons.save }}...</span>
                            <span v-else-if="image.error" class="error-text">{{ image.error }}</span>
                        </div>
                    </div>
                </div>
            </div>
        `
    }).mount(root);
})();