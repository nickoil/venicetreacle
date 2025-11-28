<x-smartlink-layout>

    <x-slot name="title">
        Modern Elixir
    </x-slot>
        
    <img src="/smartlink_resources/images/venice_treacle_modern_elixir.webp" alt="Album Cover" class="album-cover">
    
    <x-audio-previewer></x-audio-previewer>
    
    <h1>Venice Treacle</h1>
    <h2>Modern Elixir</h2>
    <p class="description">Check out the debut EP from Venice Treacle on your favorite streaming platform</p>
    
    <a href="https://open.spotify.com/artist/3bWqCt417V9s9SryrvM1aq?si=hn5GudiSQLeKAWBJhC39Yw" target="_blank" class="music-button" data-service="spotify">
        Listen on&nbsp;<img src="https://storage.googleapis.com/pr-newsroom-wp/1/2023/05/Spotify_Full_Logo_RGB_Green-300x82.png" alt="Spotify Logo">
    </a>
    <a href="https://venicetreacle.bandcamp.com" target="_blank" class="music-button" data-service="bandcamp">
        Listen on&nbsp;<img src="/smartlink_resources/icons/bandcamp-logotype-color-512.png" alt="Bandcamp Logo">
    </a>
    
    <a href="https://music.apple.com/us/album/modern-elixir-ep/1793409540" target="_blank" class="music-button" data-service="apple-music">
        Listen on&nbsp;<img src="/smartlink_resources/icons/Apple_Music.svg" alt="Apple Music Logo" />&nbsp;Apple Music
    </a>

    <a href="https://soundcloud.com/venice-treacle/sets/modern-elixir" target="_blank" class="music-button" data-service="soundcloud">
        Listen on&nbsp;<img src="/smartlink_resources/icons/soundcloud_logo.svg" alt="SOUNDCLOUD Logo" style="height:1em;" />
    </a>

    @once
    
        @push('page-scripts')
            <script type="text/javascript">

                treacleAlbum = {}
                treacleAlbum.artist = "Venice Treacle"
                treacleAlbum.albumTitle = "Modern Elixir"
                treacleAlbum.releaseDate = "February, 03 2025 00:00:00 +0000"
                treacleAlbum.daysUntilLive = -20
                treacleAlbum.artistNameShortcut = "venicetreacle"
                treacleAlbum.albumIdShortcut = "modern-elixir"
                treacleAlbum.releaseDateLocalTime = new Date(treacleAlbum.releaseDate);
                //treacleAlbum.releaseDateLocalTime = new Date(moment(treacleAlbum.releaseDateLocalTime).local().format('YYYY-MM-DD HH:mm:ss'));
                // treacleAlbum.releaseDateTimeZoneOffsetInMinutes = treacleAlbum.releaseDateLocalTime.getTimezoneOffset();
                // treacleAlbum.releaseDateLocalTime = moment(treacleAlbum.releaseDateLocalTime).add(hyperAlbum.releaseDateTimeZoneOffsetInMinutes, 'm').toDate();
                treacleAlbum.minutesUntilLiveInMyLocation = (treacleAlbum.releaseDateLocalTime - new Date)/1000/60;

                previewData = {};
                previewData.tracks = JSON.parse( "\x5B\x7B\x22previewpcmdata\x22\x3A\x22\x22,\x22songid\x22\x3A46041176,\x22title\x22\x3A\x22Fabada\x22,\x22preview\x22\x3A\x22https\x3A\x2F\x2Fs3.amazonaws.com\x2Faudio.distrokid.com\x2Fpreview_68302445_03B06661\x2DFB7B\x2D4119\x2DAF32809F21F87AB2.mp3\x22\x7D,\x7B\x22previewpcmdata\x22\x3A\x22\x22,\x22songid\x22\x3A46041177,\x22title\x22\x3A\x22The\x20Glass\x22,\x22preview\x22\x3A\x22https\x3A\x2F\x2Fs3.amazonaws.com\x2Faudio.distrokid.com\x2Fpreview_68302446_03929084\x2DDFBF\x2D46CF\x2D8AF7C61887917299.mp3\x22\x7D,\x7B\x22previewpcmdata\x22\x3A\x22\x22,\x22songid\x22\x3A46041178,\x22title\x22\x3A\x22Boson\x20Black\x20Hole\x22,\x22preview\x22\x3A\x22https\x3A\x2F\x2Fs3.amazonaws.com\x2Faudio.distrokid.com\x2Fpreview_68302444_52DB8C79\x2D622A\x2D47A7\x2D8A3C73CFDA24ECDC.mp3\x22\x7D,\x7B\x22previewpcmdata\x22\x3A\x22\x22,\x22songid\x22\x3A46041175,\x22title\x22\x3A\x22Out\x20of\x20the\x20Eyes\x22,\x22preview\x22\x3A\x22https\x3A\x2F\x2Fs3.amazonaws.com\x2Faudio.distrokid.com\x2Fpreview_68302443_B7FE8CB6\x2DA5CF\x2D4715\x2D80E5F8203259283A.mp3\x22\x7D\x5D" ); 
                console.log(previewData);
            </script>
        @endpush
    @endonce

</x-smartlink-layout>