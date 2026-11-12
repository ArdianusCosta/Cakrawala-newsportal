const fs = require('fs');

console.log('--- Verifying Extension Files ---');

// 1. Manifest
try {
  const manifestRaw = fs.readFileSync('extension-cakrawala-autoreload/manifest.json', 'utf8');
  const manifest = JSON.parse(manifestRaw);
  console.log('[OK] manifest.json is valid JSON');
  console.log('     Name:', manifest.name);
  console.log('     Version:', manifest.version);
  console.log('     Manifest version:', manifest.manifest_version);
} catch (e) {
  console.error('[FAIL] manifest.json error:', e.message);
}

// 2. Icons
['16', '48', '128'].forEach(size => {
  const p = `extension-cakrawala-autoreload/icons/icon${size}.png`;
  if (fs.existsSync(p) && fs.statSync(p).size > 100) {
    console.log(`[OK] ${p} exists (${fs.statSync(p).size} bytes)`);
  } else {
    console.error(`[FAIL] ${p} missing or empty`);
  }
});

// 3. Files check
const expectedFiles = [
  'popup.html',
  'popup.css',
  'popup.js',
  'background.js',
  'content.js',
  'storage.js',
  'README.md'
];

expectedFiles.forEach(f => {
  const p = `extension-cakrawala-autoreload/${f}`;
  if (fs.existsSync(p) && fs.statSync(p).size > 0) {
    console.log(`[OK] ${f} exists (${fs.statSync(p).size} bytes)`);
  } else {
    console.error(`[FAIL] ${f} missing or empty`);
  }
});

