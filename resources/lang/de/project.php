<?php
return [
    'timeline' => 'Timeline',
    'item' => 'Element',
    'items' => 'Elemente',
    'groups' => 'Gruppen',
    'share' => 'Teilen',
    'weblink' => 'Link',
    'logs' => 'Logs',
    'settings' => 'Einstellungen',
    'url' => 'URL',
    'project_name' => 'Projekt Name',
    'item_design' => 'Element Design',
    'select_itemtype' => 'Select Elementtyp',
    'itemtype' =>  [
        'default' => 'Standard',
        'with_subtitle' => 'mit zweit-Titel',
        'jira_with_subtitle' => 'Jira Element mit zweit-Titel',
    ],
    'item_orientation' => 'Element Orientierung ',
    'axis_orientation' => 'Achsen Orientierung ',
    'axis_ori' => [
        'bottom' => 'unten',
        'top' => 'oben',
        'both' => 'beide',
        'none' => 'keine',
    ],
    'zoom' => 'Zoom',
    'initial_zoom' => 'Anfänglicher Zoom',
    'zoom_timeline' => [
        'auto' => 'Auto',
        'year' => 'Jahr',
        'month' => 'Monat',
        'week' => 'Woche',
        'day' => 'Tag',
    ],
    'jira' => [
        'jira_host' => 'Jira Host',
        'jira_user' => 'Jira Benutzername',
        'apitoken'  => 'ApiToken',
    ],
    'display_options' => [
        'default' => 'Default',
        'weekday' => 'Wochentag',
        'week' => 'Woche',
        'day' => 'Tag',
        'month' => 'Monat',
        'year' => 'jahr',
    ],
    'timeline_tables'=> [
        'columns' => [
            'id' => 'ID',
            'title' => 'Titel',
            'content' => 'Inhalt',
            'subtitle' => 'Untertitel',
            'type' => 'Typ',
            'start' => 'Start',
            'end' => 'Ende',
            'name' => 'Name',
        ]
    ],
    'timelines' => [
        'display' => 'Anzeige',
        'no_items' => 'keine Elemente',
        'no_items_message' => 'Da noch keine Items vorhanden sind, ist es noch nicht möglich die Timeline zu laden. Bitte erst Gruppen und Items anlegen.',
        'item' => [
            'new' => 'Element',
            'data' => 'Allgemein',
            'no_group' => 'keine Gruppe',
            'select_group' => 'Wähle eine Gruppe',
            'add_link' => 'Link hinzufügen',
            'select_type' => 'Wählen einen Typ',
            'status' => 'Status',
            'select_status' => 'Wählen einen  Status',
            'click_to_select' => 'Klicken zum wählen...',
            'color' => 'Farbe',
            'tags' => 'Tags',
            'enter_tags' => 'Füge Tags an',
            'add_tags' => 'Neuer Tag',
            'series' => 'Serie',
            'types' => [
                'box' => 'Box',
                'point' => 'Punkt',
                'range' => 'Zeitraum',
                'background' => 'Hintergrund',
            ],
            'stati' => [
                'DEFAULT' => 'Normal',
                'DELAYED' => 'Verspätet',
                'CRITICAL' => 'Kritisch',
                'TEST' => 'Test',
                'DONE' => 'FERTIG',
            ],
            'colors' => [
                'undefined' => 'Standard',
                'default' => 'Standard',
                '#d32f2f' => 'Rot',
                '#c2185b' => 'Pink',
                '#7b1fa2' => 'Lila',
                '#512da8' => 'Dunkellila ',
                '#303f9f' => 'Indigo',
                '#1976d2' => 'Blau',
                '#0288d1' => 'Hellblau',
                '#0097a7' => 'Cyan',
                '#00796b' => 'Blaugrün',
                '#388e3c' => 'Grün',
                '#689f38' => 'Hellgrün',
                '#fbc02d' => 'Kalk',
                '#afb42b' => 'Gelb',
                '#ffa000' => 'Bernstein',
                '#f57c00' => 'Orange',
                '#e64a19' => 'DunkelOrange',
                '#9e9e9e' => 'Grau',
            ],

        ],
        'group' => [
            'add' => 'Gruppe',
            'group' => 'Gruppe',
            'show_in_share' => 'beim Teilen anzeigen',
        ],
        'messages' => [
            'save_fail' => 'Speichern ist fehlgeschlagen. Versuche es später erneut.',
            'save_success' => 'Erfolgreich gespeichert.',
            'delete_success' => 'Erfolgreich gelöscht',
            'delete_fail' => 'Löschen ist fehlgeschlagen. Versuche es später erneut.',
            'confirm_delete_group' => 'Möchtest du diese Gruppe löschen?',
            'confirm_delete_item' => 'Möchtest du dieses Item löschen?',
        ],
        'connection_status' => 'Verbindungsstatus',
    ],
    'filter_groups' => 'Filter Gruppen',
    'setting_users' => [
        'name' => 'Name',
        'email' => 'Email',
        'add_user' => 'Benutzer hinzufügen',
        'role' => 'Rolle',
        'roles_option' => [
            'ADMIN' => 'Admin',
            'EDITOR' => 'Editor',
            'SUBSCRIBER' => 'Abonnent'
        ]
    ]
];
