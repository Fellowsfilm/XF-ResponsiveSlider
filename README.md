# [VersoBit] Responsive Slider

A lightweight ResponsiveSlides.js powered slider for XenForo forums, with public slide management, responsive image variants, and widget rendering.

## Requirements

- PHP 7.2.0+
- XenForo 2.1.8+

## What it does

- Adds a `[VersoBit] Responsive Slider` widget, installed by default at `forum_overview_top`.
- Renders active slides through ResponsiveSlides.js when more than one slide is available.
- Displays a single slide without loading the slider JavaScript.
- Supports slide titles, image alt text, link URLs, new-window links, display ordering, and active/disabled state.
- Stores uploaded slide images in `data://pub-data/slider` with randomized filenames.
- Generates optimized JPEG image variants at 640, 960, 1280, and 1920 pixels wide for responsive `srcset` output.
- Provides a public management page at `tools/responsive-slider` for admins and users with the `Manage responsive slider` permission.
- Allows optional custom HTML/CSS injection through `vbResponsiveSlider_extra` and `vbResponsiveSlider_extra.less`.

## Options

#### [VersoBit] Responsive Slider

| Name | Description |
|---|---|
| Enable Responsive Slider | Toggles display of the slider widget. |
| Automatic Rotation | Enables automatic slide rotation. |
| Pause automatic transitions on hover. | Pauses automatic transitions while the cursor is over the slider. |
| Animation Speed | Transition speed in milliseconds. |
| Transition Delay | Delay in milliseconds before moving to the next slide. Automatic rotation must be enabled. |
| Randomize Slides | Randomizes slide order on render. |
| Enable Custom HTML/CSS Injection | Includes the extra slider HTML and LESS templates inside the slider frame. |

## Style properties

#### [VersoBit] Responsive Slider

| Name | Description |
|---|---|
| Slider Width | Controls the slider container width. |
| Slide Height | Controls the configured desktop slide height. |
| Border Radius | Controls the slider image/container border radius. |
| Container Box Shadow | Controls the slider container shadow. |
| Slider Container Margin Bottom | Controls spacing below the slider. |
| Float Position | Controls the default float position. |

## Slide management

Admins can manage slides automatically. Non-admin users need the `Manage responsive slider` general permission.

Manage slides from:

```text
tools/responsive-slider
```

Each slide requires an uploaded image before it can be saved. If image alt text is left blank, the slide title is used as the fallback alt text.

## Installation

Upload the contents of the release zip's `upload/` directory to the XenForo installation root, then install or upgrade `[VersoBit] Responsive Slider` from the XenForo Admin control panel.

After installation, enable the slider option and add slides from the responsive slider management page.

## Development

This is the development package repo for:

```text
/src/addons/VersoBit/ResponsiveSlider
```

Build output includes bundled JavaScript from `_files/js/VersoBit/` as configured by `build.json`.

## Uninstall behavior

Uninstall removes the `xf_vb_responsive_slider_slide` table and the `vbResponsiveSlider` widget. Uploaded slide image files under `data://pub-data/slider` should be removed manually if they are no longer needed.
