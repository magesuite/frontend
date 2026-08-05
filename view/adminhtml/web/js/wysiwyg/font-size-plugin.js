/**
 * WYSIWYG Font Size select: applies a semantic .h1-.h6 size class to the selected block.
 */
define(['mage/translate'], function ($t) {
    'use strict';

    const config = {
        toolbarItem: 'csfontsize',
        formatPrefix: 'csfontsize_',
        formatSelector: 'h1,h2,h3,h4,h5,h6,p,div,li,span',
        buttonLabel: $t('Font Size'),
        clearLabel: $t('Clear font size'),
        levels: [
            { title: $t('Heading 1'), className: 'h1' },
            { title: $t('Heading 2'), className: 'h2' },
            { title: $t('Heading 3'), className: 'h3' },
            { title: $t('Heading 4'), className: 'h4' },
            { title: $t('Heading 5'), className: 'h5' },
            { title: $t('Heading 6'), className: 'h6' }
        ]
    };

    const getFormatName = (level) => config.formatPrefix + level.className;

    const buildFormats = (levels) => levels.reduce((formats, level) => {
        formats[getFormatName(level)] = {
            selector: config.formatSelector,
            classes: level.className
        };

        return formats;
    }, {});

    const removeAllLevels = (editor, levels) =>
        levels.forEach((level) => editor.formatter.remove(getFormatName(level)));

    const toggleLevel = (editor, levels, level) => {
        const formatName = getFormatName(level);

        if (editor.formatter.match(formatName)) {
            editor.formatter.remove(formatName);

            return;
        }

        removeAllLevels(editor, levels);
        editor.formatter.apply(formatName);
    };

    const buildLevelMenuItem = (editor, levels, level) => {
        const formatName = getFormatName(level);

        return {
            type: 'togglemenuitem',
            text: level.title,
            onAction: () => toggleLevel(editor, levels, level),
            onSetup: (api) => {
                api.setActive(editor.formatter.match(formatName));

                return editor.formatter.formatChanged(formatName, (state) => api.setActive(state)).unbind;
            }
        };
    };

    const getActiveLevel = (editor, levels) =>
        levels.find((level) => editor.formatter.match(getFormatName(level)));

    const registerButton = (editor, levels) => {
        const formatNames = levels.map(getFormatName).join(',');

        const syncButtonLabel = (api) => {
            const active = getActiveLevel(editor, levels);
            api.setText(active ? active.title : config.buttonLabel);
        };

        editor.ui.registry.addMenuButton(config.toolbarItem, {
            icon: 'change-case',
            text: config.buttonLabel,
            tooltip: config.buttonLabel,
            onSetup: (api) => {
                syncButtonLabel(api);

                return editor.formatter.formatChanged(formatNames, () => syncButtonLabel(api)).unbind;
            },
            fetch: (callback) => {
                const items = levels.map((level) => buildLevelMenuItem(editor, levels, level));

                items.push({ type: 'separator' });
                items.push({
                    type: 'menuitem',
                    text: config.clearLabel,
                    onAction: () => removeAllLevels(editor, levels)
                });

                callback(items);
            }
        });
    };

    return {
        toolbarItem: config.toolbarItem,
        levels: config.levels,
        buildFormats,
        registerButton
    };
});
