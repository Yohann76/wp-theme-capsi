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
     Dropdowns touch — expose la classe is-open au CSS
     ============================================================ */
  // Sur mobile, .is-open remplace :hover/:focus-within pour les dropdowns



} )();
