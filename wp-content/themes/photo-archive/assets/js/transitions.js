/**
 * Photo Archive — View Transitions
 *
 * Enables the native View Transitions API for archive → dossier navigation.
 * Falls back to plain navigation for browsers without support.
 */

( function () {
  'use strict';

  /**
   * Assign view-transition-name to each archive thumbnail
   * so the browser can animate the photo element during navigation.
   */
  function assignTransitionNames() {
    document.querySelectorAll( '.archive-row[data-photo-id]' ).forEach( ( row ) => {
      const id   = row.dataset.photoId;
      const img  = row.querySelector( '.archive-row__thumb img' );
      if ( img && id ) {
        img.style.viewTransitionName = `photo-${ id }`;
      }
    } );
  }

  /**
   * Wire up click handlers on archive rows.
   * Uses View Transitions API if available.
   */
  function wireArchiveRows() {
    document.querySelectorAll( '.archive-row[data-href]' ).forEach( ( row ) => {
      row.addEventListener( 'click', ( e ) => {
        // Ignore modifier keys (open in new tab, etc.)
        if ( e.ctrlKey || e.metaKey || e.shiftKey ) {
          return;
        }
        e.preventDefault();

        const href = row.dataset.href;

        if ( ! document.startViewTransition ) {
          window.location.href = href;
          return;
        }

        document.startViewTransition( () => {
          window.location.href = href;
        } );
      } );
    } );
  }

  /**
   * On the single photo (dossier) page, assign the matching
   * view-transition-name to the large image so it connects
   * to the thumbnail in the archive.
   */
  function wireDossierImage() {
    const dossierImage = document.querySelector( '.photo-dossier__image[data-photo-id]' );
    if ( ! dossierImage ) {
      return;
    }
    const id = dossierImage.dataset.photoId;
    if ( id ) {
      dossierImage.style.viewTransitionName = `photo-${ id }`;
    }
  }

  function init() {
    assignTransitionNames();
    wireArchiveRows();
    wireDossierImage();
  }

  if ( document.readyState === 'loading' ) {
    document.addEventListener( 'DOMContentLoaded', init );
  } else {
    init();
  }
} )();
