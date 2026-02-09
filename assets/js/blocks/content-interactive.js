/**
 * Interactivity store for "theme/content-interactive".
 *
 * This file is loaded as a script module from:
 *   assets/js/interactivity/content-interactive.js
 *
 * It is registered and enqueued by the core AssetLoaderService and
 * can be referenced by block.json via "viewScriptModule".
 */

/* global console, document */
import { store } from '@wordpress/interactivity';

console.log('[theme/content-interactive] Initialising interactivity store…');

// Find the first matching root to read its data-initial-message attribute
const ROOT_SELECTOR = '[data-wp-interactive="theme/content-interactive"]';
const rootEl = document.querySelector(ROOT_SELECTOR);

let initialMessage = 'Click the button to run a demo action.';

if (rootEl && rootEl.dataset.initialMessage) {
    initialMessage = rootEl.dataset.initialMessage;
}

const { state } = store('theme/content-interactive', {
    state: {
        message: initialMessage,
        isLoading: false,
        clickCount: 0,
        error: '',
    },
    actions: {
        async demoToggle() {
            // Guard: avoid double-clicks while already loading
            if (state.isLoading) {
                return;
            }

            state.isLoading = true;
            state.error = '';
            state.message = 'Working on it…';

            try {
                // Fake async work (e.g. imagine a network call here)
                await new Promise((resolve) => setTimeout(resolve, 800));

                state.clickCount += 1;

                // Alternate messages based on number of clicks
                if (state.clickCount === 1) {
                    state.message = 'Demo action complete. Click again to toggle the message.';
                } else if (state.clickCount === 2) {
                    state.message = 'Done again! You can keep clicking to see the state update.';
                } else {
                    state.message = 'You have run the demo action ' + state.clickCount + ' times.';
                }
            } catch (error) {
                console.error('[theme/content-interactive] Demo action error:', error);
                state.error = 'Something went wrong. Please try again.';
                state.message = initialMessage;
            } finally {
                state.isLoading = false;
            }
        },

        resetDemo() {
            // Reset state to initial values
            state.isLoading  = false;
            state.clickCount = 0;
            state.error      = '';
            state.message    = initialMessage;

            console.info('[theme/content-interactive] State reset to initial message.');
        },
    },
});

console.info('[theme/content-interactive] Store ready; initial message:', state.message);
