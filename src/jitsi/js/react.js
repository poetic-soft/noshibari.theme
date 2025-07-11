import React from 'react';
import ReactDOM from 'react-dom/client'
import { JaaSMeeting } from '@jitsi/react-sdk'

const Jitsi = props => {

  const handleApiReady = (externalApi) => {
    
    externalApi
    .addListener(
      'videoConferenceJoined', 
      e => {

        props.hideclose()
      }
    );
  };

  return <JaaSMeeting
    className="jitsi"
    appId={ props.appid }
    roomName={ props.room }
    jwt={ props.jwt }
    userInfo={{
      displayName: props.username,
    }}
    configOverwrite={{
      disableLocalVideoFlip: true,
      backgroundAlpha: 0.5
    }}
    interfaceConfigOverwrite = {{
      VIDEO_LAYOUT_FIT: 'nocrop',
      MOBILE_APP_PROMO: false,
      TILE_VIEW_MAX_COLUMNS: 4
    }}
    onApiReady={ handleApiReady }
    onReadyToClose={ props.close }
  />
}

export default (
  $, 
  credentials
) => {
    
  $('body')
  .css('overflow', 'hidden')
  .append(`
    <div id="conferencewrapper">
      <div id="jitsiwrapper"></div>
      <div id="jitsiclose">
        <div class="bar a"></div>
        <div class="bar b"></div>
      </div>
    </div>
  `)

  const close = () => {

    $('#conferencewrapper').remove()
  }

  const hideclose = () => {

    $('#jitsiclose').remove()
  }

  $('#jitsiclose')
  .on(
    'click',
    function() {

      close()
    }
  )

  const elm = document.getElementById('jitsiwrapper')
  const root = ReactDOM.createRoot(elm);
  root.render(<Jitsi 
    { ... credentials } 
    hideclose={ hideclose } 
    close={ close }
  />);
}  