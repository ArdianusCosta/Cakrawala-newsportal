const fs = require('fs');
const zlib = require('zlib');

function crc32(buf) {
  let table = [];
  for (let i = 0; i < 256; i++) {
    let c = i;
    for (let k = 0; k < 8; k++) {
      c = (c & 1) ? (0xEDB88320 ^ (c >>> 1)) : (c >>> 1);
    }
    table[i] = c >>> 0;
  }
  let c = 0xFFFFFFFF;
  for (let i = 0; i < buf.length; i++) {
    c = table[(c ^ buf[i]) & 0xFF] ^ (c >>> 8);
  }
  return (c ^ 0xFFFFFFFF) >>> 0;
}

function createChunk(type, data) {
  const typeBuf = Buffer.from(type, 'ascii');
  const lenBuf = Buffer.alloc(4);
  lenBuf.writeUInt32BE(data.length, 0);
  const toCrc = Buffer.concat([typeBuf, data]);
  const crcVal = crc32(toCrc);
  const crcBuf = Buffer.alloc(4);
  crcBuf.writeUInt32BE(crcVal, 0);
  return Buffer.concat([lenBuf, typeBuf, data, crcBuf]);
}

function makePng(size) {
  const width = size;
  const height = size;
  const rowLen = width * 4;
  const rawData = Buffer.alloc((1 + rowLen) * height);
  const rCenter = size / 2;
  const radius = size * 0.44;
  const innerRadius = size * 0.26;

  for (let y = 0; y < height; y++) {
    const rowOffset = y * (1 + rowLen);
    rawData[rowOffset] = 0; // filter 0
    for (let x = 0; x < width; x++) {
      const pxOffset = rowOffset + 1 + x * 4;
      const dx = x - rCenter;
      const dy = y - rCenter;
      const dist = Math.sqrt(dx * dx + dy * dy);
      
      // Rounded square background with Cakrawala Red (#dc2626)
      const cornerR = size * 0.22;
      const qx = Math.max(0, Math.abs(dx) - (rCenter - cornerR));
      const qy = Math.max(0, Math.abs(dy) - (rCenter - cornerR));
      const qDist = Math.sqrt(qx * qx + qy * qy);

      if (qDist <= cornerR) {
        let r = 220, g = 38, b = 38, a = 255;
        
        // Draw white reload circular arc
        const angle = Math.atan2(dy, dx);
        if (dist >= innerRadius && dist <= radius) {
          if (!(angle > -0.7 && angle < 0.4)) {
            r = 255; g = 255; b = 255;
          }
        }
        // Arrow head near top right
        const ax = dx - (rCenter * 0.35);
        const ay = dy - (-rCenter * 0.32);
        if (Math.abs(ax) <= size * 0.14 && Math.abs(ay) <= size * 0.14) {
          r = 255; g = 255; b = 255;
        }

        rawData[pxOffset] = r;
        rawData[pxOffset + 1] = g;
        rawData[pxOffset + 2] = b;
        rawData[pxOffset + 3] = a;
      } else {
        rawData[pxOffset] = 0;
        rawData[pxOffset + 1] = 0;
        rawData[pxOffset + 2] = 0;
        rawData[pxOffset + 3] = 0;
      }
    }
  }

  const ihdr = Buffer.alloc(13);
  ihdr.writeUInt32BE(width, 0);
  ihdr.writeUInt32BE(height, 4);
  ihdr[8] = 8; // bit depth
  ihdr[9] = 6; // RGBA
  ihdr[10] = 0; // compression
  ihdr[11] = 0; // filter
  ihdr[12] = 0; // interlace

  const idatData = zlib.deflateSync(rawData);
  const signature = Buffer.from([137, 80, 78, 71, 13, 10, 26, 10]);
  const ihdrChunk = createChunk('IHDR', ihdr);
  const idatChunk = createChunk('IDAT', idatData);
  const iendChunk = createChunk('IEND', Buffer.alloc(0));

  return Buffer.concat([signature, ihdrChunk, idatChunk, iendChunk]);
}

[16, 48, 128].forEach(sz => {
  fs.writeFileSync(`extension-cakrawala-autoreload/icons/icon${sz}.png`, makePng(sz));
  console.log(`Generated icon${sz}.png`);
});

