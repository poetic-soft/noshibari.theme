export default $ => {

  const $qa = $('.shortcode.qa')
  if($qa.length) {    

    $qa
    .each(function() {

      const $this = $(this)
      const $form = $this.find('form')
      const $textareaqa = $form.find('textarea#qa')
      const $inputtitle = $form.find('input#title')
      const $button = $form.find('.wp-block-button')
      const $message = $this.find('.message')
      const messageerror = $textareaqa.data('message-error')
      const messageok = $textareaqa.data('message-ok')

      $textareaqa
      .on(
        'keyup',
        function() {

          const $this = $(this)
          const content = $this.val()
          
          if(content.length > 20) {

            $button.prop('disabled', false);
            $textareaqa.addClass('valid')

          } else {

            $button.prop('disabled', true);
            $textareaqa.removeClass('valid')
          }
        }
      )

      $button
      .on(
        'click',
        function() {

          $form.hide()

          $message.removeClass('warning error success')
          $message.html('Enviando...')
          $message.addClass('warning')
          $message.show()

          fetch(
            '/wp-json/noshibari/qa',
            {
              method: 'POST',
              headers: {
                "Content-Type": "application/json"
              },
              body: JSON.stringify({
                qa: $textareaqa.val(),
                title: $inputtitle.val()
              })
            }
          )
          .then(response => {

            $message.removeClass('warning error success')
            $textareaqa.val('')  
            $button.prop('disabled', true);
            $textareaqa.removeClass('valid')

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
              
            }, 4000)
          })

          return false
        }
      )
    })
  }
}