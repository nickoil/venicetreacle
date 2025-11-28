<div style="position: relative;box-sizing: border-box;margin:0;">
            
    <div class="waveformContainer hyperShadow" style="height:48px;width:100%; left:0px;border-radius: 5px;overflow: hidden;">
        <table cellpadding="0" cellspacing="0" width="100%" height="100%;">
            <tr>
                <td class="playTd" align="center" valign="middle">
                    <div class="songPreview" style="display: none;line-height: 48px;">
                        <i onclick="stepBackward();" style="display:none;font-size: 18px;margin-right:6px;vertical-align: middle;" class="blue blueHoverHighlight fa fa-step-backward pointer controlsForMultiPlay backButtonWeCanClickIfVisible" aria-hidden="true"></i>
                        <i onclick="togglePlay();" style="font-size: 36px;vertical-align: middle;" class="blue blueHoverHighlight fa fa-play-circle pointer playButtonWeCanClickIfVisible" aria-hidden="true"></i>
                        <i onclick="stepForward();" style="display:none;font-size: 18px;margin-left:6px;vertical-align: middle" class="blue blueHoverHighlight fa fa-step-forward skipForward controlsForMultiPlay pointer forwardButtonWeCanClickIfVisible" aria-hidden="true"></i>
                    </div>
                </td>
                <td style="background: rgba(211, 211, 211, 0.5);font-weight:400;position: relative;">
                    <div id="waveform" style="z-index: 100;"><span class="waveformSpinner" style="z-index: 0; position: absolute;bottom: 25px;left: calc(50% - 60px);font-size: 12px;"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Generating waveform...</span></div>
                        <div style="cursor: text; border-radius: 4px 0px 5px 0px;z-index: 200; position: absolute;bottom:0px;right: 0px;background: #FFFFFF;padding:5px;box-sizing: border-box;font-size: 8pt;">
                            <div style="max-width: 200px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;color:#333333;">
                                Preview<span class="previewTrackNumContainer"> <span class="previewTrackNum">1</span></span>: <span class="previewTrackTitle bold">Loading...</span>
                            </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>