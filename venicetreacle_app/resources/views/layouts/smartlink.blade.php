<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venice Treacle - {{ $title }} - Listen Now</title>

    <!-- cache busting -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="/smartlink_resources/js/wavesurfer_pud.js"></script>
    <script src="/smartlink_resources/js/audio_preview.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/smartlink_resources/styles/smartlink_styles.css">
    @stack('page-styles')

</head>
<body>
    <div class="container">
        <main>
            {{ $slot }}
        </main>

        <div class="social-icons">
            <a class="social-icon" data-social="website" href="https://venicetreacle.com" target="_blank"><img src="/smartlink_resources/icons/venice_treacle_favicon.png" alt="Venice Treacle Logo" style="border-radius:5px;"></a>
            <a class="social-icon" data-social="facebook" href="https://www.facebook.com/venicetreacle" target="_blank"><img src="/smartlink_resources/icons/FB-colour.png" alt="Facebook Logo"></a>
            <a class="social-icon" data-social="instagram" href="https://www.instagram.com/venicetreacle" target="_blank"><img src="/smartlink_resources/icons/Insta-colour.svg" alt="Instagram Logo"></a>
            <a class="social-icon" data-social="youtube" href="https://www.youtube.com/@venicetreacle" target="_blank"><img src="/smartlink_resources/icons/YouTube-colour.png" alt="YouTube Logo"></a>
        </div>
        
    </div>

    @stack('page-scripts')

</body>
</html>
