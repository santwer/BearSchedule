<?php
return [
    'viewable' => [
        //mail Domains that can see each other for search results with * all are visible
        '*'
        // 'hotmail.de' -> all hotmail.de can see and find hotmail.de users
        // ['hotmail.de', 'hotmail.com'] -> all hotmail.de and hotmail.com accounts can see and find each other
        // 'hotmail.de' => ['hotmail.de', 'hotmail.com'] -> all hotmail.de accounts can see and find all hotmail.de and hotmail.com acounts
    ]

];
