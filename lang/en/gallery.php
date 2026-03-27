<?php

return [

    'messages' => [
        'gallery' => [
            'created' => 'Gallery created successfully.',
            'updated' => 'Gallery updated successfully.',
            'deleted' => 'Gallery deleted successfully.',
        ],

        'image' => [
            'uploaded' => 'Images uploaded successfully.',
            'updated' => 'Image updated successfully.',
            'deleted' => 'Image deleted successfully.',
            'update_error' => 'An error occurred while updating the image.',
            'delete_error' => 'An error occurred while deleting the image.',
        ],

        'sort' => [
            'updated' => 'Image order updated successfully.',
            'error' => 'Failed to update image order.',
        ],
    ],

    'validation' => [
        'gallery' => [
            'title_required' => 'The title field is required.',
            'title_unique' => 'This gallery title already exists. Please choose another one.',
            'title_max' => 'The title may not be greater than :max characters.',
            'event_date_date' => 'The event date is not a valid date.',
        ],

        'image' => [
            'update_error' => 'Failed to save the image.',
        ],
    ],

    'frontend' => [
        'title' => 'Gallery',
        'back' => 'Back to galleries',

        'empty' => [
            'galleries' => 'No galleries are currently available.',
            'images' => 'There are no images in this gallery yet.',
        ],

        'actions' => [
            'open_gallery' => 'Open gallery',
        ],
    ],

    'admin' => [

        'titles' => [
            'index' => 'Manage galleries',
            'create' => 'Create new gallery',
            'edit' => 'Edit gallery',
            'upload' => 'Upload image(s)',
            'manage_images' => 'Manage images',
        ],

        'fields' => [
            'id' => 'ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'image' => 'Image',
            'caption' => 'Caption',
            'alt' => 'Alt',
            'created_at' => 'Created at',
            'public' => 'Public',
        ],

        'buttons' => [
            'create' => 'Create gallery',
            'back' => 'Back',
            'save' => 'Save',
            'update' => 'Update',
            'delete' => 'Delete',
            'edit' => 'Edit',
            'upload' => 'Upload',
            'save_order' => 'Save order',
            'cancel' => 'Cancel',
            'confirm' => 'Confirm',
            'manage_images' => 'Manage images'
        ],

        'status' => [
            'public_yes' => 'Yes',
            'public_no' => 'No',
        ],

        'messages' => [
            'loading' => 'Loading...',
            'drag_to_sort' => 'Drag to sort',
            'save_order' => 'Saving order...',
            'deleting' => 'Deleting...',
            'upload_description' => 'Drag images here or browse to upload.',
        ],

        'confirm' => [
            'delete_gallery' => 'Are you sure you want to delete this gallery?',
            'delete_image' => 'Are you sure you want to delete this image?',
        ],

        'empty' => [
            'galleries' => 'No galleries yet.',
        ],
    ],

];