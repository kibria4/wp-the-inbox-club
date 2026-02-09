import { defineConfig } from 'vite';
import dotenv from 'dotenv';
import dotenvExpand from 'dotenv-expand';
import tailwindcss from '@tailwindcss/vite';
import path from 'path';
import { fileURLToPath } from 'url';
import FullReload from 'vite-plugin-full-reload'; // 👈 NEW

const envLocal = dotenv.config({ path: '.env.local' });
dotenvExpand.expand(envLocal);
const env = dotenv.config({ path: '.env' });
dotenvExpand.expand(env);

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Hardcode theme dir for now – simple & explicit.
const THEME_DIR = envLocal.parsed?.THEME_DIR || env.parsed?.THEME_DIR || 'boogiewoogie-theme';

export default defineConfig(({ mode }) => {
  const isDev = mode === 'development';

  return {
    root: __dirname,
    base: '/',
    build: {
      outDir: path.resolve(__dirname, 'dist'),
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: {
          main: path.resolve(__dirname, 'assets/js/index.js'),
          styles: path.resolve(__dirname, 'assets/css/style.css'),
        },
        output: {
          entryFileNames: isDev ? '[name].js' : '[name].[hash].js',
          assetFileNames: isDev ? '[name].[ext]' : '[name].[hash].[ext]',
        },
      },
    },
    server: {
      host: 'localhost',
      port: 5173,
      strictPort: true,
      cors: true,
      watch: {
        usePolling: true,
      },
      origin: 'http://localhost:5173',
    },
    plugins: [
      tailwindcss(),

      // 🔥 Full page reload when Twig / block templates / PHP views change
      FullReload([
        // Timber templates
        'templates/**/*.twig',

        // Block templates & parts (FSE)
        'block-templates/**/*.html',
        'block-template-parts/**/*.html',

        // Optional: if you have PHP “view-ish” files you want to trigger reload on
        // 'templates/**/*.php',
        // 'src/Block/**/*.php',

        // 'templates/**/*',
        // 'block-templates/**/*',
        // 'block-template-parts/**/*',
        // 'src/Block/**/*.php',
        // 'src/Shortcode/**/*.php',
      ]),
    ],
  };
});