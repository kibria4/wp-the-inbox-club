// scripts/sync-theme-json.mjs
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Theme root = one level up from /scripts
const themeRoot = path.resolve(__dirname, '..');
const stylePath = path.join(themeRoot, 'assets/css/style.css');
const themeJsonPath = path.join(themeRoot, 'theme.json');

function slugToName(slug) {
  return slug
    .replace(/-/g, ' ')
    .replace(/\b\w/g, (c) => c.toUpperCase());
}

function parseDesignTokens(css) {
  const colors = [];
  const fonts = [];

  // --color-*
  const colorRegex = /--color-([a-z0-9-]+)\s*:\s*([^;]+);/gi;
  let match;
  while ((match = colorRegex.exec(css)) !== null) {
    const slug = match[1].trim();
    const color = match[2].trim();
    colors.push({
      slug,
      name: slugToName(slug),
      color,
    });
  }

  // --font-*
  const fontRegex = /--font-([a-z0-9-]+)\s*:\s*([^;]+);/gi;
  while ((match = fontRegex.exec(css)) !== null) {
    const slug = match[1].trim();
    const fontFamily = match[2].trim();
    fonts.push({
      slug,
      name: slugToName(slug),
      fontFamily,
    });
  }

  return { colors, fonts };
}

function loadThemeJson() {
  if (!fs.existsSync(themeJsonPath)) {
    // scripts/sync-theme-json.mjs (inside loadThemeJson, in the "no file" branch)
    return {
      $schema: "https://schemas.wp.org/trunk/theme.json",
      version: 3,
      settings: {},
      styles: {},
    };
  }

  const raw = fs.readFileSync(themeJsonPath, 'utf8');
  try {
    return JSON.parse(raw);
  } catch (e) {
    console.error('❌ Failed to parse theme.json, aborting sync.');
    console.error(e);
    process.exit(1);
  }
}

function saveThemeJson(json) {
  fs.writeFileSync(themeJsonPath, JSON.stringify(json, null, 2) + '\n', 'utf8');
  console.log('✅ theme.json updated from assets/css/style.css');
}

(function main() {
  if (!fs.existsSync(stylePath)) {
    console.error('❌ assets/css/style.css not found, cannot sync theme.json');
    process.exit(1);
  }

  const css = fs.readFileSync(stylePath, 'utf8');
  const { colors, fonts } = parseDesignTokens(css);

  const theme = loadThemeJson();

  theme.settings = theme.settings || {};
  theme.settings.color = theme.settings.color || {};
  theme.settings.typography = theme.settings.typography || {};
  theme.settings.spacing = theme.settings.spacing || {};
  theme.settings.layout = theme.settings.layout || {};

  // --- COLOURS: Brand palette only ---
  theme.settings.color.palette = colors;
  // Disable default WP/core palette + custom colours
  theme.settings.color.defaultPalette = false;
  theme.settings.color.custom = false;

  // --- TYPOGRAPHY: font families from --font-* ---
  theme.settings.typography.fontFamilies = fonts;

  // --- FSE goodies: layout + spacing presets (opinionated defaults) ---
  if (!theme.settings.layout.contentSize) {
    theme.settings.layout.contentSize = '720px';
  }
  if (!theme.settings.layout.wideSize) {
    theme.settings.layout.wideSize = '1080px';
  }

  // Allow common units + spacing controls
  theme.settings.spacing.units = theme.settings.spacing.units || [
    'px', 'em', 'rem', 'vh', 'vw', '%',
  ];
  theme.settings.spacing.padding = true;
  theme.settings.spacing.margin = true;
  theme.settings.spacing.blockGap = true;

  saveThemeJson(theme);
})();