( function () {
  'use strict';

  /* ============================================================
     Mobile nav toggle
     ============================================================ */
  const header  = document.getElementById( 'site-header' );
  const nav     = document.getElementById( 'site-nav' );
  const actions = document.querySelector( '.nav-actions' );

  if ( nav ) {
    const burger = document.createElement( 'button' );
    burger.className  = 'nav-burger';
    burger.setAttribute( 'aria-label', 'Ouvrir le menu' );
    burger.setAttribute( 'aria-expanded', 'false' );
    burger.innerHTML  =
      '<span></span><span></span><span></span>';

    header.querySelector( '.container' ).appendChild( burger );

    burger.addEventListener( 'click', () => {
      const open = nav.classList.toggle( 'is-open' );
      if ( actions ) actions.classList.toggle( 'is-open', open );
      burger.setAttribute( 'aria-expanded', open );
      burger.setAttribute( 'aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu' );
      document.body.classList.toggle( 'nav-open', open );
    } );

    /* Ferme le menu si on clique en dehors */
    document.addEventListener( 'click', ( e ) => {
      if ( nav.classList.contains( 'is-open' ) &&
           ! nav.contains( e.target ) &&
           ! burger.contains( e.target ) &&
           ( ! actions || ! actions.contains( e.target ) ) ) {
        nav.classList.remove( 'is-open' );
        if ( actions ) actions.classList.remove( 'is-open' );
        burger.setAttribute( 'aria-expanded', 'false' );
        burger.setAttribute( 'aria-label', 'Ouvrir le menu' );
        document.body.classList.remove( 'nav-open' );
      }
    } );
  }


  /* ============================================================
     Dropdowns — clic sur mobile, hover géré en CSS sur desktop
     ============================================================ */
  function isTouchDevice() {
    return window.matchMedia( '(hover: none)' ).matches;
  }

  document.querySelectorAll( '.nav-item--has-dropdown' ).forEach( ( item ) => {
    const link     = item.querySelector( 'a' );
    const dropdown = item.querySelector( '.nav-dropdown' );
    if ( ! link || ! dropdown ) return;

    link.addEventListener( 'click', ( e ) => {
      if ( ! isTouchDevice() ) return;
      const open = item.classList.toggle( 'is-open' );
      e.preventDefault();
      closeOtherDropdowns( item );
      link.setAttribute( 'aria-expanded', open );
    } );
  } );

  const accountPanel = document.querySelector( '.nav-account' );
  if ( accountPanel ) {
    const trigger = accountPanel.querySelector( '.nav-account__trigger' );
    if ( trigger ) {
      trigger.addEventListener( 'click', () => {
        if ( ! isTouchDevice() ) return;
        accountPanel.classList.toggle( 'is-open' );
      } );
    }
  }

  function closeOtherDropdowns( except ) {
    document.querySelectorAll( '.nav-item--has-dropdown.is-open' ).forEach( ( item ) => {
      if ( item !== except ) {
        item.classList.remove( 'is-open' );
        const a = item.querySelector( 'a' );
        if ( a ) a.setAttribute( 'aria-expanded', 'false' );
      }
    } );
  }

  document.addEventListener( 'click', ( e ) => {
    if ( ! e.target.closest( '.nav-item--has-dropdown' ) ) {
      document.querySelectorAll( '.nav-item--has-dropdown.is-open' ).forEach( ( item ) => {
        item.classList.remove( 'is-open' );
      } );
    }
    if ( accountPanel && ! accountPanel.contains( e.target ) ) {
      accountPanel.classList.remove( 'is-open' );
    }
  } );

  /* ============================================================
     Price slider — double range
     ============================================================ */
  document.querySelectorAll( '.shop-price-slider' ).forEach( function ( widget ) {
    var minRange  = widget.querySelector( '.shop-price-slider__range--min' );
    var maxRange  = widget.querySelector( '.shop-price-slider__range--max' );
    var fill      = widget.querySelector( '.shop-price-slider__track-fill' );
    var minLabel  = widget.querySelector( '[data-role="min-label"]' );
    var maxLabel  = widget.querySelector( '[data-role="max-label"]' );
    var minHidden = widget.querySelector( '.shop-price-slider__hidden-min' );
    var maxHidden = widget.querySelector( '.shop-price-slider__hidden-max' );
    var form      = widget.querySelector( '.shop-price-slider__form' );

    if ( ! minRange || ! maxRange || ! form ) return;

    var floor = parseInt( widget.dataset.floor, 10 );
    var ceil  = parseInt( widget.dataset.ceil,  10 );
    var span  = ceil - floor;

    function fmt( v ) {
      return v.toLocaleString( 'fr-FR' ) + ' €';
    }

    function updateUI() {
      var lo = parseInt( minRange.value, 10 );
      var hi = parseInt( maxRange.value, 10 );
      var pctLo = ( ( lo - floor ) / span ) * 100;
      var pctHi = ( ( hi - floor ) / span ) * 100;
      fill.style.left  = pctLo + '%';
      fill.style.width = ( pctHi - pctLo ) + '%';
      if ( minLabel ) minLabel.textContent = fmt( lo );
      if ( maxLabel ) maxLabel.textContent = fmt( hi );
    }

    var debounceTimer;
    function scheduleSubmit() {
      clearTimeout( debounceTimer );
      debounceTimer = setTimeout( function () {
        var lo = parseInt( minRange.value, 10 );
        var hi = parseInt( maxRange.value, 10 );
        // N'ajouter le param que si hors des bornes — removeAttribute évite d'envoyer une clé vide
        if ( lo > floor ) {
          minHidden.setAttribute( 'name', 'min_price' );
          minHidden.value = lo;
        } else {
          minHidden.removeAttribute( 'name' );
          minHidden.value = '';
        }
        if ( hi < ceil ) {
          maxHidden.setAttribute( 'name', 'max_price' );
          maxHidden.value = hi;
        } else {
          maxHidden.removeAttribute( 'name' );
          maxHidden.value = '';
        }
        form.submit();
      }, 650 );
    }

    minRange.addEventListener( 'input', function () {
      var lo = parseInt( minRange.value, 10 );
      var hi = parseInt( maxRange.value, 10 );
      if ( lo >= hi ) { minRange.value = hi - 1; lo = hi - 1; }
      minRange.style.zIndex = lo > floor + span * 0.9 ? 5 : 2;
      updateUI();
      scheduleSubmit();
    } );

    maxRange.addEventListener( 'input', function () {
      var lo = parseInt( minRange.value, 10 );
      var hi = parseInt( maxRange.value, 10 );
      if ( hi <= lo ) { maxRange.value = lo + 1; }
      updateUI();
      scheduleSubmit();
    } );

    // Conserver les params prix quand l'ordering change
    var orderingForm = document.querySelector( '.shop-filter__ordering .woocommerce-ordering' );
    if ( orderingForm ) {
      orderingForm.addEventListener( 'change', function () {
        var lo = parseInt( minRange.value, 10 );
        var hi = parseInt( maxRange.value, 10 );
        if ( lo > floor ) {
          var inp = document.createElement( 'input' );
          inp.type = 'hidden'; inp.name = 'min_price'; inp.value = lo;
          orderingForm.appendChild( inp );
        }
        if ( hi < ceil ) {
          var inp2 = document.createElement( 'input' );
          inp2.type = 'hidden'; inp2.name = 'max_price'; inp2.value = hi;
          orderingForm.appendChild( inp2 );
        }
      } );
    }

    // rAF garantit que le layout est peint avant de calculer les pourcentages
    requestAnimationFrame( updateUI );
  } );


  /* ============================================================
     Scroll animations — IntersectionObserver pour .animate-in
     ============================================================ */
  const animateEls = document.querySelectorAll( '.animate-in' );
  if ( animateEls.length && 'IntersectionObserver' in window ) {
    const observer = new IntersectionObserver( ( entries ) => {
      let batchIdx = 0;
      entries.forEach( ( entry ) => {
        if ( entry.isIntersecting ) {
          entry.target.style.transitionDelay = ( batchIdx * 0.1 ) + 's';
          entry.target.classList.add( 'is-visible' );
          observer.unobserve( entry.target );
          batchIdx++;
        }
      } );
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' } );

    animateEls.forEach( ( el ) => observer.observe( el ) );
  } else {
    animateEls.forEach( ( el ) => el.classList.add( 'is-visible' ) );
  }

} )();
