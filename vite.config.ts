import reactRefresh from '@vitejs/plugin-react-refresh';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import liveReload from 'vite-plugin-live-reload';
import path from 'path';
import fs from 'fs';

const rootpath = './resources/assets/scripts';
const themeDirName = path.basename(__dirname);

function getTopLevelFiles(): Record<string, string> {
  let topLevelFiles = fs.readdirSync(path.resolve(__dirname, rootpath));
  let files: { [key: string]: string } = {};
  topLevelFiles.forEach((file) => {
    const isFile = fs.lstatSync(path.resolve(rootpath, file)).isFile();
    if (isFile && !file.includes('.d.ts')) {
      const chunkName = file.slice(0, file.lastIndexOf('.'));
      files[chunkName] = path.resolve(rootpath, file);
    }
  });
  return files;
}

export default defineConfig({
  root: rootpath,
  base: process.env.APP_ENV === 'development' ? '/' : `/wp-content/themes/${themeDirName}/dist/`,
  build: {
    manifest: true,
    emptyOutDir: true,
    outDir: path.resolve(__dirname, 'dist'),
    assetsDir: '',
    rollupOptions: {
      input: getTopLevelFiles(),
    },
  },
  server: {
    // required to load scripts from custom host
    cors: true,
    strictPort: true,
    port: 3000,
    hmr: {
      port: 3000,
      host: 'localhost',
      protocol: 'ws',
    },
  },
  plugins: [vue(), reactRefresh(), liveReload(`${__dirname}/**/*\.php`)],
});
