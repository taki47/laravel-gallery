<?php

return [
    'pagination' => [
        'showing' => 'Megjelenítve',
    ],

    'messages' => [
        'gallery' => [
            'created' => 'A galéria létrejött.',
            'updated' => 'A galéria frissítve lett.',
            'deleted' => 'A galéria törölve lett.',
        ],

        'image' => [
            'uploaded' => 'A kép(ek) sikeresen feltöltve.',
            'updated' => 'A kép sikeresen módosítva.',
            'deleted' => 'A kép sikeresen törölve.',
            'update_error' => 'Hiba történt a kép mentése során.',
            'delete_error' => 'Hiba történt a kép törlése során.',
        ],

        'sort' => [
            'updated' => 'A képek sorrendje sikeresen mentve.',
            'error' => 'A képek sorrendjének mentése sikertelen.',
        ],
    ],

    'validation' => [
        'gallery' => [
            'title_required' => 'A cím megadása kötelező.',
            'title_unique' => 'Ez a galéria cím már létezik. Adj meg egy másik címet.',
            'title_max' => 'A cím legfeljebb :max karakter lehet.',
            'event_date_date' => 'Az esemény dátuma nem megfelelő formátumú.',
        ],

        'image' => [
            'update_error' => 'A kép mentése nem sikerült.',
        ],
    ],

    'frontend' => [
        'title' => 'Galéria',
        'back' => 'Vissza a galériákhoz',

        'empty' => [
            'galleries' => 'Jelenleg nincs elérhető galéria.',
            'images' => 'Ebben a galériában még nincsenek képek.',
        ],

        'actions' => [
            'open_gallery' => 'Galéria megnyitása',
        ],
    ],

    'admin' => [

        'titles' => [
            'index' => 'Galériák kezelése',
            'create' => 'Új galéria létrehozása',
            'edit' => 'Galéria szerkesztése',
            'upload' => 'Kép(ek) feltöltése',
            'manage_images' => 'Képek kezelése',
        ],

        'fields' => [
            'id' => 'Azonosító',
            'title' => 'Cím',
            'slug' => 'Slug',
            'description' => 'Leírás',
            'image' => 'Kép',
            'caption' => 'Felirat',
            'alt' => 'Alt',
            'created_at' => 'Létrehozva',
            'public' => 'Publikus',
        ],

        'buttons' => [
            'create' => 'Új galéria',
            'back' => 'Vissza',
            'save' => 'Mentés',
            'update' => 'Frissítés',
            'delete' => 'Törlés',
            'edit' => 'Szerkesztés',
            'upload' => 'Feltöltés',
            'save_order' => 'Sorrend mentése',
            'cancel' => 'Mégse',
            'confirm' => 'Mehet',
            'manage_images' => 'Képek kezelése',
        ],

        'status' => [
            'public_yes' => 'Igen',
            'public_no' => 'Nem',
        ],

        'messages' => [
            'loading' => 'Betöltés...',
            'drag_to_sort' => 'Húzd a rendezéshez',
            'save_order' => 'Sorrend mentése...',
            'deleting' => 'Tölés...',
            'upload_description' => 'Húzd ide a képeket, vagy tallózd be őket.',
        ],

        'confirm' => [
            'delete_gallery' => 'Biztosan törlöd ezt a galériát?',
            'delete_image' => 'Biztosan törlöd ezt a képet?',
        ],

        'empty' => [
            'galleries' => 'Még nincs galéria.',
        ],
    ],

];