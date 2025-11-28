function movePlayButtonFromMiddleToBottom() {

    $j('.waveformContainer').show();
    $j('.hyperfollowPlayButtonIcon').hide();
}
function hyperfollowPlayButton(trackNum = 0,autoplay = false) {
    wavesurfer.trackNum = trackNum;
    debug('now playing track number:');
    debug(wavesurfer.trackNum+1);
    $j('.previewTrackNum').text(wavesurfer.trackNum+1 + ' of ' + previewData.tracks.length);
    // begin: format track title (undo some html formatting)
    previewData.tracks[wavesurfer.trackNum].title = previewData.tracks[wavesurfer.trackNum].title.replace(/&amp;/g,'&');
    previewData.tracks[wavesurfer.trackNum].title = previewData.tracks[wavesurfer.trackNum].title.replace(/&#39;/g,"'");
    previewData.tracks[wavesurfer.trackNum].title = previewData.tracks[wavesurfer.trackNum].title.replace(/&#34;/g,'"');
    previewData.tracks[wavesurfer.trackNum].title = previewData.tracks[wavesurfer.trackNum].title.replace(/&quot;/g,'"');
    // end: format track title (undo some html formatting)
    // begin: getting music from apple's servers? or from distrokid internal? 
    var myPcmData = previewData.tracks[wavesurfer.trackNum].previewpcmdata;
    if (typeof myPcmData == 'undefined') {
        myPcmData = '';
    }
    // end: getting music from apple's servers? or from distrokid internal? 
    $j('.previewTrackTitle').text(previewData.tracks[wavesurfer.trackNum].title);
    if (previewData.tracks.length == 1) {
        $j('.previewTrackNumContainer').hide();
    }
    // begin: hide spinner 
    if (myPcmData != '') {
        $j('.hfWaveformSpinner').hide();
    }
    // end: hide spinner 
    wavesurfer.load(previewData.tracks[trackNum].preview, (myPcmData == '' ? '' : JSON.parse(myPcmData)));
    // begin: loop until wavesurfer is "ready". lets not use a listener like "on ready" because safari won't play the track 
    if (typeof waitForWavesurferToBeReady != 'undefined') {
        clearInterval(waitForWavesurferToBeReady);
    }
    waitForWavesurferToBeReady = setInterval(function() {
        if (wavesurfer.isReady) {
            clearInterval(waitForWavesurferToBeReady);
            // $j('.fa-play-circle').removeClass('fa-play-circle').addClass('fa-pause-circle');
            if (autoplay) {
                wavesurfer.play();
            }
        }
    }, 150)
    // end: loop until wavesurfer is "ready". lets not use a listener like "on ready" because safari won't play the track   
} 
function stepForward(playing) {
    if (typeof playing == 'undefined')
    {
        playing = wavesurfer.isPlaying()
    }
    wavesurfer.stop();
    $j('.hfWaveformSpinner').show();
//nick no fb events    facebookTrackEvent('audioSkipBackward');
    if (wavesurfer.trackNum < previewData.tracks.length-1)
    {
        wavesurfer.trackNum++;
    }
    else 
    {
        wavesurfer.trackNum = 0;
    }
    hyperfollowPlayButton(wavesurfer.trackNum,playing);
}
function stepBackward()
{
    playing = wavesurfer.isPlaying()
    wavesurfer.stop();
//nick no fb events    facebookTrackEvent('audioSkipForward');
    if(wavesurfer.trackNum > 0)
    {
    wavesurfer.trackNum = wavesurfer.trackNum - 1;
    hyperfollowPlayButton(wavesurfer.trackNum,playing);
    }
    else 
    {
    wavesurfer.play();
    }
}
function hyperfollowTogglePlay()
{
    wavesurfer.playPause();
    var isPlaying = wavesurfer.isPlaying();
    if (!isPlaying) {
        $j('.fa-pause-circle').removeClass('fa-pause-circle').addClass('fa-play-circle');
//nick no fb events        facebookTrackEvent('audioPause');
    }
    else {
        $j('.fa-play-circle').removeClass('fa-play-circle').addClass('fa-pause-circle');
//nick no fb events       facebookTrackEvent('audioPlay');
    }
}
function isCursorInInput()
{
    var returnBoo = false;
    if ((document.activeElement.nodeName.toLowerCase() == "a") || (document.activeElement.nodeName.toLowerCase() == "input") || (document.activeElement.nodeName.toLowerCase() == "textarea") || (document.activeElement.nodeName.toLowerCase() == "button")) 
    {
    debug(document.activeElement.nodeName.toLowerCase());
    returnBoo = true
    }
    return returnBoo 
}
function savePreviewWaveform(songId) {

    console.log('savingPreviewWaveform');
    wavesurfer.exportPCM(1024, 10000, true, 0).then(function(pcmData) {

        previewData.tracks[wavesurfer.trackNum].previewpcmdata = pcmData;

        /*
        
        data = {};
        data.pcmData = myJson;
        data.id = songId;
        data.hyperfollowAlbumId = treacleAlbum.hyperfollowAlbumId;
        debug(data);
        $j.post('/api/hyperfollowPcmPreviewSave/',data,function(response)
        {
        debug('pcmdata saved');
        },'json')*/
    })
}
function hfPollForPreviewFiles()
{
    var data = {};
    data.hyperfollowAlbumId = treacleAlbum.hyperfollowAlbumId;
    debug('do files exist?');
    if (typeof hfPollingForPreviewFiles != 'undefined')
    {
    clearInterval(hfPollingForPreviewFiles);
    }
    hfPollingForPreviewFiles = setInterval(function()
    {
    $j.get('/api/hyperfollow/audio/previewFilesExist/',data,function(response)
    {
    if (response.filesExist == true)
    {
    clearInterval(hfPollingForPreviewFiles);
    window.location.reload(false); 
    }
    else 
    {
    debug('Files still don\'t exist');
    }
    },'json')
    },1000)
}
function createWavesurfer()
{
    if (typeof wavesurfer == 'undefined') {
        wavesurfer = WaveSurfer.create({
            container: '#waveform',
            waveColor: '#0F78B8',
            progressColor: '#074972',
            cursorColor: '#FFFFFF',
            height: 48,
            backend: 'MediaElement' /* required to make iOS play even when on silent mode */
        });
        wavesurfer.on('finish', function () {
            if (wavesurfer.trackNum < previewData.tracks.length-1) {
                stepForward(true);
            }
            else {
                $j('.fa-pause-circle').removeClass('fa-pause-circle').addClass('fa-play-circle');
            }
        }); 
        wavesurfer.on('error', function (err) {
            debug('there was an error');
            debug(err);
            if (err == 'Error loading media element') {
                hfPollForPreviewFiles();
            }
        }); 
        wavesurfer.on('waveform-ready', function () {
            console.log('waveform ready');
            $j('.hfWaveformSpinner').hide();
            console.log(previewData.tracks[wavesurfer.trackNum]);
            if (!previewData.tracks[wavesurfer.trackNum].previewpcmdata || previewData.tracks[wavesurfer.trackNum].previewpcmdata == '') {
                savePreviewWaveform(previewData.tracks[wavesurfer.trackNum].songid);
            }
        // saveWaveform(playerId);
        // waveformReady(playerId);
        });
        // minor cosmetic thing 
        // $j('.textAboutBlurryAlbumArt').css('margin-top','-60px');
        // minor cosmetic thing 
        // capture keyboard input
        document.onkeydown = function (e) {
            // check for spacebar press
            if ([32,75,119].indexOf(e.keyCode) >= 0)  {
                if (!isCursorInInput()) {
                    e.preventDefault();
                    $j('.playButtonWeCanClickIfVisible:visible').click();
                }
            }
            else if ([37,118].indexOf(e.keyCode) >= 0) {
                if (!isCursorInInput()) {
                    e.preventDefault();
                    $j('.backButtonWeCanClickIfVisible:visible').click();
                    if ((wavesurfer.isPlaying()) && (!$j('.backButtonWeCanClickIfVisible').is(':visible'))) {
                        stepBackward();
                    }
                }
            }
            else if ([39,120].indexOf(e.keyCode) >= 0) {
                if (!isCursorInInput()) {
                    e.preventDefault();
                    $j('.forwardButtonWeCanClickIfVisible:visible').click();
                }
            }
        }   
    }
    $j('.hyperfollowSongPreview').show();
    if (previewData.tracks.length > 1) // if more than 1 track, show "previous" and "next" buttons 
    {
        $j('.playTd').addClass('playTd_multi');
        $j('.controlsForMultiPlay').show();
    }
}
/*  
function preloadSongs()
    {
    var delay = 0;
    $j(previewData.tracks).each(function(count)
    {
    setTimeout(function()
    {
    // $j.get(previewData.tracks[count].preview);
    },delay)
    delay = delay + 500; // wait a bit before preloading the next song 
    })
    hyperfollowPlayButton();
}
*/
function getAudioPreview() // gets audio preview from apple's servers 
{
    //debug('hyperAlbum:');
    //debug(treacleAlbum);
    /* why not show it?
    if (treacleAlbum.minutesUntilLiveInMyLocation > 0) {
        $j('.waveformContainer').hide(); // let's not show audio before release date
        return false 
    }
    */
    /*Nick - not possible */
    if (typeof previewData != 'undefined') // if we've already loaded preview data server-side, ignore all this. 
    {
        debug('previewData exists');
        createWavesurfer();
        hyperfollowPlayButton(0,false); // "false" loads waveform & title, but doesn't actuall play 
        return false
    }
    else {
        debug('previewData does not exist');    
    }
    /**/
    /*
    previewData = {} // global variable 
    previewData.tracks = [];
    previewData.id = treacleAlbum.hyperfollowAlbumId;
    var myUrl = 'https://itunes.apple.com/lookup?upc=199086599840&entity=song&callback=iTunesCallback';
    $j.get(myUrl,function(response)
    {
        if (response.resultCount > 0) {
            $j.each(response.results,function() {
                if (this.wrapperType == 'track') {
                    $j('.hyperfollowSongPreview').show();
                    debug('this.trackName');
                    debug(this.trackName);
                    var trackStruct = {}
                    trackStruct.preview = this.previewUrl;
                    trackStruct.title = this.trackName;
                    previewData.tracks.push(trackStruct);
                }
            })

            // Reorder tracks by specified title order
            const titleOrder = ["Fabada", "The Glass", "Boson Black Hole", "Out of the Eyes"];
            previewData.tracks.sort((a, b) => {
                const indexA = titleOrder.indexOf(a.title);
                const indexB = titleOrder.indexOf(b.title);
                
                // If title not found in order array, put it at the end
                if (indexA === -1 && indexB === -1) return 0;
                if (indexA === -1) return 1;
                if (indexB === -1) return -1;
                
                return indexA - indexB;
            });

            debug('previewData');
            debug(previewData);

            /* nick not appropriate
            // begin: save pcm 
            if (previewData.tracks.length > 0) {
                $j(previewData.tracks).each(function() {
                    if (this.previewpcmdata == '') {
                        savePreviewWaveform(this.songid);
                    }
                })
            }
            * /

            // end: save pcm 
            createWavesurfer();
            hyperfollowPlayButton();
        }

    },'jsonp')

    */
}

function fixPlaceHolder() {
    $j('.hyperfollowArtPlaceholder').css('opacity','0');
    $j('.wordsOverBlurryStuff').show();
    $j('.hyperfollowArtPlaceholder').height($j('.blurryArt:visible').outerHeight());
}

$j(document).ready(function() {
    getAudioPreview();
})

$j(window).resize(function() {
    // begin: redraw waveform 
    if ((typeof wavesurfer != 'undefined') && ($j('.hyperfollowSongPreview').is(':visible'))) {
        wavesurfer.drawBuffer();    
    }
    // end: redraw waveform 
})