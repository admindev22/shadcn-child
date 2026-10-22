# Shadcn Child

This is a WordPress block-theme child of `shadcn`. It inherits the parent templates, template parts, patterns, WooCommerce support, and dark-mode behavior. Only child-owned tokens, assets, and patterns are added.

## Build

```bash
npm install
npm run build
```

Use `npm run dev` while authoring. The compiled file is `dist/app.css`; it is versioned with a file-modification cache key and enqueued on the frontend and in the block editor.

## Install

Copy `wp-content/themes/shadcn-child` into the site's themes directory, run the build in that directory, then activate **Shadcn Child** from Appearance > Themes. The parent theme must remain installed.

## Verify

```bash
wp theme status shadcn-child
wp theme activate shadcn-child
npm run build
```

The parent theme's user-customized templates and styles can override filesystem defaults. Confirm the active theme and reset affected Site Editor customizations when testing a new default.
