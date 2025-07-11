export default credentials => {
    
  const script = document.createElement('script');
  script.src = `https://${credentials.appid}.8x8.vc//external_api.js`;
  script.type = 'text/javascript';
  script.async = true; 

  script.onload = () => {

      $('body')
    .css('overflow', 'hidden')
    .append(`
      <div id="conferencewrapper">
      </div>
    `)
    const $conferencewrapper = $('#conferencewrapper')
    // https://jitsi.github.io/handbook/docs/dev-guide/dev-guide-iframe-events
    const options = {
      roomName: `${credentials.appid}/${credentials.room}`,
      width: '100%',
      height: '100%',
      parentNode: $conferencewrapper[0],
      jwt: credentials.jwt,
      onload: () => { 'READY' },
      configOverwrite: {
        disableDeepLinking: true,
      },
      interfaceConfigOverwrite: {
        TOOLBAR_BUTTONS: [
          'microphone', 
          'camera', 
          'fullscreen', 
          'chat'
        ],    
        SHOW_CHROME_EXTENSION_BANNER: false
      },
      onload: () => { console.log('onload') },
      videoConferenceLeft: () => { console.log('videoConferenceLeft') },
      readyToClose: () => { console.log('readyToClose') }
    }

    const api = new JitsiMeetExternalAPI(
      `${ credentials.appid }.8x8.vc`, 
      options
    )
  };

  script.onerror = () => {

    console.error('Error al cargar el script.');
  };

  document.head.appendChild(script);
}

  