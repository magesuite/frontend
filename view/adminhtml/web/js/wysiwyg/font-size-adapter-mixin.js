/**
 * Mixes the Font Size select into every core WYSIWYG (HugeRTE) editor instance.
 */
define([
    'MageSuite_Frontend/js/wysiwyg/font-size-plugin'
], function (fontSize) {
    'use strict';

    const appendToolbarItem = (settings) => {
        if (typeof settings.toolbar === 'string' && settings.toolbar.trim().length) {
            settings.toolbar = `${settings.toolbar} | ${fontSize.toolbarItem}`;

            return;
        }

        if (typeof settings.toolbar1 === 'string' && settings.toolbar1.trim().length) {
            settings.toolbar1 = `${settings.toolbar1} | ${fontSize.toolbarItem}`;

            return;
        }

        settings.toolbar = fontSize.toolbarItem;
    };

    const wrapSetup = (originalSetup, levels) => (editor) => {
        if (typeof originalSetup === 'function') {
            originalSetup(editor);
        }

        fontSize.registerButton(editor, levels);
    };

    return function (adapterPrototype) {
        const originalGetSettings = adapterPrototype.getSettings;

        /**
         * Overridden: adds the Font Size formats and toolbar button to the core
         * settings, then delegates to the original.
         */
        adapterPrototype.getSettings = function (...args) {
            const settings = originalGetSettings.apply(this, args);
            const levels = fontSize.levels;

            settings.formats = { ...settings.formats, ...fontSize.buildFormats(levels) };

            appendToolbarItem(settings);

            settings.setup = wrapSetup(settings.setup, levels);

            return settings;
        };

        return adapterPrototype;
    };
});
