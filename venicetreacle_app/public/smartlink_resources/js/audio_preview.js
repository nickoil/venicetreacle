
function debug(message) {
    console.log(message);
}

function playButton(trackNum = 0,autoplay = false) {

    wavesurfer.trackNum = trackNum;
    debug('now playing track number:');
    debug(wavesurfer.trackNum+1);
    $('.previewTrackNum').text(wavesurfer.trackNum+1 + ' of ' + previewData.tracks.length);
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
    $('.previewTrackTitle').text(previewData.tracks[wavesurfer.trackNum].title);
    if (previewData.tracks.length == 1) {
        $('.previewTrackNumContainer').hide();
    }
    // begin: hide spinner 
    if (myPcmData != '') {
        $('.waveformSpinner').hide();
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
            // $('.fa-play-circle').removeClass('fa-play-circle').addClass('fa-pause-circle');
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
    $('.waveformSpinner').show();
//nick no fb events    facebookTrackEvent('audioSkipBackward');
    if (wavesurfer.trackNum < previewData.tracks.length-1)
    {
        wavesurfer.trackNum++;
    }
    else 
    {
        wavesurfer.trackNum = 0;
    }
    playButton(wavesurfer.trackNum,playing);
}
function stepBackward()
{
    playing = wavesurfer.isPlaying()
    wavesurfer.stop();
//nick no fb events    facebookTrackEvent('audioSkipForward');
    if(wavesurfer.trackNum > 0)
    {
    wavesurfer.trackNum = wavesurfer.trackNum - 1;
    playButton(wavesurfer.trackNum,playing);
    }
    else 
    {
    wavesurfer.play();
    }
}
function togglePlay()
{
    wavesurfer.playPause();
    var isPlaying = wavesurfer.isPlaying();
    if (!isPlaying) {
        $('.fa-pause-circle').removeClass('fa-pause-circle').addClass('fa-play-circle');
//nick no fb events        facebookTrackEvent('audioPause');
    }
    else {
        $('.fa-play-circle').removeClass('fa-play-circle').addClass('fa-pause-circle');
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
    })
}
/*
function pollForPreviewFiles()
{
    var data = {};
    if (typeof pollingForPreviewFiles != 'undefined')
    {
        clearInterval(pollingForPreviewFiles);
    }
    pollingForPreviewFiles = setInterval(function()
    {
    $.get('/api/hyperfollow/audio/previewFilesExist/',data,function(response)
    {
    if (response.filesExist == true)
    {
    clearInterval(pollingForPreviewFiles);
    window.location.reload(false); 
    }
    else 
    {
    debug('Files still don\'t exist');
    }
    },'json')
    },1000)
}
*/
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
                $('.fa-pause-circle').removeClass('fa-pause-circle').addClass('fa-play-circle');
            }
        }); 
        wavesurfer.on('error', function (err) {
            debug('there was an error');
            debug(err);
            //if (err == 'Error loading media element') {
                // pollForPreviewFiles();
            //}
        }); 
        wavesurfer.on('waveform-ready', function () {
            console.log('waveform ready');
            $('.waveformSpinner').hide();
            console.log(previewData.tracks[wavesurfer.trackNum]);
            if (!previewData.tracks[wavesurfer.trackNum].previewpcmdata || previewData.tracks[wavesurfer.trackNum].previewpcmdata == '') {
                savePreviewWaveform(previewData.tracks[wavesurfer.trackNum].songid);
            }
        // saveWaveform(playerId);
        // waveformReady(playerId);
        });
        // minor cosmetic thing 
        // $('.textAboutBlurryAlbumArt').css('margin-top','-60px');
        // minor cosmetic thing 
        // capture keyboard input
        document.onkeydown = function (e) {
            // check for spacebar press
            if ([32,75,119].indexOf(e.keyCode) >= 0)  {
                if (!isCursorInInput()) {
                    e.preventDefault();
                    $('.playButtonWeCanClickIfVisible:visible').click();
                }
            }
            else if ([37,118].indexOf(e.keyCode) >= 0) {
                if (!isCursorInInput()) {
                    e.preventDefault();
                    $('.backButtonWeCanClickIfVisible:visible').click();
                    if ((wavesurfer.isPlaying()) && (!$('.backButtonWeCanClickIfVisible').is(':visible'))) {
                        stepBackward();
                    }
                }
            }
            else if ([39,120].indexOf(e.keyCode) >= 0) {
                if (!isCursorInInput()) {
                    e.preventDefault();
                    $('.forwardButtonWeCanClickIfVisible:visible').click();
                }
            }
        }   
    }
    $('.songPreview').show();
    if (previewData.tracks.length > 1) // if more than 1 track, show "previous" and "next" buttons 
    {
        $('.playTd').addClass('playTd_multi');
        $('.controlsForMultiPlay').show();
    }
}
/*  
function preloadSongs()
    {
    var delay = 0;
    $(previewData.tracks).each(function(count)
    {
    setTimeout(function()
    {
    // $.get(previewData.tracks[count].preview);
    },delay)
    delay = delay + 500; // wait a bit before preloading the next song 
    })
    playButton();
}
*/
function getAudioPreview() // gets audio preview from apple's servers 
{
    //debug('hyperAlbum:');
    //debug(treacleAlbum);
    /* why not show it?
    if (treacleAlbum.minutesUntilLiveInMyLocation > 0) {
        $('.waveformContainer').hide(); // let's not show audio before release date
        return false 
    }
    */
    /*Nick - not possible */
    if (typeof previewData != 'undefined') // if we've already loaded preview data server-side, ignore all this. 
    {
        debug('previewData exists');
        createWavesurfer();
        playButton(0,false); // "false" loads waveform & title, but doesn't actuall play 
        return false
    }
    else {
        debug('previewData does not exist');    
    }
}

$(document).ready(function() {
    getAudioPreview();
})

$(window).resize(function() {
    // begin: redraw waveform 
    if ((typeof wavesurfer != 'undefined') && ($('.songPreview').is(':visible'))) {
        wavesurfer.drawBuffer();    
    }
    // end: redraw waveform 
})