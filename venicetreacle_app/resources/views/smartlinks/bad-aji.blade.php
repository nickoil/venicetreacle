<x-smartlink-layout>

    <x-slot name="title">
        Bad Aji
    </x-slot>
        
    <img src="/smartlink_resources/images/venice_treacle_bad_aji.webp" alt="Album Cover" class="album-cover">
    
    <x-audio-previewer></x-audio-previewer>
    
    <h1>Venice Treacle</h1>
    <h2>Bad Aji</h2>
    
    @if(session('success'))
        <div class="success-message">
            <p>{{ session('success') }}</p>
        </div>
    @elseif(session('error'))
        <div class="error-message">
            <p>{{ session('error') }}</p>
        </div>
        <p class="description">Help us beat the algorithm! Pre-save the groovy new number from Venice Treacle to Spotify</p>
        <a id="spotifyPresaveButton" target="_blank" class="music-button" data-service="spotify">
            Pre-save to &nbsp;<img src="https://storage.googleapis.com/pr-newsroom-wp/1/2023/05/Spotify_Full_Logo_RGB_Green-300x82.png" alt="Spotify Logo">
        </a>
    @else
        
        @if($now->greaterThanOrEqualTo($releaseDate))
            <p class="description">As technology changes how (and if) you use your mind, Venice Treacle brings you a new, entirely human-made track designed to help you lose it.</p>

            <a href="https://open.spotify.com/track/2JFZlBx722B6hvuwNLdLFt?si=vt_smartlink" target="_blank" class="music-button" data-service="spotify">
                Listen to Bad Aji on&nbsp;<img src="https://storage.googleapis.com/pr-newsroom-wp/1/2023/05/Spotify_Full_Logo_RGB_Green-300x82.png" alt="Spotify Logo">
            </a>
            <a href="https://venicetreacle.bandcamp.com/track/bad-aji" target="_blank" class="music-button" data-service="bandcamp">
                Listen to Bad Aji on&nbsp;<img src="/smartlink_resources/icons/bandcamp-logotype-color-512.png" alt="Bandcamp Logo">
            </a>
            <a href="https://music.apple.com/album/1855846919?i=1855846920" target="_blank" class="music-button" data-service="apple-music">
                Listen to Bad Aji on&nbsp;<img src="/smartlink_resources/icons/Apple_Music.svg" alt="Apple Music Logo" />&nbsp;Apple Music
            </a>
            <a href="https://soundcloud.com/venice-treacle/bad-aji/s-KUgUmBjXl28" target="_blank" class="music-button" data-service="soundcloud">
                Listen to Bad Aji on&nbsp;<img src="/smartlink_resources/icons/soundcloud_logo.svg" alt="SOUNDCLOUD Logo" style="height:1em;" />
            </a>
        @else
            <p class="description">Help us beat the algorithm! Pre-save the groovy new number from Venice Treacle to Spotify</p>
            <a id="spotifyPresaveButton" target="_blank" class="music-button" data-service="spotify">
                Pre-save to &nbsp;<img src="https://storage.googleapis.com/pr-newsroom-wp/1/2023/05/Spotify_Full_Logo_RGB_Green-300x82.png" alt="Spotify Logo">
            </a>
        @endif

    @endif
    

    @once
    
        @push('page-scripts')
            <script type="text/javascript">

                treacleAlbum = {}
                treacleAlbum.artist = "Venice Treacle"
                treacleAlbum.albumTitle = "Bad Aji"
                treacleAlbum.releaseDate = "December, 12 2025 00:00:00 +0000"
                treacleAlbum.daysUntilLive = -20
                treacleAlbum.artistNameShortcut = "venicetreacle"
                treacleAlbum.albumIdShortcut = "bad-aji"
                treacleAlbum.releaseDateLocalTime = new Date(treacleAlbum.releaseDate);
                //treacleAlbum.releaseDateLocalTime = new Date(moment(treacleAlbum.releaseDateLocalTime).local().format('YYYY-MM-DD HH:mm:ss'));
                // treacleAlbum.releaseDateTimeZoneOffsetInMinutes = treacleAlbum.releaseDateLocalTime.getTimezoneOffset();
                // treacleAlbum.releaseDateLocalTime = moment(treacleAlbum.releaseDateLocalTime).add(hyperAlbum.releaseDateTimeZoneOffsetInMinutes, 'm').toDate();
                treacleAlbum.minutesUntilLiveInMyLocation = (treacleAlbum.releaseDateLocalTime - new Date)/1000/60;

                previewData = {};
                previewData.tracks = [
                    {
                        "previewpcmdata": "",
                        "songid": 1,
                        "title": "Bad Aji",
                        "preview": "/smartlink_resources/audio/venice-treacle-bad-aji-preview.mp3"
                    }
                ];
                console.log(previewData);
            </script>
            <script>
                document.getElementById('spotifyPresaveButton').addEventListener('click', function() {
                    const clientId = '{{  config('services.spotify.client_id')  }}';
                    const redirectUri = encodeURIComponent('{{ config('services.spotify.redirect_uri') }}');
                    const scope = 'user-library-modify user-read-email user-read-private';
                    const state = 'bad-aji'; 
                    const oauthUrl = `https://accounts.spotify.com/authorize?client_id=${clientId}&response_type=code&redirect_uri=${redirectUri}&scope=${scope}&state=${state}`;
                    
                    window.location.href = oauthUrl;
                });
            </script>
        @endpush
    @endonce

</x-smartlink-layout>