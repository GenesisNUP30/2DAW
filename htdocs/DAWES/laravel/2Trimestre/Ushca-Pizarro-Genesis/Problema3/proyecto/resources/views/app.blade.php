<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @routes 
    @vite(['resources/js/app-inertia.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body>
    @inertia
</body>
</html>