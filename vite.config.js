import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

const projectRoot = fileURLToPath(new URL('.', import.meta.url));

export default defineConfig({
  plugins: [vue()],
  build: {
    manifest: 'manifest.json',
    outDir: 'app/public/assets/build',
    emptyOutDir: true,
    rollupOptions: {
      input: `${projectRoot}app/resources/js/main.js`,
    },
  },
});
