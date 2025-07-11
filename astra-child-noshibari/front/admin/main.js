(function($) { 
  
  const unsubscribe = wp.data.subscribe(() => {

    const $orderselector = $('option[value="date/desc"]')

    if($orderselector.length) {

      unsubscribe()

      $orderselector
      .parent('select')
      .prepend(`
        <option value="menu_order/asc">Menu order ASC</option>
        <option value="menu_order/desc">Menu order DESC</option>
      `)
    } 
  });

})(jQuery)