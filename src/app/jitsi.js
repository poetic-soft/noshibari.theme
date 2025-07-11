export default $ => {

  const openConference = (credentials)=> {

    console.log(credentials.jwt.data)
    
    const script = document.createElement('script');
    script.src = `https://8x8.vc/${credentials.appid}/external_api.js`;
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

      const api = new JitsiMeetExternalAPI(
        `8x8.vc`, 
        {
          room: `${credentials.appid}/${credentials.room}`,
          width: '100%',
          height: '100%',
          parentNode: $conferencewrapper[0],
          jwt: credentials.jwt.data,
          onload: () => { 'READY' }
        }
      )
    };

    script.onerror = () => {

      console.error('Error al cargar el script.');
    };

    document.head.appendChild(script);
  }

  const $subscribe = $('.shortcode.jitsi')
  if($subscribe.length) {    

    $subscribe
    .each(function() {

      const $this = $(this)
      const $form = $this.find('form')
      const $inputemail = $form.find('input#email')
      const $inputtitle = $form.find('input#title')
      const $button = $form.find('.wp-block-button')
      const $message = $this.find('.message')
      const messageinvalid = $inputemail.data('message-invalid')
      const messageerror = $inputemail.data('message-error')
      const messageok = $inputemail.data('message-ok')
      
      $form.validate({
        messages: {
          email: {
            email: messageinvalid
          }
        }
      })  

      $inputemail
      .on(
        'keyup',
        function() {

          const $this = $(this) 
              
          if($this.valid()) {

            $button.prop('disabled', false);

          } else {

            $button.prop('disabled', true);
          }
        }
      )

      $button
      .on(
        'click',
        function() {

          $form.hide()

          $message.removeClass('warning error success')
          $message.html('Conectando...')
          $message.addClass('warning')
          $message.show()

          fetch(
            '/wp-json/noshibari/jitsi/jwt',
            {
              method: 'POST',
              headers: {
                "Content-Type": "application/json"
              },
              body: JSON.stringify({
                email: $inputemail.val(),
                title: $inputtitle.val()
              })
            }
          )
          .then(response => {

            $message.removeClass('warning error success')

            if(response.status != 200) {

              $message.html(messageerror)
              $message.addClass('error')

            } else {

              $message.html(messageok)
              $message.addClass('success')
            }
              
            $message.show()

            setTimeout(() => {

              $message.hide()
              $form.show()
              $inputemail.val('')

              response.json()
              .then(credentials => openConference(credentials))
              
            }, 2000)
          })

          return false
        }
      )
    })
  }
}